# MyDigitalShadow

**Personal Digital Hygiene & Corporate Attack Surface Platform**

A zero-third-party-lookup, AI-powered OSINT platform for Indian individuals and enterprises.

## Architecture

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│   React SPA     │────▶│  Laravel API    │────▶│  PostgreSQL     │
│  (Frontend)     │     │  (Business)     │     │  (Primary DB)   │
└─────────────────┘     └─────────────────┘     └─────────────────┘
         │                       │
         │              ┌────────▼────────┐
         │              │  FastAPI Engine  │
         │              │  (OSINT + AI)    │
         │              └────────┬────────┘
         │                       │
    ┌────▼────┐            ┌────▼────┐    ┌─────────┐
    │ Nginx   │            │ Ollama  │    │  Redis  │
    │ (SSL)   │            │ (LLM)   │    │ (Queue) │
    └─────────┘            └─────────┘    └─────────┘
```

## Dual Path

| Individuals | Organizations |
|------------|---------------|
| DigiLocker eKYC | DNS TXT / HTTP File verification |
| PAN-Aadhaar link check | CIN/GSTIN validation |
| Selfie liveness | Multi-admin RBAC |
| Name deep-dive scan | Attack surface discovery |
| Breach + paste monitoring | Employee exposure tracking |
| Personal remediation | Brand protection + threat intel |

## Quick Start

```bash
git clone https://github.com/shivapp708-art/DigitalShadow.git
cd DigitalShadow
cp .env.example .env
# Edit .env with your secrets
make setup
make db-migrate
make ollama-pull
make start
```

## Technology Stack

| Layer | Technology |
|-------|----------|
| Frontend | React 18 + Vite + Tailwind CSS |
| Business API | Laravel 11 + PHP 8.3 + PostgreSQL 16 |
| OSINT Engine | FastAPI + Python 3.11 + AsyncIO |
| AI | Ollama (Mistral 7B) + Gemini Flash fallback |
| Queue | Redis + Laravel Queues + Celery |
| Reverse Proxy | Nginx + Certbot |
| Infrastructure | Docker Compose |

## Compliance

- DPDP Act 2023 (India)
- Aadhaar Act 2016
- ISO 27001 aligned
- No raw government ID storage

## License

Proprietary - MyDigitalShadow Technologies
