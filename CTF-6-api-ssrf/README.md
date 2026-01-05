CTF-6 : API SSRF & Microservices 

Challenge CTF axé sur l'exploitation SSRF (Server-Side Request Forgery) dans une architecture microservices.

Objectif

Récupérer le flag stocké dans un service interne non exposé, inaccessible directement depuis l'extérieur.

Installation et lancement

Nécessite
- Docker
- Docker Compose
- Postman pour tester les requetes ou cURL


cd CTF/CTF-5-api-ssrf
docker-compose up -d --build



curl http://localhost:8080

