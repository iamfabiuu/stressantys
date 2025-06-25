<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "stressantysbd";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id_usr = $_SESSION['usuario']['id'];

    // Buscar todos os pedidos do usuário
    $stmt = $pdo->prepare("SELECT * FROM Stped WHERE id_usr = :id_usr ORDER BY numped DESC");
    $stmt->execute([':id_usr' => $id_usr]);
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro ao carregar pedidos: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Pedidos</title>
</head>
<body>
    <h2>Meus Pedidos</h2>

    <?php if (empty($pedidos)): ?>
        <p>Você ainda não fez nenhum pedido.</p>
    <?php else: ?>
        <?php foreach ($pedidos as $pedido): ?>
            <div style="border:1px solid #ccc; margin-bottom: 15px; padding:10px;">
                <h3>Pedido #<?= str_pad($pedido['numped'], 4, '0', STR_PAD_LEFT) ?></h3>
                <p><strong>Valor Total:</strong> R$ <?= number_format($pedido['valortotal'], 2, ',', '.') ?></p>
                <p><strong>Desconto:</strong> R$ <?= number_format($pedido['desconto'], 2, ',', '.') ?></p>
                <p><strong>Forma de Pagamento:</strong> <?= htmlspecialchars($pedido['tipo_pag']) ?></p>

                <h4>Carros neste pedido:</h4>
                <ul>
                <?php
                    $stmt = $pdo->prepare("
                        SELECT c.modelo, c.marca, c.placa, c.preco, c.ano
                        FROM Stpedcar pc
                        JOIN Stcar c ON pc.id_car = c.id
                        WHERE pc.id_ped = :id_ped
                    ");
                    $stmt->execute([':id_ped' => $pedido['numped']]);
                    $carros = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($carros as $carro):
                ?>
                    <li>
                        <?= htmlspecialchars($carro['marca']) ?> <?= htmlspecialchars($carro['modelo']) ?> |
                        Ano: <?= $carro['ano'] ?> |
                        Placa: <?= $carro['placa'] ?> |
                        R$ <?= number_format($carro['preco'], 2, ',', '.') ?>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="/stressantys/index.php">Voltar à página inicial</a>
</body>
</html>
