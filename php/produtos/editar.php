<?php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');

include "../conexao.php";

$id = $_POST['id'] ?? 0;
$id = intval($id);


// Recebe os dados editados
$nome = $_POST['nome'] ?? '';
$codigo = $_POST['codigo'] ?? '';
$categoria = $_POST['categoria'] ?? '';
$qtd_por_lote = intval($_POST['qtd_por_lote'] ?? 0);
$qtd_de_lote = intval($_POST['qtd_de_lote'] ?? 0);
$preco_pago_lote = floatval($_POST['preco_pago_lote'] ?? 0);
$valor_revenda_unidade = floatval($_POST['valor_revenda_unidade'] ?? 0);
$descricao = $_POST['descricao'] ?? '';

// Cálculos automáticos
$qtd_total_unidades = $qtd_por_lote * $qtd_de_lote;
$valor_pago_por_unidade = $preco_pago_lote / ($qtd_por_lote ?: 1);
$lucro_por_unidade = $valor_revenda_unidade - $valor_pago_por_unidade;
$lucro_por_lote = $lucro_por_unidade * $qtd_por_lote;

$stmt = $conn->prepare("
    UPDATE produtos SET
        nome = ?,
        categoria = ?,
        qtd_por_lote = ?,
        qtd_de_lote = ?,
        preco_pago_lote = ?,
        valor_revenda_unidade = ?,
        descricao = ?,
        qtd_total_unidades = ?,
        lucro_por_lote = ?,
        lucro_por_unidade = ?
    WHERE id = ? 
");

$stmt->bind_param(
    "ssiiddsiddi",
    $nome,
    $categoria,
    $qtd_por_lote,
    $qtd_de_lote,
    $preco_pago_lote,
    $valor_revenda_unidade,
    $descricao,
    $qtd_total_unidades,
    $lucro_por_lote,
    $lucro_por_unidade,
    $id
);

if($stmt->execute()){
    echo json_encode(["sucesso" => true, "msg" => "Produto atualizado com sucesso"]);
}else{
    echo json_encode(["erro" => "Erro ao atualizar produto"]);
}

$stmt->close();
exit;
