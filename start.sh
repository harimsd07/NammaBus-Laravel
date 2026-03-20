#!/bin/bash
echo "Starting XAMPP MySQL..."
sudo /opt/lampp/lampp start

echo "Starting NammaBus backend..."
/opt/lampp/bin/php artisan serve --host=0.0.0.0 --port=8000 &
/opt/lampp/bin/php artisan reverb:start --host=0.0.0.0 --port=8080 &
/opt/lampp/bin/php artisan queue:listen &

echo "All services started!"
echo "API:    http://192.168.1.36:8000"
echo "Reverb: ws://192.168.1.36:8080"
wait
