<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

include "../conexao.php";

// if ($nivel === "leitor") {
//     http_response_code(403);
//     die("Permissão negada.");
// }

$id = $_POST['id'] ?? 0;
$id = intval($id);

if ($id === 0) {
    echo json_encode(["erro" => "ID não enviado"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
if (!$stmt) {
    echo json_encode(["erro" => "Erro no prepare: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $id);

if($stmt->execute()){
    echo json_encode(["sucesso" => true, "msg" => "Produto excluído com sucesso"]);
}else{
    echo json_encode(["erro" => "Erro ao excluir produto"]);
}

$stmt->close();
exit;
?>
