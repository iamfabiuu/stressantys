<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['pedidos'])) {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "stressantysbd";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        foreach ($_POST['pedidos'] as $pedidoId) {
            // Deletar primeiro da tabela Stpedcar
            $stmt1 = $pdo->prepare("DELETE FROM Stpedcar WHERE id_ped = :id");
            $stmt1->execute([':id' => $pedidoId]);

            // Depois deletar da tabela Stped
            $stmt2 = $pdo->prepare("DELETE FROM Stped WHERE numped = :id");
            $stmt2->execute([':id' => $pedidoId]);
        }

        header("Location: /stressantys/php/meusPedidos.php?status=cancelado");
        exit;

    } catch (PDOException $e) {
        echo "Erro ao cancelar pedido: " . $e->getMessage();
    }
} else {
    header("Location: /stressantys/php/meusPedidos.php");
    exit;
}
