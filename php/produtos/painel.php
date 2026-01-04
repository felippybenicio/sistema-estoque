<?php
    include '../conexao.php';
    include '../funcoes.php';
    
    
    require '../permissoes.php';
    exigirPermissao(['admin', 'operador', 'leitor']);


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
    <title>Painel - Sistema de Estoque</title>

    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/painel/menu.css?v=1">
    <link rel="stylesheet" href="../../assets/css/painel/info_produtos.css">
    <link rel="stylesheet" href="../../assets/css/painel/principal.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

</head>
<body>
    
    <header class="topo"> 
        <button id="menuBtn" class="menuBtn"><span class="material-symbols-outlined">menu</span></button>
        <h1>Painel do Sistema</h1>
        <p>Olá, <strong><?php echo $nome; ?></strong></p>
    </header>

    <nav>
        <section>
            <p><strong><?php echo $nome; ?></strong> </p>
            <p>Nivel de acesso: (<?php echo $nivel ?>)</p> 
        </section>
        <ul>
            <?php if (pode(['admin', 'operador', 'leitor'])): ?>
                <li><button>Peinel principal</button></li>
            <?php endif ?>
            <?php if (pode(['admin', 'operador'])): ?>
                <li><a href="../cadastrar_produto/painel_cadastro.php"><button id="btnAbrirCadastro">Cadastrar Produtos</button></a></li>
            <?php endif ?>
             <?php if (pode('admin')): ?>
                <li><a href="../saida/painel_saida.php"><button>Saídas de produtos</button></a></li>
            <?php endif ?>
            <?php if (pode(['admin', 'leitor'])): ?>
                <li><a href="../funcionarios/lista.php"><button>Usuários</button></a></li>
            <?php endif ?>
            </ul>
        <a href="../../index.php"><button id="sair">Sair</button></a>
    </nav>

    <main class="conteudo">

        <section class="topo-painel">
            <h2>Produtos</h2>
            <input type="text" id="pesquisar" placeholder="Buscar por nome ou código">
        </section>

        <section class="lista-produtos" id="listaProdutos">
            <?php
            $stmt = $conn->prepare("SELECT id, nome, codigo FROM produtos ORDER BY nome ASC");
            $stmt->execute();
            $resultado = $stmt->get_result();

            while ($produto = $resultado->fetch_assoc()):
            ?>
                <div class="item-produto" data-id="<?= $produto['id'] ?>">
                    <p class="nome"><?= htmlspecialchars($produto['nome']) ?></p>
                    <p class="codigo">Código: <?= htmlspecialchars($produto['codigo']) ?></p>
                     <?php if (pode('admin')): ?>
                        <span class="material-symbols-outlined" id="deletarProduto2">delete</span>
                    <?php endif ?>
                    </div>
            <?php endwhile; ?>
        </section> 

        <!-- MODAL DE DETALHES DO PRODUTO -->
        <section id="modalProduto" class="modal">
            <div class="conteudo-modal">
                <span class="material-symbols-outlined" id="fecharModal">close</span>

                <h3 id="modalNome">Carregando...</h3>

                <p id="modalCodigo">...</p>
                <p id="modalCategoria">...</p>
                <p id="modalQtdPorLote">...</p>
                <p id="modalQtdDeLote">...</p>
                <p id="modalPrecoPagoLote">...</p>
                <p id="modalValorRevendaUnidade">...</p>
                <p id="modalDescricao">...</p>
                <p id="modalQtdTotalUnidades">...</p>
                <p id="modalLucroPorLote">...</p>
                <p id="modalLucroPorUnidade">...</p>
                <div id="btnEdcoes">
                    <button type="button" id="editar">Edtar</button>
                    <button type="submit" id ="salvarEdicao" style="display: none">Salvar</button>
                    <?php if (pode('admin')): ?>
                        <button type="button" id="deletarProduto1">Excluir</button>
                    <?php endif; ?>
                    
                </div>
            </div>
        </section>
    </main>
    
    <script>
        const NIVEL_USUARIO = "<?php echo $nivel ?>";
    </script>

    <script src="../../assets/js/painel/principal.js"></script>
    <script src="../../assets/js/painel/menu.js"></script>
    <script src="../../assets/js/painel/detalhes_produtos.js"></script>
</body>
</html>
