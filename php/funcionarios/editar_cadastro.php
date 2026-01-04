<?php
header("Content-Type: application/json");
include "../conexao.php";

$id = intval($_POST["id"] ?? 0);
$nome = trim($_POST["nome"] ?? "");
$user = trim($_POST["user"] ?? "");
$nivel = trim($_POST["nivel"] ?? "");

if ($id <= 0) { echo json_encode(["erro" => "ID inválido"]); exit; }
if ($nome === "" || $user === "" || $nivel === "") {
    echo json_encode(["erro" => "Preencha todos os campos"]);
    exit;
}

$stmt = $conn->prepare("UPDATE autenticacao SET nome = ?, user = ?, nivel = ? WHERE id = ?");
$stmt->bind_param("sssi", $nome, $user, $nivel, $id);

if ($stmt->execute()) {
    echo json_encode(["sucesso" => true]);
} else {
    echo json_encode(["erro" => "Erro ao atualizar"]);
}

$stmt->close();
$conn->close();
