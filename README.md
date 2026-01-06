# CTF Project - Capture The Flag Challenges

Welcome to this educational Capture The Flag (CTF) project! This project contains 6 progressive cybersecurity challenges testing different vulnerabilities and exploitation techniques.

## 📊 Challenges Overview

| # | Name | Type | Difficulty | Description |
|---|------|------|-----------|-------------|
| 1 | Web SQL Injection | Web Security | 🟢 **Easy** | Web fuzzing and SQL injections |
| 2 | JWT Leak | Web Security | 🟡 **Medium** | JWT secret leaks and path traversal |
| 3 | Linux Permissions | Linux Security | 🟢 **Easy** | Linux permissions exploitation |
| 4 | Linux SUID | Linux Security | 🔴 **Hardcore** | PATH hijacking and SUID binaries |
| 5 | PHP Object Injection | Web Security | 🔴 **Hardcore** | PHP deserialization and RCE |
| 6 | API SSRF | Web Security | 🟡 **Medium** | Server-Side Request Forgery in microservices |

---

## 🟢 EASY LEVEL

### CTF-1: Web SQL Injection

**Difficulty:** 🟢 Easy (1/3)

**Description:**
This first challenge introduces the fundamental concepts of Web Fuzzing and SQL injections. The web application contains vulnerable forms exploitable through classic SQL injection techniques.

**Technologies:**
- PHP
- MySQL
- Apache
- Docker

**Concepts Tested:**
- Web fuzzing and reconnaissance
- Basic SQL injections
- SQL query manipulation
- Database information extraction

**Launch:**
```bash
cd CTF/CTF-1-web-sqli-ctf
docker build -t ctf-web-sqli .
docker run --rm -p 8080:80 ctf-web-sqli
```

**Access:**
- URL: `http://localhost:8080`

**Important Files:**
- `create_db.php`: Database creation and initialization
- `index.php`: Vulnerable homepage
- `administrator.php`: Vulnerable admin panel
- `order.php`: Vulnerable order system

---

### CTF-3: Linux Permissions

**Difficulty:** 🟢 Easy (1/3)

**Description:**
This challenge teaches the basics of Linux permissions. The goal is to find a hidden flag in a `.secret.txt` file by intelligently exploring the Docker container's file system.

**Technologies:**
- Linux (Ubuntu)
- Bash scripting
- Docker

**Concepts Tested:**
- Linux permissions (rwx)
- System navigation
- Restricted file access
- Basic system enumeration

**Launch:**
```bash
cd CTF/CTF-3-linux-permissions
docker-compose up -d --build
docker exec -it ctf3-linux-permissions /bin/bash
```

**Or directly:**
```bash
docker build -t ctf-linux-permissions .
docker run -it ctf-linux-permissions
```

**Objective:**
- Read the `.secret.txt` file containing the flag
- Direct access is restricted, you must bypass permissions

**Hints:**
- A `helper.py` file in `/app` provides progressive hints
- Examine permissions with `ls -la`
- Look for alternative paths to the flag

---

## 🟡 MEDIUM LEVEL

### CTF-2: JWT Leak

**Difficulty:** 🟡 Medium (2/3)

**Description:**
This challenge combines multiple web vulnerabilities: secret leaks, path traversal, and JWT (JSON Web Tokens) manipulation. The application accidentally exposes its configuration secrets and JWTs can be forged.

**Technologies:**
- Python (Flask)
- JavaScript (Vite + React)
- JWT (JSON Web Tokens)
- Docker Compose

**Concepts Tested:**
- API reconnaissance
- Path traversal/LFI (Local File Inclusion)
- Sensitive file leaks (.env)
- JWT manipulation and forgery
- Authentication bypass

**Architecture:**
```
Backend: Flask API (Port 8080)
Frontend: Vite + React (Port 5174)
```

**Launch:**
```bash
cd CTF/CTF-2-jwt-leak
docker-compose up -d --build
```

**Access:**
- Frontend: `http://localhost:5174`
- Backend API: `http://localhost:8080`

**Objective:**
- Retrieve the flag by obtaining a valid JWT with `admin` role
- Exploit vulnerabilities to access configuration secrets

**Key Vulnerabilities:**
1. **Path Traversal**: The `/api/download?file=` endpoint is not secure
2. **Env Leak**: The `.env` file contains the JWT secret key
3. **JWT Forgery**: Once the secret is obtained, create an admin token

