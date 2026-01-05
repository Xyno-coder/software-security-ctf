<?php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>Recipe Chamber — Crazy Einstein</title>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <style>
    :root { color-scheme: dark; }
    body {
      margin: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: clamp(2rem, 6vw, 4rem) clamp(1.5rem, 4vw, 3rem);
      font-family: "Segoe UI", "Trebuchet MS", sans-serif;
      background: radial-gradient(circle at 20% -10%, rgba(147,197,253,0.2) 0%, transparent 55%),
                  radial-gradient(circle at 90% 0%, rgba(236,72,153,0.2) 0%, transparent 60%),
                  linear-gradient(120deg, #050b18 0%, #04060d 35%, #0b162e 100%);
      color: #e6f1ff;
    }
    h1 {
      margin: 0;
      font-size: clamp(2.2rem, 3.4vw, 3rem);
      text-align: center;
      text-shadow: 0 12px 28px rgba(0,0,0,0.55);
    }
    p.lead {
      max-width: 600px;
      text-align: center;
      color: #c6e3ff;
      line-height: 1.6;
      margin: 1rem auto 2rem;
    }
    form {
      width: min(680px, 100%);
      background: linear-gradient(140deg, rgba(10,25,52,0.95), rgba(6,14,36,0.95));
      border-radius: 22px;
      padding: clamp(1.6rem, 4vw, 2.4rem);
      box-shadow: 0 32px 50px rgba(3, 6, 18, 0.7);
      border: 1px solid rgba(143, 245, 255, 0.2);
    }
    .input-shell {
      position: relative;
      display: flex;
      align-items: center;
      padding: 0.35rem;
      border-radius: 18px;
      background: radial-gradient(circle at 20% 20%, rgba(34,211,238,0.16) 0%, transparent 60%),
                  rgba(5, 12, 32, 0.95);
      border: 1px solid rgba(143, 245, 255, 0.2);
      box-shadow: inset 0 0 18px rgba(8, 20, 40, 0.6);
    }
    label {
      display: block;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.28em;
      color: rgba(142, 247, 255, 0.75);
      margin-bottom: 0.75rem;
    }
    input[type="text"] {
      flex: 1 1 auto;
      padding: 0.85rem 1.05rem 0.85rem 1rem;
      border-radius: 14px;
      border: 1px solid transparent;
      background: rgba(4, 10, 24, 0.92);
      color: #f1fbff;
      font-size: 1.05rem;
      letter-spacing: 0.12em;
      transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }
    input[type="text"]::placeholder {
      color: rgba(142, 247, 255, 0.45);
      letter-spacing: 0.18em;
      text-transform: uppercase;
    }
    input[type="text"]:focus {
      outline: none;
      border-color: rgba(94, 234, 212, 0.7);
      box-shadow: 0 0 0 2px rgba(94, 234, 212, 0.3);
      transform: translateY(-1px);
    }
    .input-glyph {
      flex-shrink: 0;
      margin-right: 0.6rem;
      width: 2.1rem;
      height: 2.1rem;
      border-radius: 50%;
      background: linear-gradient(135deg, rgba(62, 203, 247, 0.8), rgba(168, 85, 247, 0.8));
      display: flex;
      align-items: center;
      justify-content: center;
      color: #01060f;
      font-size: 1rem;
      font-weight: 700;
      letter-spacing: 0.12em;
      box-shadow: 0 10px 20px rgba(62, 203, 247, 0.35);
    }
    button {
      margin-top: 1.2rem;
      width: 100%;
      padding: 0.85rem 1.1rem;
      border: none;
      border-radius: 999px;
      font-size: 0.95rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.3em;
      background: linear-gradient(135deg, #3ecbf7, #a855f7);
      color: #081020;
      cursor: pointer;
      box-shadow: 0 24px 40px rgba(62, 203, 247, 0.35);
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    button:hover {
      transform: translateY(-4px);
      box-shadow: 0 28px 42px rgba(168, 85, 247, 0.35);
    }
    .tip {
      margin-top: clamp(2rem, 6vw, 3rem);
      max-width: 620px;
      text-align: center;
      padding: clamp(1.2rem, 4vw, 2rem);
      border-radius: 22px;
      background: rgba(13, 27, 55, 0.62);
      border: 1px solid rgba(142, 247, 255, 0.18);
      color: #d6ecff;
      line-height: 1.65;
    }
    a { color: #8ef7ff; }
  </style>
</head>
<body>
  <h1>Secret Recipe Chamber</h1>
  <p class="lead">Crazy Einstein keeps his culinary formulas under lock and key. Yet, some IDs unlock unexpected doors… even to data fields he never intended to be public.</p>

  <form method="POST" action="order.php">
    <label for="dish">Experimental Identifier</label>
    <div class="input-shell">
      <span class="input-glyph">ID</span>
      <input id="dish" name="id" placeholder="e.g., 1" autocomplete="off" />
    </div>
    <button type="submit">Trigger the Experiment</button>
  </form>

  <p class="tip">
    IDs are just the beginning. Log analysis has shown that speaking directly to the SQL engine can reveal entire recipes, hidden sessions… or even the professor’s famous flag.
  </p>
</body>
</html>
