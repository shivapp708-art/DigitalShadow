#!/bin/bash
set -e
ENV=${1:-production}
echo "=== Deploying to $ENV ==="
if [ "$ENV" = "production" ]; then
    ssh $VPS_USER@$VPS_HOST "cd /var/www/mydigitalshadow && git pull origin main && docker compose down && docker compose build && docker compose up -d"
else
    docker compose down && docker compose build && docker compose up -d
fi
echo "Deployment complete."
