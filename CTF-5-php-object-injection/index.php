<?php
require_once 'classes.php';

session_start();

if (isset($_COOKIE['session_data'])) {
    $cookie_data = $_COOKIE['session_data'];
    $decoded_data = base64_decode($cookie_data, true);
    
    if ($decoded_data !== false) {
        $session = unserialize($decoded_data);
        
        if ($session instanceof UserSession) {
            $_SESSION['user'] = $session;
        }
    }
}

if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = new UserSession('guest', false);
    $session_object = $_SESSION['user'];
    $serialized = serialize($session_object);
    $encoded = base64_encode($serialized);
    setcookie('session_data', $encoded, time() + 3600, '/');
}

$current_user = $_SESSION['user'];

$source_content = '';
if (isset($_GET['source'])) {
    $requested_file = $_GET['source'];
    
    $allowed_files = ['classes', 'index'];
    
    if (in_array($requested_file, $allowed_files)) {
        $file_path = __DIR__ . '/' . $requested_file . '.php';
        
        if (file_exists($file_path)) {
            $filter_url = 'php://filter/convert.base64-encode/resource=' . $file_path;
            $encoded_source = @file_get_contents($filter_url);
            
            if ($encoded_source !== false) {
                $source_content = base64_decode($encoded_source);
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Portal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        .session-info {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }
        .session-info p {
            color: #555;
            margin: 10px 0;
            font-size: 14px;
        }
        .session-info strong {
            color: #333;
        }
        .admin-badge {
            display: inline-block;
            background: #764ba2;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            margin-left: 10px;
        }
        .admin-badge.no {
            background: #999;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            color: #333;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }
        button {
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        button:hover {
            background: #764ba2;
        }
        .footer {
            text-align: center;
            color: #999;
            font-size: 12px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .source-viewer {
            display: none;
        }
        .source-viewer.active {
            display: block;
            background: #1e1e1e;
            color: #00ff00;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #444;
        }
        .source-viewer pre {
            margin: 0;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .debug-hint {
            display: none;
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
            color: #856404;
            font-size: 12px;
        }
        .debug-hint.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Session Portal</h1>
        
        <div class="session-info">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($current_user->username); ?></p>
            <p><strong>Status:</strong> 
                <span class="admin-badge <?php echo $current_user->is_admin ? '' : 'no'; ?>">
                    <?php echo $current_user->is_admin ? 'ADMIN' : 'USER'; ?>
                </span>
            </p>
        </div>

        <form method="POST" action="update.php">
            <label for="username">Update Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($current_user->username); ?>" required>
            
            <button type="submit">Update Session</button>
        </form>

        <?php if (isset($_GET['source']) && !empty($source_content)): ?>
        <div class="source-viewer active">
            <pre><?php echo htmlspecialchars($source_content); ?></pre>
        </div>
        <?php endif; ?>

        <div class="debug-hint" id="debugHint">
            <strong>💡 Hint:</strong> Systèmes de débogage actifs. Essayez <code>?source=classes</code> ou <code>?source=index</code> pour inspecter le code.
        </div>

        <div class="footer">
            <p>Session data is managed securely</p>
            <p style="margin-top: 10px; font-size: 10px;"></p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (!urlParams.has('source')) {
                setTimeout(function() {
                    const hint = document.getElementById('debugHint');
                    hint.classList.add('show');
                }, 3000);
            }
        });
    </script>
</body>
</html>
