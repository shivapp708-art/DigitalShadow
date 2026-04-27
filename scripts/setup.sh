#!/bin/bash
set -e

echo "=== MyDigitalShadow Setup ==="

if ! command -v docker &> /dev/null; then
    echo "Docker not found. Installing..."
    curl -fsSL https://get.docker.com | sh
    sudo usermod -aG docker $USER
    echo "Docker installed. Please logout and login again, then re-run this script."
    exit 1
fi

mkdir -p backups logs

if [ ! -f .env ]; then
    cp .env.example .env
    echo "Created .env - please edit it with your secrets before continuing!"
fi

docker compose build

echo "=== Setup Complete ==="
echo "Next: edit .env, then run: make db-migrate && make ollama-pull && make start"
