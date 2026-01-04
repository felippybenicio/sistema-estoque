<?php
    include '../conexao.php';

    require '../permissoes.php';
    exigirPermissao(['admin', 'operador']);

 
    $usuario_id = $_SESSION['id'];

    $stmtCadastrado = $conn->prepare("SELECT nome, nivel FROM autenticacao WHERE id = ?");
    $stmtCadastrado->bind_param("i", $usuario_id);
    $stmtCadastrado->execute();
    $stmtCadastrado->bind_result($nome, $nivel);
    $stmtCadastrado->fetch();
    $stmtCadastrado->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/painel/menu.css?v=1">
    <link rel="stylesheet" href="../../assets/css/cadastro_produto/cadastro_produtos.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    
</head>
<body> 
    <header class="topo">
        <button id="menuBtn" class="menuBtn"><span class="material-symbols-outlined">menu</span></button>
        <h1>Cadastro de Produtos</h1>
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
                <li><button id="btnAbrirCadastro">Cadastrar Produtos</button></li>
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
    <main>
        <section id="cadastroMultiplo" class="cadastroMultiplo">

            <form action="painel_cadastro.php" method="post" id="formCadastroMultiplo">
                
                <div id="containerProdutos"></div>

                

                <div class="botoesFinal">
                    <button type="button" id="btnAddProduto" class="btnAddProduto">+ Adicionar Produto</button>
                    <button type="submit" class="btnSalvar">Salvar Tudo</button>
                </div>
            </form>
        </section>

        <!-- TEMPLATE INVISÍVEL -->
        <div id="produtoTemplate" style="display: none;">
            <div class="produtoBloco">

                <div class="grupo">
                    <label>Nome do produto
                        <input type="text" name="nome[]" required>
                    </label>
                </div>

                <div class="grupo">
                    <label>Categoria
                        <input type="text" name="categoria[]" required>
                    </label>
                </div>

                <div class="grupo">
                    <label>Qtd. de unidades por lote
                        <input type="number" name="quantidadePorLote[]" min="1" required>
                    </label>
                </div>
 
                <div class="grupo">
                    <label>Qtd. de lotes
                        <input type="number" name="quantidadeDeLote[]" min="1" required>
                    </label>
                </div>

                <div class="grupo">
                    <label>Preço pago por lote
                        <input type="number" name="precoPagoLote[]" step="0.01" min="0" required>
                    </label>
                </div>

                <div class="grupo">
                    <label>Valor de revenda por unidade
                        <input type="number" name="valorRevendaUnidade[]" step="0.01" min="0" required>
                    </label>
                </div>

                <div class="grupo">
                    <label>Descrição
                        <textarea name="descricao[]"></textarea>
                    </label>
                </div>

                <button type="button" class="btnRemoverItem">Remover este produto</button>
                <hr>
            </div>
        </div>

    </main>
    <script src="../../assets/js/cadastro_produto/cadastro_produto.js"></script>
    <script src="../../assets/js/painel/menu.js"></script>
</body>
</html>