 CTF-4 : Linux SUID & PATH Hijacking Challenge 



Challenge CTF de niveau intermédiaire axé sur l'exploitation de binaires SUID et le PATH hijacking. Ce challenge enseigne les techniques d'élévation de privilèges via la manipulation de la variable d'environnement PATH.
Objectif

Lire le flag situé dans `/root/flag.txt` en exploitant un binaire SUID vulnérable.

`CTF{path_hijacking_and_permissions_master}`



Nécessite Docker et Docker Compose installés

LANCEMENT

cd CTF/CTF-4-linux-suid
docker-compose up -d --build
docker exec -it ctf4-linux-suid /bin/bash

Qu'est-ce qu'un binaire SUID ?

SUID (Set User ID) est un bit de permission spécial qui permet à un binaire de s'exécuter avec les privilèges de son propriétaire, et non de l'utilisateur qui le lance.

Exemple:
```bash
-rwsr-xr-x 1 root ctfgroup 12345 check_system
```

- Le `s` dans `rws` indique le bit SUID
- Ce binaire appartient à `root` mais est exécutable par `ctfgroup`
- Quand `ctfuser` l'exécute, il tourne avec les privilèges de `root` !

PATH Hijacking

Lorsqu'un programme appelle une commande système sans chemin absolu (ex: `system("ls")` au lieu de `system("/bin/ls")`), le shell cherche cette commande dans les répertoires listés dans la variable `PATH`.

Exploitation :
1. Créer un script malveillant nommé comme la commande ciblée
2. Ajouter le répertoire du script en début de PATH
3. Exécuter le binaire vulnérable
4. Le script malveillant s'exécute avec les privilèges SUID !

Indices progressifs


Indice 1 - Énumération

Commencez par chercher les binaires SUID sur le système. Un binaire dans `/usr/local/bin/` pourrait être intéressant...

Indice 2 - Analyse du binaire

Utilisez `strings` pour voir quelles commandes le binaire appelle. Cherchez des appels à `system()` avec des commandes sans chemin absolu.

```bash
strings /usr/local/bin/check_system
```

Indice 3 - Exploitation

Le binaire appelle probablement des commandes comme `whoami`, `id`, ou `ls` sans `/bin/` devant. Vous pouvez créer votre propre version malveillante !

Indice 4 - PATH Hijacking

Créez un script `whoami` dans un répertoire personnel, ajoutez ce répertoire au début de votre PATH, puis exécutez le binaire SUID.




