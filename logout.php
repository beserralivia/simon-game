<?php
session_start();

// Destruir todas as sessões
session_unset();
session_destroy();

// Redireciona o usuário para a página de login após o logout
header('Location: login.php');
exit;
?>
