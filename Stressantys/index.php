<?php
session_start();
$usuario = $_SESSION['usuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Página Inicial</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h1>Bem-vindo a Stressantys</h1>

  <?php if ($usuario): ?>
    <p>Olá, <strong><?= htmlspecialchars($usuario['nome']) ?></strong>! Você está logado.</p>
    <p>Deseja fazer um pedido? <a href="/stressantys/php/montarPedido.php">Pedido</a></p>
    <p>Deseja verificar o seu pedido? <a href="/stressantys/php/meusPedidos.php">Meus Pedidos</a></p>
    <a href="/stressantys/php/logout.php" class="btn btn-danger">Sair</a>
  <?php else: ?>
    <p>Você não está logado. <a href="/stressantys/html/login.html"> Faça Login</a> ou <a href="/stressantys/html/cadastro.html">Cadastrar-se</a></p>
  <?php endif; ?>
</body>
</html>
