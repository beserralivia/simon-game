<?php
include 'config.php';

// Busca todos os recordes dos usuários
$query = "SELECT player_name, score, created_at FROM rankings ORDER BY score DESC";
$stmt = $conn->prepare($query);
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
    <title>Ranking Simon Game</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #1c1c1c; /* Cor de fundo mais escura */
            color: #fff;
            font-family: 'Arial', sans-serif;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 36px; /* Aumenta o tamanho do título */
        }

        table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 80%;
        }

        th, td {
            border: 1px solid #fff;
            padding: 10px;
            text-align: center;
        }

        #back-button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 18px;
            background-color: #444;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5); /* Sombra no botão */
        }

        #back-button:hover {
            background-color: #666;
        }
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
    </style>
</head>
<body>
<div class="menu" id="menu">
    <div></div>
    <div></div>
    <div></div>
</div>

<div class="menu-content" id="menu-content">
    <a href="simon_game.php">Voltar ao jogo</a>
    <a href="manual.php">Manual de Instruções</a>
    <a href="logout.php">Sair</a>
</div>
<h1>Ranking Geral</h1>

<table>
    <thead>
        <tr>
            <th>Nome do Usuário</th>
            <th>Pontuação</th>
            <th>Data e Hora</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($scores as $score): ?>
            <tr>
                <td><?php echo htmlspecialchars($score['player_name']); ?></td>
                <td><?php echo htmlspecialchars($score['score']); ?></td>
                <td><?php echo htmlspecialchars($score['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- <button id="back-button" onclick="window.location.href='simon_game.php'">Voltar ao Jogo</button> -->
<script>
    document.getElementById('menu').addEventListener('click', function () {
    const menuContent = document.getElementById('menu-content');
    menuContent.style.display = menuContent.style.display === 'block' ? 'none' : 'block';
});
</script>
</body>
</html>
