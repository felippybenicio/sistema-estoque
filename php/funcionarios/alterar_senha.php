<?php
header("Content-Type: application/json");
include "../conexao.php";

$id = intval($_POST["id"] ?? 0);
$antiga = trim($_POST["antiga"] ?? "");
$nova = trim($_POST["nova"] ?? ""); 
$conf = trim($_POST["conf"] ?? "");

if ($id <= 0) { echo json_encode(["erro" => "ID inválido"]); exit; }
if ($nova !== $conf) { echo json_encode(["erro" => "A nova senha não confere"]); exit; }

// Verifica senha correta
$stmt = $conn->prepare("SELECT senha FROM autenticacao WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($senhaBanco);
$stmt->fetch();
$stmt->close();

// Normaliza valores
$senhaBanco = trim((string)$senhaBanco);
$antiga = trim((string)$antiga);

if (!password_verify($antiga, $senhaBanco)) {
    echo json_encode(["erro" => "Senha atual incorreta"]);
    exit;
}


// Atualiza senha
$novaHash = password_hash($nova, PASSWORD_DEFAULT);

$stmt2 = $conn->prepare("UPDATE autenticacao SET senha = ? WHERE id = ?");
$stmt2->bind_param("si", $novaHash, $id);


if ($stmt2->execute()) {
    echo json_encode(["sucesso" => true]);
} else {
    echo json_encode(["erro" => "Erro ao alterar senha"]);
}

$stmt2->close();
$conn->close();
?>
