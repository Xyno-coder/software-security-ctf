from fastapi import FastAPI, HTTPException, Header
from typing import Optional
import os
import logging

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = FastAPI(title="Internal Flag Service", version="1.0.0")

# Le flag est stocké dans une variable d'environnement mais si pas de .env on le remet là
FLAG = os.getenv("FLAG", "CTF{htrq56FZAQ}")

@app.get("/")
async def root():
    return {
        "service": "Internal Flag Service",
        "message": "This service is internal only",
        "note": "You should not be able to access this directly from outside"
    }

@app.get("/health")
async def health():
    return {"status": "healthy", "service": "internal-flag"}

@app.get("/flag")
async def get_flag(x_internal_request: Optional[str] = Header(None)):
    """
    Endpoint protégé contenant le flag
    Utilise un simple header HTTP pour l'authentification
    Pas de cryptographie, pas de JWT, pas de secret partagé
    N'importe qui peut forger ce header s'il peut accéder au service
    
    """
    logger.info(f"Flag endpoint accessed. X-Internal-Request header: {x_internal_request}")
    if x_internal_request != "true":
        logger.warning("Unauthorized access attempt - missing or invalid internal header")
        raise HTTPException(
            status_code=403,
            detail={
                "error": "Forbidden",
                "message": "This endpoint is only accessible from internal services",
                "hint": "You need the correct internal authentication header"
            }
        )
    
    logger.info("Valid internal request - returning flag")
    return {
        "status": "success",
        "flag": FLAG,
        "message": "Congratulations! You successfully exploited the SSRF vulnerability!",
        "explanation": {
            "vulnerability": "Server-Side Request Forgery (SSRF)",
            "technique": "Used the API Gateway as a proxy to access internal service",
            "key_concept": "Trust-based authentication using HTTP headers is dangerous"
        }
    }

@app.get("/info")
async def info(x_internal_request: Optional[str] = Header(None)):
    
    if x_internal_request != "true":
        raise HTTPException(
            status_code=403,
            detail="This endpoint is only accessible from internal services"
        )
    
    return {
        "service": "internal-flag",
        "endpoints": {
            "GET /flag": "Returns the CTF flag (requires internal auth)",
            "GET /info": "Returns service information",
            "GET /health": "Health check"
        },
        "authentication": "X-Internal-Request: true",
        "network": "Only accessible within Docker network"
    }

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8080)
