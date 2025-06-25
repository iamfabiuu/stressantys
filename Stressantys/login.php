<?php
session_start();
$mensagem = $_SESSION['mensagem'] ?? null;
unset($_SESSION['mensagem']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Login - Stressantys Motors</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      background-color: #f8f9fa;
    }
    .container {
      max-width: 400px;
      margin: 6rem auto;
      padding: 2rem;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
    .btn-custom {
      background-color: #000;
      color: white;
      border-radius: 0;
      transition: background-color 0.3s ease;
    }
    .btn-custom:hover {
      background-color: #333;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2 class="mb-4 fw-bold text-center">Login</h2>

    <?php if ($mensagem): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>

    <form action="../php/login.php" method="post" novalidate>
      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input
          type="email"
          class="form-control"
          id="email"
          name="email"
          required
          placeholder="email@exemplo.com"
        />
        <div class="invalid-feedback">Informe um e-mail válido.</div>
      </div>

      <div class="mb-4">
        <label for="senha" class="form-label">Senha</label>
        <input
          type="password"
          class="form-control"
          id="senha"
          name="senha"
          required
          minlength="6"
          placeholder="Sua senha"
        />
        <div class="invalid-feedback">Senha com no mínimo 6 caracteres.</div>
      </div>

      <button type="submit" class="btn btn-custom w-100">Entrar</button>
    </form>

    <p class="text-center mt-3">
      Não tem conta? <a href="cadastro.php">Cadastre-se</a>
    </p>
  </div>

  <!-- Bootstrap JS e validação customizada -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (() => {
      'use strict';
      const form = document.querySelector('form');
      form.addEventListener('submit', e => {
        if (!form.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    })();
  </script>
</body>
</html>
