<?php
header("Content-Type: application/json; charset=utf-8");
include "../conexao.php";
session_start();
if (!isset($_SESSION['id'])) {
    echo json_encode(["erro" => "Usuário não autenticado"]);
    exit;
}




$busca = trim($_POST["termo"] ?? "");

if ($busca === "") {
    echo json_encode(["erro" => "Nada informado"]);
    exit;
}

$stmt = $conn->prepare("
    SELECT id, nome, codigo, qtd_total_unidades
    FROM produtos
    WHERE nome LIKE ? OR codigo LIKE ?
    LIMIT 1
");

$like = "%$busca%";
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["erro" => "Produto não encontrado"]);
    exit;
}

$produto = $result->fetch_assoc();

// quantidade total do estoque
$totalUnidades = $produto["qtd_total_unidades"];

echo json_encode([
    "sucesso" => true,
    "nome" => $produto["nome"],
    "codigo" => $produto["codigo"],
    "quantidade_total" => $totalUnidades,
    "id" => $produto["id"]
]);
