from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
import httpx
import os
import logging

# Configuration du logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = FastAPI(title="API Gateway", version="1.0.0")

class ReportRequest(BaseModel):
    callback_url: str

@app.get("/")
async def root():
    return {
        "service": "API Gateway",
        "version": "1.0.0",
        "endpoints": {
            "POST /api/report": "Send a report with callback URL",
            "GET /health": "Health check"
        }
    }

@app.get("/health")
async def health():
    return {"status": "healthy"}

@app.post("/api/report")
async def create_report(request: ReportRequest):
   
    callback_url = request.callback_url
    logger.info(f"Received report request with callback URL: {callback_url}")

    try:
        async with httpx.AsyncClient(timeout=10.0) as client:
            headers = {
                "X-Internal-Request": "true",
                "X-Gateway-Source": "api-gateway",
                "User-Agent": "InternalGateway/1.0"
            }
            
            logger.info(f"Making callback request to {callback_url} with internal headers")
            response = await client.get(callback_url, headers=headers)
            
            logger.info(f"Callback response status: {response.status_code}")
            
            return {
                "status": "success",
                "message": "Report processed and callback executed",
                "callback_response": {
                    "status_code": response.status_code,
                    "content": response.text[:500]  
                }
            }
    
    except httpx.RequestError as e:
        logger.error(f"Callback request failed: {str(e)}")
        raise HTTPException(
            status_code=400,
            detail=f"Callback request failed: {str(e)}"
        )
    except Exception as e:
        logger.error(f"Unexpected error: {str(e)}")
        raise HTTPException(
            status_code=500,
            detail="Internal server error"
        )

@app.get("/internal/status") 
async def internal_status():
    
    internal_url = os.getenv("INTERNAL_SERVICE_URL", "http://internal-flag:8080")
    return {
        "message": "Internal services are accessible only within the network",
        "internal_service": "internal-flag:8080",
        "note": "Direct access from outside is not possible"
    }

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8080)
