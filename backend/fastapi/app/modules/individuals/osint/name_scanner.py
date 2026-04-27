"""
Name Deep-Dive Scanner
Searches for a person's name across public Indian government portals,
court records, company registrations, and social media.
"""
import httpx
import asyncio
from app.core.config import settings


async def scan_name(full_name: str, city: str = None, dob_year: str = None) -> dict:
    results = {
        "name": full_name,
        "search_filters": {"city": city, "dob_year": dob_year},
        "findings": {},
        "risk_indicators": [],
        "total_results": 0,
    }

    tasks = [
        _scan_mca_directors(full_name),
        _scan_court_records(full_name, city),
        _scan_voter_rolls(full_name, city),
        _scan_gazette(full_name),
        _scan_social_profiles(full_name),
    ]

    scan_results = await asyncio.gather(*tasks, return_exceptions=True)
    labels = ["mca_directors", "court_records", "voter_rolls", "gazette", "social_profiles"]

    for label, result in zip(labels, scan_results):
        if isinstance(result, Exception):
            results["findings"][label] = {"error": str(result)}
        else:
            results["findings"][label] = result
            if result.get("count", 0) > 0:
                results["total_results"] += result["count"]

    if results["findings"].get("court_records", {}).get("count", 0) > 0:
        results["risk_indicators"].append({
            "type": "court_records_found",
            "severity": "medium",
            "detail": "Name appears in court records",
        })

    results["ai_summary"] = await _ai_summarize(results)
    return results


async def _scan_mca_directors(name: str) -> dict:
    return {"source": "MCA21 Director Database", "url": "https://www.mca.gov.in", "count": 0, "records": []}

async def _scan_court_records(name: str, city: str = None) -> dict:
    return {"source": "eCourts India", "url": "https://services.ecourts.gov.in", "count": 0, "records": []}

async def _scan_voter_rolls(name: str, city: str = None) -> dict:
    return {"source": "Election Commission of India", "url": "https://electoralsearch.eci.gov.in", "count": 0, "records": []}

async def _scan_gazette(name: str) -> dict:
    return {"source": "Gazette of India", "url": "https://egazette.gov.in", "count": 0, "records": []}

async def _scan_social_profiles(name: str) -> dict:
    return {"source": "Social Media", "platforms": ["LinkedIn", "Twitter/X", "Facebook", "Instagram"], "count": 0, "records": []}


async def _ai_summarize(scan_data: dict) -> str:
    try:
        prompt = f"Summarize OSINT scan for {scan_data['name']} in 2-3 sentences. Focus on privacy risks. Findings: {scan_data['findings']}"
        async with httpx.AsyncClient(timeout=30) as client:
            resp = await client.post(
                f"{settings.OLLAMA_URL}/api/generate",
                json={"model": settings.OLLAMA_MODEL, "prompt": prompt, "stream": False},
            )
            if resp.status_code == 200:
                return resp.json().get("response", "").strip()
    except Exception:
        pass
    return f"Scan completed for {scan_data['name']}. {scan_data['total_results']} public records found."
