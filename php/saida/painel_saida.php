<?php
    session_start();
    include '../conexao.php';

  
    require '../permissoes.php';
    exigirPermissao(['admin']);

    $usuario_id = $_SESSION['id'];

    $stmtCadastrado = $conn->prepare("SELECT nome, nivel FROM autenticacao WHERE id = ?");
    $stmtCadastrado->bind_param("i", $usuario_id);
    $stmtCadastrado->execute();
    $stmtCadastrado->bind_result($nome, $nivel);
    $stmtCadastrado->fetch();
    $stmtCadastrado->close();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lançar Saída</title>
    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/painel/menu.css?v=1">
    <link rel="stylesheet" href="../../assets/css/saida/painel_saida.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>

<header class="topo">
    <button id="menuBtn" class="menuBtn"><span class="material-symbols-outlined">menu</span></button>
    <h1>Lançar Saída de Produtos</h1>
</header>
<nav>
    <section>
        <p><strong><?php echo $nome; ?></strong> </p>
        <p>Nivel de acesso: (<?php echo $nivel ?>)</p>
    </section>
    <ul>
        <?php if (pode(['admin', 'operador', 'leitor'])): ?>
            <li><a href="../produtos/painel.php"><button>Peinel principal</button></a></li>
        <?php endif ?>
        <?php if (pode(['admin', 'operador'])): ?>
            <li><a href="../cadastrar_produto/painel_cadastro.php"><button id="btnAbrirCadastro">Cadastrar Produtos</button></a></li>
        <?php endif ?>
            <?php if (pode('admin')): ?>
            <li><button>Saídas de produtos</button></li>
        <?php endif ?>
        <?php if (pode(['admin', 'leitor'])): ?>
            <li><a href="../funcionarios/lista.php"><button>Usuários</button></a></li>
        <?php endif ?>
    </ul>
    <a href="../../index.php"><button id="sair">Sair</button></a>
</nav>
<main>
    <section id="container">
     
        <form id="formSaida">
    
            <div id="listaItens">
                <div id="listaItensFormatados"></div>
                <div class="itemSaida">
                    <label>
                        Buscar produto por nome ou código
                        <input type="text" name="produto[]">
                    </label>
     
                    <label>
                        Quantidade de unidades
                        <input type="number" min="1" name="quantidade[]">
                    </label>
                </div>
            </div>
    
            <div id="botoes">
                <button type="button" id="addItem" class="btnAdd">+ Adicionar Item</button>
                <button type="submit" class="btnSalvar">Lançar Saída</button>
            </div>
    
        </form>
    
    </section>
</main>

<script src="../../assets/js/saida/saida.js"></script>
<script src="../../assets/js/saida/subtracao_produtos.js"></script>
<script src="../../assets/js/painel/menu.js"></script>

</body>
</html>
