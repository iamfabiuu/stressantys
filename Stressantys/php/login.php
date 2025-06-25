<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "stressantysbd";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Recebe os dados do formulário
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        // Busca o usuário pelo e-mail
        $stmt = $pdo->prepare("SELECT * FROM Stusr WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se achou o usuário e se a senha está correta
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = [
                'id'    => $usuario['id'],
                'nome'  => $usuario['nome'],
                'email' => $usuario['email']
            ];
            header("Location: /stressantys/index.php");
            exit;
        } else {
            echo "<p style='color:red;'>E-mail ou senha incorretos.</p>";
        }

    } catch (PDOException $e) {
        echo "Erro de conexão: " . $e->getMessage();
    }
}else {
    header("Location: /stressantys/index.php");
    exit;
}
?>
