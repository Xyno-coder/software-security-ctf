<?php

$dbFile = __DIR__ . '/data/db.sqlite';

if (!file_exists($dbFile)) {
    echo "<p>Database missing. Please ensure create_db.php has been executed.</p>";
    exit;
}

try {
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo "DB error: " . htmlspecialchars($e->getMessage());
    exit;
}


$param = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : null);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Experiment Console — Crazy Einstein</title>
    <style>
        :root { color-scheme: dark; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", "Trebuchet MS", sans-serif;
            background: radial-gradient(circle at 8% 10%, rgba(96,165,250,0.18) 0%, transparent 60%),
                                    radial-gradient(circle at 92% 12%, rgba(168,85,247,0.18) 0%, transparent 65%),
                                    linear-gradient(135deg, #050912 0%, #020409 40%, #09162d 100%);
            color: #e0efff;
            padding: clamp(2rem, 5vw, 3rem);
        }
        h1 {
            margin-top: 0;
            text-align: center;
            font-size: clamp(2.1rem, 3vw, 2.8rem);
            text-shadow: 0 12px 28px rgba(0,0,0,0.55);
        }
        .console {
            max-width: 720px;
            margin: 2.5rem auto;
            background: rgba(9, 22, 45, 0.82);
            border: 1px solid rgba(142, 247, 255, 0.22);
            border-radius: 24px;
            box-shadow: 0 28px 42px rgba(5, 11, 24, 0.6);
            padding: clamp(1.5rem, 4vw, 2.4rem);
        }
        .console p { color: #c8e4ff; line-height: 1.6; }
        pre {
            margin-top: 1.2rem;
            background: linear-gradient(135deg, rgba(4, 10, 24, 0.95), rgba(10, 24, 48, 0.95));
            border-radius: 16px;
            padding: 1.25rem;
            border: 1px solid rgba(94, 234, 212, 0.18);
            color: #a7ffef;
            overflow-x: auto;
            box-shadow: inset 0 0 18px rgba(9, 20, 38, 0.6);
        }
        a { color: #8ef7ff; }
        .back-link { text-align: center; margin-top: 2rem; font-size: 0.95rem; letter-spacing: 0.2em; text-transform: uppercase; }
    </style>
</head>
<body>
    <h1>Experiment Console</h1>
    <div class="console">
        <p>Results are printed directly from the professor's computers. If the output becomes verbose, pay attention: the smallest SQL error is a clue.</p>
  <?php
  if ($param === null || $param === '') {
      echo "<p>No ID provided. Return to the <a href=\"secret_recipe.php\">secret page</a>.</p>";
      exit;
  }

  $sql = "SELECT id, name, description FROM dishes WHERE id = '$param'";

  try {
      $stmt = $db->query($sql);
      if ($stmt === false) {
          $err = $db->errorInfo();
          echo "<p>SQL Error: " . htmlspecialchars($err[2]) . "</p>";
          echo "<pre>SQL executed: " . htmlspecialchars($sql) . "</pre>";
      } else {
          $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
          if (count($rows) === 0) {
              echo "<p>No results for id=" . htmlspecialchars($param) . "</p>";
              echo "<pre>SQL executed: " . htmlspecialchars($sql) . "</pre>";
          } else {
              foreach ($rows as $r) {
                  echo "<pre>" . htmlspecialchars(json_encode($r, JSON_PRETTY_PRINT)) . "</pre>";
              }
          }
      }
  } catch (Exception $e) {
      echo "<p>SQL Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
      echo "<pre>SQL executed: " . htmlspecialchars($sql) . "</pre>";
  }
    ?>
    </div>
    <div class="back-link"><a href="secret_recipe.php">Return to the laboratory</a></div>
</body>
</html>
