<?php
    header("Content-Type: application/json");
    include "../conexao.php";

    $id = intval($_GET["id"] ?? 0);

    $sql = $conn->prepare("SELECT nome, user FROM autenticacao WHERE id = ?");
    $sql->bind_param("i", $id);
    $sql->execute();
    $result = $sql->get_result();
    $dados = $result->fetch_assoc();

    echo json_encode($dados);
?>
