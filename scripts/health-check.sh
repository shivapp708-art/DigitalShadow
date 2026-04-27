#!/bin/bash
ENDPOINTS=(
    "https://mydigitalshadow.in/health"
    "https://api.mydigitalshadow.in/api/v1/health"
    "https://api.mydigitalshadow.in/osint/health"
)
for endpoint in "${ENDPOINTS[@]}"; do
    status=$(curl -s -o /dev/null -w "%{http_code}" "$endpoint")
    if [ "$status" = "200" ]; then echo "OK $endpoint"; else echo "FAIL $endpoint ($status)"; fi
done
docker compose ps
docker stats --no-stream
