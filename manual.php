<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instruções do Jogo</title>
    <style>
        body {
            display: flex;
            height: 100vh;
            background-color: #1c1c1c; /* Cor de fundo mais escura */
            color: #fff;
            font-family: 'Arial', sans-serif;
            position: relative;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        #manual-container {
            margin: 20px;
            padding: 20px;
            
            /* Sem cor de fundo destacada */
        }

        /* Estilos do menu hamburguer */
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
    <script>
        function toggleMenu() {
            const menuItems = document.getElementById('menu-items');
            menuItems.style.display = menuItems.style.display === 'block' ? 'none' : 'block';
        }
    </script>
</head>
<body>

<div class="menu" id="menu">
    <div></div>
    <div></div>
    <div></div>
</div>

<div class="menu-content" id="menu-content">
    <a href="simon_game.php">Voltar ao jogo</a>
    <a href="ranking.php">Ver Ranking Geral</a>
    <a href="logout.php">Sair</a>
</div>

<div id="manual-container">
<h1>Manual de Instruções</h1>

        <h2>Sobre o Jogo</h2>
        <p>O Simon é um jogo de memória no qual você deve repetir uma sequência de cores gerada aleatoriamente. Cada vez que você acerta, uma nova cor é adicionada à sequência, aumentando a dificuldade.</p>

        <h2>Como Jogar</h2>
        <p>1. Clique no botão "Iniciar Jogo" para começar.<br>
           2. Memorize a sequência de cores mostrada na tela.<br>
           3. Repita a sequência clicando nas cores na mesma ordem.<br>
           4. Se acertar, o jogo continuará com uma nova cor adicionada à sequência. Se errar, o jogo termina e sua pontuação é salva.</p>

        <h2>Pontuação</h2>
        <p>Você ganha um ponto para cada nível completado corretamente. O objetivo é alcançar a maior sequência de cores possível.</p>

        <h2>Dicas</h2>
        <p>1. Tente identificar pequenos padrões dentro da sequência para facilitar a memorização.<br>
           2. Fique focado e evite distrações enquanto memoriza a sequência.</p>

</div>
<script>
    document.getElementById('menu').addEventListener('click', function () {
    const menuContent = document.getElementById('menu-content');
    menuContent.style.display = menuContent.style.display === 'block' ? 'none' : 'block';
});
</script>
</body>
</html>
