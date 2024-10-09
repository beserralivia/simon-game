<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Consulta SQL para buscar o usuário pelo e-mail
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar se o usuário foi encontrado
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verificar se a senha fornecida corresponde à senha armazenada
            if ($password === $user['password']) {
                // Login bem-sucedido
                $_SESSION['user_id'] = $user['id'];
                header('Location: simon_game.php');
                exit(); // Para garantir que o script pare após o redirecionamento
            } else {
                // Senha inválida
                echo "<p style='color: red;'>Senha incorreta!</p>";
            }
        } else {
            // Nenhum usuário encontrado com o e-mail fornecido
            echo "<p style='color: red;'>Usuário não encontrado!</p>";
        }

        $stmt->close(); // Fecha a declaração
    } else {
        echo "<p style='color: red;'>Erro na consulta ao banco de dados.</p>";
    }

    $conn->close(); // Fecha a conexão com o banco de dados
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

<h1>Login</h1>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Senha" required>
    <button type="submit">Login</button>
</form>

<div class="link">
    Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a>
</div>

</body>
</html>
