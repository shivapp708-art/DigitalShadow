from fastapi import APIRouter
from app.modules.individuals.osint.breach_checker import (
    check_email_breaches, check_phone_breaches, check_username_enum
)
from app.modules.individuals.osint.name_scanner import scan_name

router = APIRouter()

@router.post("/breach/email")
async def email_breach(payload: dict):
    return await check_email_breaches(payload["email"])

@router.post("/breach/phone")
async def phone_breach(payload: dict):
    return await check_phone_breaches(payload["phone"])

@router.post("/username/enum")
async def username_enum(payload: dict):
    return await check_username_enum(payload["username"])

@router.post("/name/scan")
async def name_scan(payload: dict):
    return await scan_name(
        payload["full_name"],
        city=payload.get("city"),
        dob_year=payload.get("dob_year"),
    )
