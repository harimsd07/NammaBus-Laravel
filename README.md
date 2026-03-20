# 🚌 NammaBus — Laravel Backend API

> நம்ம பஸ் — Real-time bus tracking for everyone in Tiruchirappalli.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![Version](https://img.shields.io/badge/Version-v2.0.0-purple?style=flat)](https://github.com/harimsd07/NammaBus-Laravel/releases/tag/v2.0.0)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📱 App Screenshots

The Flutter app powered by this API:

### Splash Screen

| Native Splash | Flutter Splash |
|:---:|:---:|
| <img src="assets/screenshots/00_splash_native.png" width="180"/> | <img src="assets/screenshots/00_splash_flutter.png" width="180"/> |
| Instant purple launch | நம்ம பஸ் · Tamil text · spinner |

---

### Student Experience

| Landing | Bus List (Live) | Live Map |
|:---:|:---:|:---:|
| <img src="assets/screenshots/03_landing.png" width="180"/> | <img src="assets/screenshots/08_bus_list_live.png" width="180"/> | <img src="assets/screenshots/05_live_map_nearby.png" width="180"/> |
| Search without login | LIVE badge via WebSocket | Real-time GPS tracking |

### Driver Experience

| Driver Home | Live Broadcasting | Edit Bus |
|:---:|:---:|:---:|
| <img src="assets/screenshots/17_driver_home_assigned.png" width="180"/> | <img src="assets/screenshots/13_driver_panel_stop.png" width="180"/> | <img src="assets/screenshots/15_edit_bus.png" width="180"/> |
| Assigned bus via /api/my-bus | GPS broadcasting to Reverb | Edit via PUT /api/buses/{id} |

---

## 📖 About

NammaBus Laravel backend powers the real-time bus tracking system. It provides a REST API for bus schedule management, real-time location broadcasting via **Laravel Reverb** (WebSocket), and role-based authentication for Students and Drivers.

---

## 🗂️ Project Structure

```
app/
├── Http/Controllers/
│   ├── AuthController.php          # Login, register, OAuth
│   ├── BusController.php           # Bus CRUD + assignment + live location
│   └── Auth/PasswordResetController.php
├── Models/
│   ├── User.php                    # Student / Driver roles
│   └── BusDetail.php              # Bus schedule + GPS coordinates
├── Events/
│   └── BusLocationUpdated.php     # Reverb broadcast event
routes/
├── api.php                        # All API routes
└── channels.php                   # WebSocket channel auth
database/migrations/               # All table migrations
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
git clone https://github.com/harimsd07/NammaBus-Laravel.git
cd NammaBus-Laravel

composer install --ignore-platform-reqs
cp .env.example .env
php artisan key:generate
```

### Configure `.env`

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=busapp
DB_USERNAME=root
DB_PASSWORD=
# For XAMPP on Linux — add socket path
DB_SOCKET=/opt/lampp/var/mysql/mysql.sock

REVERB_APP_KEY=your_reverb_key
REVERB_PORT=8080
BROADCAST_CONNECTION=reverb
DRIVER_SECRET_KEY=YOUR_ADMIN_SECRET
```

### Run

```bash
# Terminal 1 — API server
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2 — WebSocket server
php artisan reverb:start --host=0.0.0.0 --port=8080

# Terminal 3 — Queue worker
php artisan queue:listen
```

---

## 📡 API Endpoints

### Public Routes

| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/register` | Register student or driver |
| POST | `/api/login` | Login and get Sanctum token |
| POST | `/api/password/email` | Send password reset link |
| POST | `/api/password/reset` | Reset password |
| GET | `/api/buses` | Get all buses |
| GET | `/api/search-buses` | Search buses by route |
| GET | `/api/auth/{provider}/redirect` | OAuth redirect |
| GET | `/api/auth/{provider}/callback` | OAuth callback |

### Protected Routes (Sanctum token required)

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/user` | Get authenticated user |
| GET | `/api/users` | List all users |
| POST | `/api/buses` | Register new bus |
| PUT | `/api/buses/{id}` | Update bus details |
| DELETE | `/api/buses/{id}` | Delete a bus |
| POST | `/api/bus/update-location` | Update live GPS coordinates |
| GET | `/api/my-bus` | Driver fetches their assigned bus |
| POST | `/api/assign-bus` | Assign driver to a bus |
| POST | `/api/unassign-bus` | Remove driver from a bus |

---

## 🔌 WebSocket Events

Channel: `bus-tracking.{busId}`

| Event | Payload | Description |
|---|---|---|
| `BusLocationUpdated` | `{ bus: { id, latitude, longitude } }` | Fired when driver updates GPS |

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
| driver_id | bigint | Assigned driver (nullable FK) |
| busNameOrbusNo | string | Bus name / route number |
| vehicle_no | string | Registration number |
| pick_up_stop | string | Starting point |
| destination | string | End point |
| pickup_time | time | Departure time (HH:mm:ss) |
| reach_destination_time | time | Arrival time (HH:mm:ss) |
| latitude | decimal(10,8) | Current GPS latitude |
| longitude | decimal(11,8) | Current GPS longitude |

---

## 🔐 Role System

| Role | Permissions |
|---|---|
| `student` | View buses, search routes, track live location |
| `driver` | All of the above + broadcast GPS + edit/delete assigned bus |

---

## ☁️ Deployment (Fly.io + PlanetScale)

```bash
# Install Fly CLI and login
fly auth login

# Launch and deploy
fly launch
fly deploy

# Set secrets
fly secrets set APP_KEY=your_key
fly secrets set DB_HOST=your_planetscale_host
fly secrets set DB_PASSWORD=your_password
fly secrets set REVERB_APP_KEY=your_key
fly secrets set DRIVER_SECRET_KEY=your_secret
```

---

## 🗺️ Roadmap — v3

- [ ] Bus ETA calculation endpoint
- [ ] Bus delay reporting by driver
- [ ] FCM push notifications
- [ ] Admin panel (Laravel Blade)
- [ ] API rate limiting
- [ ] Laravel Telescope monitoring

---

## 🤝 Contributing

Pull requests are welcome. For major changes please open an issue first.

---

## 📄 License

MIT License — see [LICENSE](LICENSE) for details.

---

Made with ❤️ in Tiruchirappalli, Tamil Nadu 🇮🇳
