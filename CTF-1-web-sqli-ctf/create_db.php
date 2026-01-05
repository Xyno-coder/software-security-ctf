<?php
$dbDirectory = __DIR__ . '/data';
$dbPath = $dbDirectory . '/db.sqlite';

@mkdir($dbDirectory, 0755, true);

if (file_exists($dbPath)) {
    echo "DB already exists: $dbPath\n";
    exit(0);
}

try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   
    $db->exec("CREATE TABLE users (id INTEGER PRIMARY KEY, username TEXT, email TEXT, password TEXT);");
    $db->exec("CREATE TABLE dishes (id INTEGER PRIMARY KEY, name TEXT, description TEXT);");

    $db->exec("INSERT INTO dishes (name, description) VALUES
        ('Spaghetti Carbonara', 'Creamy, cheesy delight'),
        ('Truffle Risotto', 'Aromatic wild truffle risotto'),
        ('Margherita Pizza', 'Classic tomato & basil cheese');");

    $db->exec("INSERT INTO users (username, email, password) VALUES
        ('alice', 'alice@example.com', 'alice_pw'),
        ('bob', 'bob@example.com', 'bob_pw');");


    $flag = 'flag{The_chief_is_cooking_up_sqli_for_you!}';
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:u, :e, :p)");
    $stmt->execute([':u' => 'admin', ':e' => 'admin@restaurant.local', ':p' => $flag]);

    echo "DB created at $dbPath with flag for admin.\n";

} catch (PDOException $e) {
    echo "DB error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
