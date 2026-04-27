from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from app.core.config import settings
from app.modules.individuals.osint import router as individual_router
from app.modules.organizations.osint import router as org_router

app = FastAPI(
    title="MyDigitalShadow OSINT Engine",
    description="Zero-third-party OSINT scanning for India",
    version="1.0.0",
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=[settings.APP_URL, settings.API_URL],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

app.include_router(individual_router, prefix="/individuals", tags=["individuals"])
app.include_router(org_router,        prefix="/organizations", tags=["organizations"])

@app.get("/health")
def health():
    return {"status": "ok", "service": "osint-engine"}