**Useful Endpoints:**
- `GET /api/download?file=README.txt` - Download files
- `GET /api/admin` - Protected endpoint (requires admin JWT)

---

### CTF-6: API SSRF & Microservices

**Difficulty:** 🟡 Medium (2/3)

**Description:**
This challenge tests SSRF (Server-Side Request Forgery) vulnerability exploitation in a microservices architecture. The flag is stored in an internal service not directly exposed, accessible only via SSRF.

**Technologies:**
- Python (Flask)
- Docker Compose
- Microservices architecture
- Docker networking

**Architecture:**
```
Gateway (Port 8080)
  └─> Internal-Flag Service (Port 8001 - not publicly exposed)
```

**Concepts Tested:**
- Server-Side Request Forgery (SSRF)
- Microservices architecture
- API reconnaissance
- Access restriction bypass
- Exploitation via internal requests

**Launch:**
```bash
cd CTF/CTF-6-api-ssrf
docker-compose up -d --build
```

**Access:**
- Gateway: `http://localhost:8080`
- Internal API: `http://localhost:8001` (container access only)

**Objective:**
Retrieve the flag stored in the internal service by exploiting an SSRF vulnerability in the gateway.

**SSRF Exploitation Technique:**

The idea is to use the gateway service to make requests to the internal service:

```bash
# Check what the gateway exposes
curl http://localhost:8080

# Attempt SSRF to internal service
curl 'http://localhost:8080/proxy?url=http://internal-flag:8001/flag'
```

**Key Points:**
1. The internal service is not directly accessible from your machine
2. The gateway service has access to the internal Docker network
3. An SSRF vulnerability in the gateway allows it to make internal requests
4. By default, Docker containers can resolve by name: `internal-flag:8001`

**Useful Tools:**
- `curl` to test requests
- `docker-compose logs` for debugging
- `docker exec` to inspect containers

---

## 🔴 HARDCORE LEVEL

### CTF-4: Linux SUID & PATH Hijacking

**Difficulty:** 🔴 Hardcore (3/3)

**Description:**
This advanced challenge teaches privilege escalation techniques through SUID binary exploitation and PATH hijacking. The goal is to read `/root/flag.txt` by exploiting a vulnerable binary.

**Flag:** `CTF{path_hijacking_and_permissions_master}`

**Technologies:**
- Linux (Ubuntu)
- C (compiled binaries)
- Bash scripting
- Docker

**Concepts Tested:**
- SUID bit and privilege escalation
- PATH environment variable and search order
- Binary analysis (strings, ltrace, strace)
- Malicious binary creation
- PATH injection attacks

**Launch:**
```bash
cd CTF/CTF-4-linux-suid
docker-compose up -d --build
docker exec -it ctf4-linux-suid /bin/bash
```

**What is a SUID Binary?**

SUID (Set User ID) allows a binary to execute with its owner's privileges:
```bash
-rwsr-xr-x 1 root ctfgroup 12345 check_system
```
- The `s` in `rws` indicates SUID is set
- When executed, the binary runs with `root` privileges

**PATH Hijacking Exploitation Technique:**

1. **Enumeration** - Find SUID binaries:
```bash
find / -perm -4000 2>/dev/null
```

2. **Analysis** - See what commands the binary calls:
```bash
strings /usr/local/bin/check_system
```

3. **Exploitation** - Create a malicious binary:
```bash
mkdir -p /tmp/hijack
echo '#!/bin/bash' > /tmp/hijack/whoami
echo 'cat /root/flag.txt' >> /tmp/hijack/whoami
chmod +x /tmp/hijack/whoami
export PATH=/tmp/hijack:$PATH
/usr/local/bin/check_system
```

**Progressive Hints:**
1. Look for SUID binaries in `/usr/local/bin/`
2. Analyze with `strings` to find `system()` calls
3. Create malicious commands without absolute paths
4. Manipulate the PATH variable

### CTF-5: PHP Object Injection

**Difficulty:** 🔴 Hardcore (3/3)

**Description:**
This advanced challenge tests insecure PHP object deserialization. The application inadvertently exposes its source code via LFI, revealing a deserialization vulnerability allowing complete RCE (Remote Code Execution).

**Technologies:**
- PHP 8.1
- Apache
- Docker

