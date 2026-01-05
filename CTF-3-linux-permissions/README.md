CTF-3 : Linux Permissions Challenge Difficulté 1/3
Trouver le flag caché dans un fichier `.secret.txt` en explorant le système Linux du conteneur Docker.


Nécessite Docker 
Pour lancer: 

cd CTF/CTF-3-linux-permissions
docker-compose up -d --build
docker exec -it ctf3-linux-permissions /bin/bash

OU sinon

docker build -t ctf-linux-permissions 
docker run -it ctf-linux-permissions


Un fichier helper.py est disponible et donne des indices