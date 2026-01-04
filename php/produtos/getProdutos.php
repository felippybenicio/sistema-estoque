<?php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');

include "../conexao.php";

// recebe POST ou GET
$id = $_POST['id'] ?? $_GET['id'] ?? 0;
$id = intval($id);

if($id === 0){
    echo json_encode(["erro" => "ID não enviado"]);
    exit;
}

$stmt = $conn->prepare("SELECT 
                            nome,
                            codigo,
                            categoria,
                            qtd_por_lote,
                            qtd_de_lote,
                            preco_pago_lote,
                            valor_revenda_unidade,
                            descricao,
                            qtd_total_unidades,
                            lucro_por_lote,
                            lucro_por_unidade
                        FROM produtos 
                        WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$produto = $stmt->get_result();

if($produto->num_rows > 0){
    echo json_encode($produto->fetch_assoc());
}else{
    echo json_encode(["erro"=>"Produto não encontrado"]);
}
exit;
