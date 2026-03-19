# 🚌 NammaBus — Laravel Backend API

> நம்ம பஸ் — Real-time bus tracking for everyone.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📖 About

NammaBus is a real-time bus tracking application built for Tamil Nadu commuters. This repository contains the **Laravel 12 REST API backend** that powers the Flutter mobile app.

Features:
- REST API for bus schedule management
- Real-time location broadcasting via **Laravel Reverb** (WebSocket)
- Role-based authentication — **Student** and **Driver** roles
- Sanctum token-based API auth
- Google & GitHub OAuth via Laravel Socialite
- Haversine-based proximity alert calculation
- Password reset via email

---

## 🗂️ Project Structure

```
app/
├── Http/
│   └── Controllers/
│       ├── AuthController.php          # Login, register, OAuth
│       ├── BusController.php           # Bus CRUD + live location update
│       └── Auth/
│           └── PasswordResetController.php
├── Models/
│   ├── User.php                        # Student / Driver roles
│   └── BusDetail.php                  # Bus schedule + coordinates
├── Events/
│   └── BusLocationUpdated.php         # Reverb broadcast event
routes/
├── api.php                            # All API routes
└── channels.php                       # WebSocket channel auth
database/
└── migrations/                        # All table migrations
```

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| PHP | >= 8.2 |
| Database | MySQL |
| Authentication | Laravel Sanctum |
| WebSocket | Laravel Reverb |
| Social Auth | Laravel Socialite |
| Queue | Database driver |

---

## 🚀 Local Setup

### Prerequisites
- PHP >= 8.2
- MySQL
- Composer

### Installation

```bash
# Clone the repository
git clone https://github.com/harimsd07/Bus-App-Laravel.git
cd Bus-App-Laravel

# Install dependencies
composer install --ignore-platform-reqs

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your .env
# Set DB_DATABASE, DB_USERNAME, DB_PASSWORD

# Run migrations
php artisan migrate

# Start the API server
php artisan serve

# In a second terminal — start WebSocket server
php artisan reverb:start --host=0.0.0.0 --port=8080

# In a third terminal — start queue worker
php artisan queue:listen
```

---

## ⚙️ Environment Variables

```env
APP_NAME=NammaBus
APP_ENV=local
APP_URL=http://127.0.0.1:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=busapp
DB_USERNAME=root
DB_PASSWORD=

# WebSocket — Laravel Reverb
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your_app_id
REVERB_APP_KEY=your_app_key
REVERB_APP_SECRET=your_app_secret
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

# Driver registration secret
DRIVER_SECRET_KEY=YOUR_SECRET_KEY

# Queue
QUEUE_CONNECTION=database
```

---

## 📡 API Endpoints

### Authentication
| Method | Endpoint | Description | Auth |
|---|---|---|---|
| POST | `/api/register` | Register student or driver | Public |
| POST | `/api/login` | Login and get token | Public |
| POST | `/api/password/email` | Send password reset link | Public |
| POST | `/api/password/reset` | Reset password | Public |
| GET | `/api/auth/{provider}/redirect` | OAuth redirect (google/github) | Public |
| GET | `/api/auth/{provider}/callback` | OAuth callback | Public |

### Bus Management
| Method | Endpoint | Description | Auth |
|---|---|---|---|
| GET | `/api/buses` | Get all buses | Public |
| POST | `/api/buses` | Add a new bus | Public |
| GET | `/api/search-buses` | Search buses by route | Public |
| POST | `/api/bus/update-location` | Update live GPS location | Sanctum |

---

## 🔌 WebSocket Events

Channel: `bus-tracking.{busId}`

| Event | Payload | Description |
|---|---|---|
| `BusLocationUpdated` | `{ bus: { id, latitude, longitude } }` | Fired when driver updates location |

Flutter app subscribes to this channel to move the bus marker on the map in real time.

---

## 🗄️ Database Schema

### users
| Column | Type | Description |
|---|---|---|
| id | bigint | Primary key |
| name | string | Full name |
| email | string | Unique email |
| password | string | Hashed |
| role | string | `student` or `driver` |

### bus_detail_tables
| Column | Type | Description |
|---|---|---|
| id | bigint | Primary key |
| busNameOrbusNo | string | Bus name / route number |
| vehicle_no | string | Registration number |
| pick_up_stop | string | Starting point |
| destination | string | End point |
| pickup_time | time | Departure time (HH:mm:ss) |
| reach_destination_time | time | Arrival time (HH:mm:ss) |
| latitude | decimal(10,8) | Current GPS latitude |
| longitude | decimal(11,8) | Current GPS longitude |
| driver_id | bigint | Assigned driver (nullable) |

---

## 🔐 Role System

| Role | Can do |
|---|---|
| `student` | View buses, search routes, track live location |
| `driver` | All of the above + broadcast GPS location |

Driver registration requires a secret key (`DRIVER_SECRET_KEY` in `.env`) to prevent unauthorized driver accounts.

---

## ☁️ Deployment (Fly.io + PlanetScale)

```bash
# Install Fly CLI
curl -L https://fly.io/install.sh | sh

# Login
fly auth login

# Launch app
fly launch

# Set environment secrets
fly secrets set APP_KEY=your_key
fly secrets set DB_HOST=your_planetscale_host
fly secrets set DB_PASSWORD=your_password
fly secrets set REVERB_APP_KEY=your_reverb_key

# Deploy
fly deploy
```

---

## 🗺️ Roadmap — v2

- [ ] Push notifications when bus is nearby
- [ ] Admin panel for bus management
- [ ] Driver assignment to specific buses
- [ ] Route polyline on map
- [ ] Bus delay reporting

---

## 🤝 Contributing

Pull requests are welcome. For major changes please open an issue first.

---

## 📄 License

MIT License — see [LICENSE](LICENSE) for details.

---

Made with ❤️ in Tiruchirappalli, Tamil Nadu 🇮🇳
