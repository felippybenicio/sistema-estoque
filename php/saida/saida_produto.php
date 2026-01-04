<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['id'])) {
    echo json_encode(["erro" => "Usuário não autenticado"]);
    exit;
}

require '../conexao.php';

$produtos = $_POST['id_produto'] ?? [];
$quantidades = $_POST['quantidade'] ?? [];

if (count($produtos) === 0 || count($quantidades) === 0) {
    echo json_encode(["erro" => "Nenhum item válido"]);
    exit;
}

$totalItens = count($produtos);
$estoquesAtualizados = [];

for ($i = 0; $i < $totalItens; $i++) {

    $idProduto = intval($produtos[$i]);
    $qtd_retirar = intval($quantidades[$i]);

    if ($qtd_retirar <= 0) {
        echo json_encode(["erro" => "Quantidade inválida"]);
        exit;
    }

    // -----------------------------
    // BUSCAR PRODUTO
    // -----------------------------
    $stmt = $conn->prepare("
        SELECT id, nome, qtd_total_unidades, qtd_por_lote, qtd_de_lote
        FROM produtos 
        WHERE id = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $idProduto);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        echo json_encode(["erro" => "Produto ID '$idProduto' não encontrado"]);
        exit;
    }

    $produto = $resultado->fetch_assoc();
    $estoqueAtual = intval($produto['qtd_total_unidades']);
    $qtdPorLote = intval($produto['qtd_por_lote']);
    $qtdDeLote = intval($produto['qtd_de_lote']);

    // -----------------------------
    // VERIFICAR ESTOQUE
    // -----------------------------
    if ($estoqueAtual < $qtd_retirar) {
        echo json_encode([
            "erro" => "Estoque insuficiente para '{$produto['nome']}'. Atual: $estoqueAtual"
        ]);
        exit;
    }

    $novoEstoque = $estoqueAtual - $qtd_retirar;

    // -----------------------------
    // CALCULAR NOVA QUANTIDADE DE LOTES
    // -----------------------------
    // Lotes inteiros restantes
    $novosLotes = ceil($novoEstoque / $qtdPorLote);

    // -----------------------------
    // ATUALIZAR ESTOQUE E LOTES
    // -----------------------------
    $up = $conn->prepare("
        UPDATE produtos
        SET qtd_total_unidades = ?, qtd_de_lote = ?
        WHERE id = ? AND qtd_total_unidades >= ?
    ");
    $up->bind_param("iiii", $novoEstoque, $novosLotes, $idProduto, $qtd_retirar);

    if (!$up->execute() || $up->affected_rows === 0) {
        echo json_encode([
            "erro" => "Erro ao atualizar estoque ou estoque insuficiente para '{$produto['nome']}'"
        ]);
        exit;
    }


    $estoquesAtualizados[] = [
        "id" => $idProduto,
        "novo_estoque" => $novoEstoque,
        "novos_lotes" => $novosLotes
    ];

}

echo json_encode(["sucesso" => true]);
exit;
?>
