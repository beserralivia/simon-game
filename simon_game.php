<?php
include 'config.php';
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Busca o nome do usuário no banco de dados
$user_id = $_SESSION['user_id'];
$query = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$username = $user['username'] ?? 'Usuário';

// Lógica para salvar a pontuação
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['score'])) {
    $score = $_POST['score'];

    // Salvar pontuação na tabela rankings
    $query = "INSERT INTO rankings (player_name, score, created_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $username, $score);
    $stmt->execute();
}

// Busca os recordes mais altos do usuário
$query = "SELECT player_name, score, created_at FROM rankings WHERE player_name = ? ORDER BY score DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

$scores = [];
while ($row = $result->fetch_assoc()) {
    $scores[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simon Game</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #1c1c1c;
            color: #fff;
            font-family: 'Arial', sans-serif;
            position: relative;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 36px;
        }

        #game-area {
            display: grid;
            grid-template-columns: repeat(2, 150px);
            grid-template-rows: repeat(2, 150px);
            gap: 15px;
            margin-bottom: 20px;
            display: none; /* Oculta a área do jogo inicialmente */
        }

        .color-button {
            width: 150px;
            height: 150px;
            border-radius: 15px;
            opacity: 0.8;
            transition: opacity 0.3s ease, transform 0.3s ease;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.6);
        }

        .color-button.active {
            opacity: 1;
            transform: scale(1.1);
        }

        #red { background-color: #ff4d4d; }
        #green { background-color: #4dff4d; }
        #blue { background-color: #4d4dff; }
        #yellow { background-color: #ffff4d; }

        .score-display {
            font-size: 24px;
            margin-top: 20px;
        }

        #start-button {
            padding: 10px 20px;
            font-size: 18px;
            background-color: #444;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        #start-button:hover {
            background-color: #666;
        }

        /* Estilo do Menu Hamburguer */
        .menu {
            position: absolute;
            top: 20px;
            right: 20px;
            cursor: pointer;
        }

        .menu div {
            width: 30px;
            height: 4px;
            background-color: white;
            margin: 5px;
            transition: all 0.3s ease;
        }

        .menu-content {
            display: none;
            position: absolute;
            top: 50px;
            right: 20px;
            background-color: #333;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .menu-content a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 8px 12px;
        }

        .menu-content a:hover {
            background-color: #444;
        }
        table {
            width: 80%; /* Largura da tabela */
            margin: 20px 0; /* Margem superior e inferior */
            border-collapse: collapse; /* Colapsa as bordas */
        }

        th, td {
            padding: 10px; /* Espaçamento interno das células */
            text-align: left; /* Alinhamento do texto */
            border-bottom: 1px solid #444; /* Borda inferior das células */
        }

        th {
            background-color: #444; /* Cor de fundo do cabeçalho */
            color: white; /* Cor do texto do cabeçalho */
        }

        tr:hover {
            background-color: #555; /* Cor de fundo ao passar o mouse */
        }

        tbody tr:nth-child(even) {
            background-color: #2c2c2c; /* Cor de fundo das linhas pares */
        }
    </style>
</head>
<body>

<div class="menu" id="menu">
    <div></div>
    <div></div>
    <div></div>
</div>

<div class="menu-content" id="menu-content">
    <a href="ranking.php">Ver Ranking Geral</a>
    <a href="manual.php">Manual de Instruções</a>
    <a href="logout.php">Sair</a>
</div>

<h1>Bem-vindo <?php echo htmlspecialchars($username); ?></h1>
<button id="start-button">Iniciar Jogo</button>

<div id="game-area">
    <div id="red" class="color-button"></div>
    <div id="green" class="color-button"></div>
    <div id="blue" class="color-button"></div>
    <div id="yellow" class="color-button"></div>
</div>

<div class="score-display">Score: <span id="score">0</span></div>

<table>
    <thead>
        <tr>
            <th>Nome do Usuário</th>
            <th>Pontuação</th>
            <th>Data e Hora</th>
        </tr>
    </thead>
    <tbody id="scoreboard">
        <?php foreach ($scores as $score): ?>
            <tr>
                <td><?php echo htmlspecialchars($score['player_name']); ?></td>
                <td><?php echo htmlspecialchars($score['score']); ?></td>
                <td><?php echo htmlspecialchars($score['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
let sequence = [];
let playerSequence = [];
let level = 0;

// Função para iniciar o jogo
document.getElementById('start-button').addEventListener('click', startGame);

function startGame() {
    level = 0;
    sequence = [];
    playerSequence = [];
    document.getElementById("score").innerText = level; // Reseta o score no HTML
    document.getElementById("game-area").style.display = "grid"; // Mostra a área do jogo
    document.getElementById('start-button').style.display = 'none'; // Oculta o botão "Iniciar"
    nextSequence();
}

function nextSequence() {
    const colors = ["red", "green", "blue", "yellow"];
    const randomColor = colors[Math.floor(Math.random() * 4)];
    sequence.push(randomColor);
    level++;
    document.getElementById("score").innerText = level; // Atualiza o score no HTML
    playSequence();
}

function playSequence() {
    sequence.forEach((color, index) => {
        setTimeout(() => {
            const button = document.getElementById(color);
            button.classList.add('active');
            setTimeout(() => button.classList.remove('active'), 500);
        }, (index + 1) * 1000);
    });
}

document.querySelectorAll('.color-button').forEach(button => {
    button.addEventListener('click', event => {
        const color = event.target.id;
        playerSequence.push(color);

        // Checar se a sequência do jogador está correta
        if (playerSequence.length === sequence.length) {
            if (JSON.stringify(playerSequence) === JSON.stringify(sequence)) {
                playerSequence = [];
                nextSequence();
            } else {
                alert("Você perdeu! Seu score foi: " + level);
                saveScore(level);
                resetGame();
            }
        }
    });
});

function resetGame() {
    sequence = [];
    playerSequence = [];
    level = 0;
    document.getElementById("score").innerText = level; // Reseta o score no HTML
    document.getElementById("game-area").style.display = "none"; // Oculta a área do jogo
    document.getElementById('start-button').style.display = 'block'; // Mostra o botão "Iniciar" novamente
}

function saveScore(score) {
    fetch('simon_game.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `score=${score}`
    })
    .then(response => response.json())
    .then(data => updateScoreboard(data));
}

// Função para atualizar a tabela de recordes
function updateScoreboard(scores) {
    const scoreboard = document.getElementById('scoreboard');
    scoreboard.innerHTML = ''; // Limpa a tabela atual
    scores.forEach(score => {
        const row = document.createElement('tr');
        row.innerHTML = `<td>${score.player_name}</td><td>${score.score}</td><td>${score.created_at}</td>`;
        scoreboard.appendChild(row);
    });
}

// Funcionalidade do menu hamburguer
document.getElementById('menu').addEventListener('click', function () {
    const menuContent = document.getElementById('menu-content');
    menuContent.style.display = menuContent.style.display === 'block' ? 'none' : 'block';
});
</script>

</body>
</html>