**Architecture:**
- Web service: Apache + PHP 8.1 with Base64-encoded sessions
- Flag stored in `/flag.txt` (accessible after RCE)

**Concepts Tested:**
- Local File Inclusion (LFI) - Vulnerability discovery
- Insecure PHP deserialization
- Gadget chains
- Remote Code Execution (RCE)
- Encoded cookie manipulation

**Launch:**
```bash
cd CTF/CTF-5-php-object-injection
docker-compose up -d --build
# Or directly
docker build -t ctf-php-injection .
docker run -p 8005:80 ctf-php-injection
```

**Access:**
- URL: `http://localhost:8005`

**Exploitation Steps:**

**1️⃣ LFI Discovery**
The application exposes a `?source=` parameter that returns code in Base64:
```bash
curl 'http://localhost:8005/index.php?source=classes'
curl 'http://localhost:8005/index.php?source=index'
```

**2️⃣ Code Analysis**
The `index.php` file uses `unserialize()` without validation:
```php
$decoded_data = base64_decode($_COOKIE['session_data'], true);
$session = unserialize($decoded_data);  // ⚠️ Vulnerable!
```

**3️⃣ Gadget Chain Identification**
The `FileLogger` class contains `__destruct()` allowing file writing:
```php
class FileLogger {
    public $logFile;
    public $message;
    
    public function __destruct() {
        file_put_contents($this->logFile, $this->message);
    }
}
```

**4️⃣ Payload Construction**
Create a serialized object that exploits the gadget chain to execute code.

**Important Files:**
- `index.php`: Main application with intentional LFI
- `classes.php`: Definition of vulnerable classes
- `shell.php`: Webshell (to create via RCE)

**Additional Documentation:**
- `EXPLOIT_GUIDE.md`: Detailed exploitation guide
- `WALKTHROUGH.md`: Complete walkthrough
- `SUMMARY.md`: Concept summary

---

## 🛠️ Requirements

All challenges require:

- **Docker** (version 20.10+)
- **Docker Compose** (version 1.29+)
- **Optional tools**:
  - `curl` or `Postman` for testing APIs
  - `strings` and `ltrace` for binary analysis
  - Text editor or IDE

**Installation:**
```bash
# macOS with Homebrew
brew install docker-compose

# Linux (Ubuntu/Debian)
sudo apt-get install docker.io docker-compose
```

---

## 🚀 Quick Start

```bash
# Clone the project
cd /path/to/CTF-Project-Software-security

# Launch a specific challenge
cd CTF/CTF-1-web-sqli-ctf
docker-compose up -d --build

# Stop services
docker-compose down
```

---

## 📚 Recommended Progression

1. **Start with EASY challenges:**
   - CTF-1: Web SQL Injection
   - CTF-3: Linux Permissions

2. **Progress to MEDIUM challenges:**
   - CTF-2: JWT Leak
   - CTF-6: API SSRF & Microservices

3. **Master HARDCORE challenges:**
   - CTF-4: Linux SUID & PATH Hijacking
   - CTF-5: PHP Object Injection

---

## 🎓 Key Concepts by Domain

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

### Port Already in Use
```bash
# Find and stop the service using the port
lsof -i :8080
kill -9 <PID>

# Or use a different port
docker run -p 9090:80 ctf-web-sqli
```

### Docker Errors
```bash
# Force rebuild
docker-compose up -d --build --force-recreate

# View logs
docker-compose logs -f

# Complete cleanup
docker-compose down -v
docker system prune -a
```

### Connection Issues
```bash
# Check active services
docker ps

# Inspect network configuration
docker network ls
docker network inspect ctf_default
```

---

## 📝 Important Notes

- ⚠️ These challenges contain **intentional vulnerabilities** for educational purposes
- 🔐 Do not use the techniques learned on systems without authorization
- 💡 Read each challenge's README for hints
- 📖 Consult the walkthroughs if you are stuck
- 🎯 The objective is to learn security concepts, not just get the flag

---

## 📞 Additional Resources

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PortSwigger Web Security Academy](https://portswigger.net/web-security)
- [HackTheBox](https://www.hackthebox.com/)
- [TryHackMe](https://tryhackme.com/)
- [PHP Security](https://www.php.net/manual/en/security.php)

---

**Good luck and have fun exploring! 🎯**
