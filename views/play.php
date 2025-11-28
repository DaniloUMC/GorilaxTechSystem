<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Jogo Nave</title>
<style>
    body { margin: 0; overflow: hidden; background: #000; }
    canvas { display: block; }
</style>
</head>
<body>
<canvas id="game"></canvas>

<script>
const canvas = document.getElementById("game");
const ctx = canvas.getContext("2d");

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let mouseX = canvas.width / 2;
let mouseY = canvas.height / 2;
let gameEnded = false;

// ==========================
const player = {
    x: canvas.width / 2,
    y: canvas.height - 100,
    size: 25,
    speed: 0.15,
    bullets: [],
    lastShot: 0
};

// ==========================
const enemies = [];
setInterval(() => {
    if (!gameEnded) {
        enemies.push({
            x: Math.random() * canvas.width,
            y: -50,
            size: 25,
            speed: 1 + Math.random() * 1.5
        });
    }
}, 800);

// ==========================
// CONTROLE MOUSE & TOUCH
document.addEventListener("mousemove", e => {
    mouseX = e.clientX;
    mouseY = e.clientY;
});
document.addEventListener("touchmove", e => {
    const t = e.touches[0];
    mouseX = t.clientX;
    mouseY = t.clientY;
});

// ==========================
function shoot() {
    const now = Date.now();
    if (now - player.lastShot > 200) {
        player.bullets.push({
            x: player.x,
            y: player.y - player.size,
            size: 5,
            speed: 8
        });
        player.lastShot = now;
    }
}

// ==========================
// GAME OVER COM POPUP
function gameOver() {
    gameEnded = true;
    alert("S.I é melhor que Engenharia!");
    location.reload();
}

// ==========================
function update() {
    if (gameEnded) return;

    // movimento da nave
    player.x += (mouseX - player.x) * player.speed;
    player.y += (mouseY - player.y) * player.speed;

    shoot();

    // atualização dos tiros
    player.bullets.forEach((b, i) => {
        b.y -= b.speed;
        if (b.y < 0) player.bullets.splice(i, 1);
    });

    // atualização dos inimigos
    enemies.forEach((e, i) => {
        const dx = player.x - e.x;
        const dy = player.y - e.y;
        const dist = Math.hypot(dx, dy);

        e.x += (dx / dist) * e.speed;
        e.y += (dy / dist) * e.speed;

        // colisão tiro-inimigo
        player.bullets.forEach((b, j) => {
            if (Math.hypot(b.x - e.x, b.y - e.y) < e.size) {
                enemies.splice(i, 1);
                player.bullets.splice(j, 1);
            }
        });

        // colisão jogador-inimigo: morte imediata
        if (Math.hypot(player.x - e.x, player.y - e.y) < e.size + player.size) {
            gameOver();
        }
    });
}

// ==========================
function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Nave triangular
    ctx.fillStyle = "cyan";
    ctx.beginPath();
    ctx.moveTo(player.x, player.y - player.size);
    ctx.lineTo(player.x - player.size, player.y + player.size);
    ctx.lineTo(player.x + player.size, player.y + player.size);
    ctx.closePath();
    ctx.fill();

    // Tiros
    ctx.fillStyle = "yellow";
    player.bullets.forEach(b => {
        ctx.beginPath();
        ctx.arc(b.x, b.y, b.size, 0, Math.PI * 2);
        ctx.fill();
    });

    // Inimigos
    ctx.fillStyle = "red";
    enemies.forEach(e => {
        ctx.beginPath();
        ctx.arc(e.x, e.y, e.size, 0, Math.PI * 2);
        ctx.fill();
    });
}

// ==========================
function loop() {
    update();
    draw();
    if (!gameEnded) requestAnimationFrame(loop);
}

loop();
</script>
</body>
</html>
