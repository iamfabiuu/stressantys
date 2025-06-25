<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "stressantysbd";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $nome     = $_POST["nome"];
        $telefone = $_POST["tel"];
        $email    = $_POST["email"];
        $senha    = $_POST["senha"];
        $cpf      = $_POST["cpf"];
        $end      = $_POST["end"];

        $senhaCripto = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO Stusr (nome, email, senha, cpf, end, tel)
                VALUES (:nome, :email, :senha, :cpf, :end, :tel)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":senha", $senhaCripto, PDO::PARAM_STR);
        $stmt->bindParam(":cpf", $cpf, PDO::PARAM_STR); // CPF é melhor como string
        $stmt->bindParam(":end", $end, PDO::PARAM_STR);
        $stmt->bindParam(":tel", $telefone, PDO::PARAM_STR); // telefone também como string

        $stmt->execute();

 		header("Location: /stressantys/index.php");
        exit;

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }

} else {
    echo "Você não tem permissão para acessar o site!";
}
?>
