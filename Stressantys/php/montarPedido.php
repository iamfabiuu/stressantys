<?php
// montar_pedido.html (mas com .php no final para incluir a sessão)
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /stressantys/php/login.php");
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "stressantysbd";

$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Buscar carros disponíveis
$stmt = $pdo->query("SELECT * FROM Stcar WHERE status = 1 AND estoque > 0");
$carros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Montar Pedido</title>
</head>
<body>
    <h2>Olá, <?= $_SESSION['usuario']['nome'] ?>!</h2>
    <h3>Monte seu pedido</h3>

    <form action="processaPedido.php" method="POST">
        <table border="1" cellpadding="5">
            <tr>
                <th>Selecionar</th>
                <th>Modelo</th>
                <th>Marca</th>
                <th>Preço</th>
                <th>Placa</th>
                <th>Ano</th>
                <th>Estoque</th>
            </tr>
            <?php foreach ($carros as $carro): ?>
                <tr>
                    <td><input type="checkbox" name="carros[]" value="<?= $carro['id'] ?>"></td>
                    <td><?= htmlspecialchars($carro['modelo']) ?></td>
                    <td><?= htmlspecialchars($carro['marca']) ?></td>
                    <td>R$ <?= number_format($carro['preco'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($carro['placa']) ?></td>
                    <td><?= htmlspecialchars($carro['ano']) ?></td>
                    <td><?= $carro['estoque'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <br>
        <button type="submit">Finalizar Pedido</button>
    </form>
</body>
</html>
