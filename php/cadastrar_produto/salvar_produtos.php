<?php
session_start();
include '../conexao.php';

// Pega id do usuário logado
$usuario_id = $_SESSION['id'];
 
// Recebe arrays de dados
$nomes = $_POST['nome'];
$categorias = $_POST['categoria'];
$qtdPorLote = $_POST['quantidadePorLote'];
$qtdDeLote = $_POST['quantidadeDeLote'];
$precoPagoLote = $_POST['precoPagoLote'];
$valorRevenda = $_POST['valorRevendaUnidade'];
$descricoes = $_POST['descricao'];

for ($i = 0; $i < count($nomes); $i++) {

    // Segurança: ignora blocos enviados vazios
    if (trim($nomes[$i]) === "") {
        continue;
    }

    // Cálculos automáticos
    $qtd_total_unidades = $qtdPorLote[$i] * $qtdDeLote[$i];

    $valor_pago_por_unidade = $precoPagoLote[$i] / $qtdPorLote[$i];
    $lucro_por_unidade = $valorRevenda[$i] - $valor_pago_por_unidade;
    $lucro_por_lote = $lucro_por_unidade * $qtdPorLote[$i];

    $codigo = random_int(100000, 999999);

    // INSERIR NO BANCO
    $stmt = $conn->prepare("
        INSERT INTO produtos (
            usuario_id,
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
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "isssiiddsidd",
        $usuario_id,
        $nomes[$i],
        $codigo,
        $categorias[$i],
        $qtdPorLote[$i],
        $qtdDeLote[$i],
        $precoPagoLote[$i],
        $valorRevenda[$i],
        $descricoes[$i],
        $qtd_total_unidades,
        $lucro_por_lote,
        $lucro_por_unidade
    );

    if (!$stmt->execute()) {
        die("Erro ao inserir produto ($nomes[$i]): " . $stmt->error);
    }

    $stmt->close();
}

// Depois de tudo, volta ao painel
header("Location: painel.php");
exit;

?>
