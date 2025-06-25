<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Pedido inválido.";
    exit;
}

$id_pedido = $_GET['id'];

$host = "localhost";
$user = "root";
$pass = "";
$db   = "stressantysbd";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Busca informações do pedido
    $stmt = $pdo->prepare("SELECT * FROM Stped WHERE numped = :id AND id_usr = :id_usr");
    $stmt->execute([
        ':id' => $id_pedido,
        ':id_usr' => $_SESSION['usuario']['id']
    ]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        echo "Pedido não encontrado ou não pertence a você.";
        exit;
    }

    // Busca os carros relacionados ao pedido
    $stmt = $pdo->prepare("
        SELECT c.* FROM Stpedcar pc
        JOIN Stcar c ON pc.id_car = c.id
        WHERE pc.id_ped = :id_ped
    ");
    $stmt->execute([':id_ped' => $id_pedido]);
    $carros = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro ao buscar dados do pedido: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Confirmação de Pedido</title>
</head>
<body>
    <h2>Pedido Confirmado</h2>
    <p><strong>Número do Pedido:</strong> <?= str_pad($pedido['numped'], 4, '0', STR_PAD_LEFT) ?></p>
    <p><strong>Valor Total:</strong> R$ <?= number_format($pedido['valortotal'], 2, ',', '.') ?></p>
    <p><strong>Desconto:</strong> R$ <?= number_format($pedido['desconto'], 2, ',', '.') ?></p>
    <p><strong>Tipo de Pagamento:</strong> <?= htmlspecialchars($pedido['tipo_pag']) ?></p>

    <h3>Carros no Pedido:</h3>
    <table border="1" cellpadding="5">
        <tr>
            <th>Modelo</th>
            <th>Marca</th>
            <th>Placa</th>
            <th>Preço</th>
            <th>Ano</th>
        </tr>
        <?php foreach ($carros as $carro): ?>
        <tr>
            <td><?= htmlspecialchars($carro['modelo']) ?></td>
            <td><?= htmlspecialchars($carro['marca']) ?></td>
            <td><?= htmlspecialchars($carro['placa']) ?></td>
            <td>R$ <?= number_format($carro['preco'], 2, ',', '.') ?></td>
            <td><?= $carro['ano'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="/stressantys/index.php">Voltar para Página Inicial</a>
</body>
</html>
