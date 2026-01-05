<?php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>Crazy Einstein Cantina — Home</title>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <style>
    :root { color-scheme: dark; }
    body {
      font-family: "Segoe UI", "Trebuchet MS", sans-serif;
      margin: 0;
      padding: 3rem clamp(1.5rem, 5vw, 4rem);
      color: #e8f1ff;
      background: radial-gradient(circle at 10% 20%, #0a1b34 0%, #03060f 55%, #050914 100%);
      position: relative;
      overflow-x: hidden;
    }
    body::before,
    body::after {
      content: "";
      position: absolute;
      inset: -20vw auto auto -10vw;
      width: clamp(18rem, 35vw, 30rem);
      height: clamp(18rem, 35vw, 30rem);
      background: radial-gradient(circle, rgba(58,255,245,0.18) 0%, transparent 70%);
      filter: blur(10px);
      opacity: 0.75;
      pointer-events: none;
      z-index: 0;
    }
    body::after {
      inset: auto -15vw -10vh auto;
      background: radial-gradient(circle, rgba(235,137,255,0.22) 0%, transparent 68%);
    }
    .layout { position: relative; z-index: 1; max-width: 1100px; margin: 0 auto; }
    .hero { display: flex; flex-direction: column; gap: 1.5rem; align-items: center; text-align: center; }
    @media (min-width: 840px) {
      .hero { flex-direction: row; text-align: left; }
    }
    .avatar {
      flex-shrink: 0;
      width: clamp(200px, 32vw, 260px);
      filter: drop-shadow(0 24px 40px rgba(67, 193, 255, 0.4));
      border-radius: 24px;
      display: block;
    }
    .tag {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.35em;
      padding: 0.4rem 1rem;
      border-radius: 999rem;
      background: rgba(34, 211, 238, 0.12);
      border: 1px solid rgba(34, 211, 238, 0.35);
      color: #8ef7ff;
    }
    h1 {
      font-size: clamp(2.4rem, 4.2vw, 3.6rem);
      margin: 0;
      line-height: 1.15;
      text-shadow: 0 8px 24px rgba(0, 0, 0, 0.45);
    }
    .intro { font-size: 1.05rem; color: #cfe4ff; max-width: 540px; }
    .menu {
      margin-top: clamp(2.5rem, 5vw, 3.5rem);
      display: grid;
      gap: clamp(1.4rem, 3vw, 2.2rem);
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    }
    .card {
      position: relative;
      border-radius: 22px;
      padding: 1.75rem;
      background: linear-gradient(145deg, rgba(12,28,56,0.9), rgba(6,12,32,0.85));
      border: 1px solid rgba(130, 196, 255, 0.18);
      box-shadow: 0 30px 45px rgba(9, 15, 35, 0.45);
      overflow: hidden;
    }
    .card::before {
      content: "";
      position: absolute;
      inset: -40% 40% 60% -40%;
      background: radial-gradient(circle, rgba(255,255,255,0.18) 0%, transparent 70%);
      opacity: 0.35;
      transform: rotate(12deg);
      pointer-events: none;
    }
    .card h3 { margin: 0 0 0.75rem; font-size: 1.4rem; color: #9bd8ff; }
    .card p { margin: 0; line-height: 1.55; color: #d8ecff; font-size: 0.98rem; }
    .card span { display: inline-block; margin-top: 1rem; font-size: 0.75rem; letter-spacing: 0.3em; text-transform: uppercase; color: rgba(142, 247, 255, 0.65); }
    .hint {
      margin-top: clamp(2.5rem, 6vw, 4rem);
      text-align: center;
      background: linear-gradient(135deg, rgba(15,50,90,0.65), rgba(8,18,42,0.85));
      border: 1px solid rgba(142, 247, 255, 0.2);
      border-radius: 24px;
      padding: clamp(1.8rem, 4vw, 2.8rem);
      box-shadow: 0 24px 45px rgba(5, 10, 25, 0.55);
    }
    .hint h2 { margin: 0 0 0.8rem; font-size: 1.8rem; color: #a5dfff; }
    .hint p { margin: 0.35rem 0; color: #ddefff; line-height: 1.6; }
    footer {
      margin-top: clamp(2.5rem, 6vw, 4rem);
      text-align: center;
      color: rgba(205, 231, 255, 0.7);
      font-size: 0.85rem;
      letter-spacing: 0.12em;
    }
    a { color: #8ef7ff; }
  </style>
</head>
<body>
  <div class="layout">
    <header class="hero">
      <img class="avatar" src="./Einstein.jpg" alt="Albert Einstein" />
      <div>
        <span class="tag">Crazy Einstein</span>

        <h1>« Quantum Cantina » — the professor's edible experiments</h1>
        <p class="intro">
          In his secret laboratory, Professor Crazy Einstein blends quantum physics with molecular gastronomy. Each dish is an equation, each recipe a clue.
        </p>
        <p class="intro">
          Rumor has it that some culinary formulas lead to SQL injection points. It's up to you to follow the trail and hack the mad scientist's menu.
        </p>
      </div>
    </header>

    <section class="menu">
      <article class="card">
        <span>Preview 01</span>
        <h3>Relativity Lasagna</h3>
        <p>Layers of pasta twisted by an edible gravitational field. Einstein swears the taste varies with your eating speed.</p>
      </article>
      <article class="card">
        <span>Preview 02</span>
        <h3>Particle Soufflé</h3>
        <p>A light, unstable foam: watch it and it collapses, ignore it and it expands infinitely. The ingredients seem to hide more than one superposition.</p>
      </article>
      <article class="card">
        <span>Preview 03</span>
        <h3>Entangled Cheesecake</h3>
        <p>Two connected slices: touch one in Paris, the other reacts in Zurich. Perfect for injections faster than light.</p>
      </article>
    </section>

    <section class="hint">
      <h2>Secret Laboratory</h2>
      <p><b>The official cantina doors are secure. But Crazy Einstein leaves experimental conduits around that curious minds can map.</b></p>
    </section>

    <footer>
      <p>© Crazy Einstein Labs — Get ready to decode the cuisine of the future.</p>
    </footer>
  </div>
</body>
</html>
