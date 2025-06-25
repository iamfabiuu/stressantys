<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "stressantysbd";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['carros'])) {
        $ids_carros = $_POST['carros'];
        $id_usr = $_SESSION['usuario']['id'];

        // Buscar estoque e preço dos carros selecionados
        $placeholders = implode(',', array_fill(0, count($ids_carros), '?'));
        $stmt = $pdo->prepare("SELECT id, preco, estoque FROM Stcar WHERE id IN ($placeholders)");
        $stmt->execute($ids_carros);
        $carros_selecionados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Verifica se todos têm estoque
        foreach ($carros_selecionados as $carro) {
            if ($carro['estoque'] <= 0) {
                echo "O carro com ID {$carro['id']} está fora de estoque.";
                exit;
            }
        }

        $valor_total = array_sum(array_column($carros_selecionados, 'preco'));

        // Cria o pedido
        $stmt = $pdo->prepare("INSERT INTO Stped (id_usr, cupon, valortotal, desconto, tipo_pag)
                               VALUES (:id_usr, '', :valortotal, 0, 'pendente')");
        $stmt->execute([
            ':id_usr' => $id_usr,
            ':valortotal' => $valor_total
        ]);

        $id_pedido = $pdo->lastInsertId();

        // Associa os carros ao pedido e atualiza o estoque
        $stmt_insert = $pdo->prepare("INSERT INTO Stpedcar (id_ped, id_car) VALUES (:id_ped, :id_car)");
        $stmt_update = $pdo->prepare("UPDATE Stcar SET estoque = estoque - 1 WHERE id = :id_car");

        foreach ($carros_selecionados as $carro) {
            $stmt_insert->execute([
                ':id_ped' => $id_pedido,
                ':id_car' => $carro['id']
            ]);
            $stmt_update->execute([':id_car' => $carro['id']]);
        }

        // Redirecionar para confirmação
        header("Location: /stressantys/php/confirmaPedido.php?id=" . $id_pedido);
        exit;

    } else {
        echo "Nenhum carro selecionado.";
    }

} catch (PDOException $e) {
    echo "Erro ao processar o pedido: " . $e->getMessage();
}
