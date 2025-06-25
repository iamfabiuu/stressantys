<?php
session_start();
$mensagem = $_SESSION['mensagem'] ?? null;
unset($_SESSION['mensagem']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Cadastro - Stressantys Motors</title>
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
      max-width: 480px;
      margin: 4rem auto;
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
    <h2 class="mb-4 fw-bold text-center">Cadastro de Cliente</h2>

    <?php if ($mensagem): ?>
      <div class="alert alert-info"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>

    <form action="../php/cadastrar.php" method="post" novalidate>
      <div class="mb-3">
        <label for="nome" class="form-label">Nome Completo</label>
        <input type="text" class="form-control" id="nome" name="nome" required placeholder="Seu nome completo" />
        <div class="invalid-feedback">Por favor, insira seu nome.</div>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" required placeholder="email@exemplo.com" />
        <div class="invalid-feedback">Informe um e-mail válido.</div>
      </div>

      <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="tel" class="form-control" id="telefone" name="tel" required placeholder="(XX) XXXXX-XXXX" pattern="\(\d{2}\) \d{4,5}-\d{4}" />
        <div class="invalid-feedback">Formato: (XX) XXXXX-XXXX</div>
      </div>

      <div class="mb-3">
        <label for="cpf" class="form-label">CPF</label>
        <input type="text" class="form-control" id="cpf" name="cpf" required placeholder="000.000.000-00" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" />
        <div class="invalid-feedback">Formato: 000.000.000-00</div>
      </div>

      <div class="mb-3">
        <label for="end" class="form-label">Endereço</label>
        <input type="text" class="form-control" id="end" name="end" required placeholder="Rua, número, bairro, cidade" />
        <div class="invalid-feedback">Informe seu endereço completo.</div>
      </div>

      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" class="form-control" id="senha" name="senha" required minlength="6" placeholder="Sua senha" />
        <div class="invalid-feedback">Senha com no mínimo 6 caracteres.</div>
      </div>

      <div class="mb-4">
        <label for="confirma_senha" class="form-label">Confirme a Senha</label>
        <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" required minlength="6" placeholder="Confirme sua senha" />
        <div class="invalid-feedback">As senhas devem coincidir.</div>
      </div>

      <button type="submit" class="btn btn-custom w-100">Cadastrar</button>
    </form>
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

        // Validação customizada para confirmar senha
        const senha = form.senha.value;
        const confirma = form.confirma_senha.value;

        if (senha !== confirma) {
          e.preventDefault();
          e.stopPropagation();
          form.confirma_senha.setCustomValidity("As senhas não coincidem.");
          form.confirma_senha.reportValidity();
        } else {
          form.confirma_senha.setCustomValidity("");
        }

        form.classList.add('was-validated');
      }, false);
    })();
  </script>
</body>
</html>
