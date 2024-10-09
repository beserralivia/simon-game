<?php
// config.php
$servername = "localhost"; // ou o endereço do seu servidor
$username = "root"; // seu nome de usuário do banco de dados
$password = ""; // sua senha do banco de dados
$dbname = "simon_game"; // nome do seu banco de dados

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
