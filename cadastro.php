<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Consulta SQL para verificar se o e-mail já está cadastrado
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<p style='color: red;'>Este e-mail já está cadastrado!</p>";
    } else {
        // Inserir novo usuário no banco de dados
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $username, $email, $password);
        if ($stmt->execute()) {
            echo "<p style='color: green;'>Cadastro realizado com sucesso!</p>";
            header('Location: login.php'); // Redireciona para a página de login
            exit();
        } else {
            echo "<p style='color: red;'>Erro ao cadastrar usuário!</p>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #222;
            color: white;
            font-family: Arial, sans-serif;
        }

        h1 {
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            width: 300px; /* Largura do formulário */
        }

        input {
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
            background-color: #444;
            color: white;
            font-size: 16px;
        }

        input:focus {
            outline: none;
            background-color: #666;
        }

        button {
            padding: 10px;
            background-color: #444;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #666;
        }

        .link {
            margin-top: 15px;
            color: white;
        }

        .link a {
            color: #66f; /* Cor do link */
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline; /* Sublinhado ao passar o mouse */
        }
    </style>
</head>
<body>

<h1>Cadastro</h1>

<form method="POST">
    <input type="text" name="username" placeholder="Nome de Usuário" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Senha" required>
    <button type="submit">Cadastrar</button>
</form>

<div class="link">
    Já tem uma conta? <a href="login.php">Faça login aqui</a>
</div>

</body>
</html>
