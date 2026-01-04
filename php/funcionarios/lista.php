
<?php
    session_start();
    include '../conexao.php';
    
    require '../permissoes.php';
    exigirPermissao(['admin', 'leitor']);

    $usuario_id = $_SESSION['id'];
    $_SESSION['origem_cadastro'] = 'controle';

    $stmtCadastrado = $conn->prepare("SELECT nome, nivel FROM autenticacao WHERE id = ?"); 
    $stmtCadastrado->bind_param("i", $usuario_id);
    $stmtCadastrado->execute();
    $stmtCadastrado->bind_result($nome, $nivel);
    $stmtCadastrado->fetch();
    $stmtCadastrado->close(); 

    // Consulta os funcionários
    $sql = "SELECT id, nome, user, nivel, data_criacao FROM autenticacao ORDER BY nome ASC";
    $result = $conn->query($sql);

    function dataHora($dataHoraOriginal) {
        $dataHora = DateTime::createFromFormat('Y-m-d H:i:s', $dataHoraOriginal);
        if (!$dataHora) return $dataHoraOriginal;
        return $dataHora->format("d/m/Y") . " - " . $dataHora->format("H:i");
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionários</title>
    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/painel/menu.css?v=1">
    <link rel="stylesheet" href="../../assets/css/funcionarios/edicao.css">
    <link rel="stylesheet" href="../../assets/css/funcionarios/lista.css">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>

<header class="topo">
    <button id="menuBtn" class="menuBtn"><span class="material-symbols-outlined">menu</span></button>
    <h1>Funcionários Cadastrados</h1>
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
                <li><a href="../saida/painel_saida.php"><button>Saídas de produtos</button></a></li>
            <?php endif ?>
            <?php if (pode(['admin', 'leitor'])): ?>
                <li><a href="../funcionarios/lista.php"><button>Usuários</button></a></li>
            <?php endif ?>
    </ul>
    <a href="../../index.php"><button id="sair">Sair</button></a>
</nav>
<main>
    <section id="lista">
    
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($f = $result->fetch_assoc()): ?>
    
            <div class="card-funcionario" data-id="<?= $f['id'] ?>">
    
                <div class="info">
                    <h2><?= htmlspecialchars($f['nome']) ?></h2>
                    <p><strong>Usuário:</strong> <?= htmlspecialchars($f['user']) ?></p>
                    <p class="nivel"><strong>Nível:</strong> <?= htmlspecialchars($f['nivel']) ?></p>
                    <p class="data"><strong>Cadastrado:</strong> <?= dataHora($f['data_criacao']) ?></p>

                </div>
                <?php if (pode('admin')): ?>
                    <div class="acoes">
                        <button class="btn editar" data-id="<?= $f['id'] ?>">Editar</button>
                        <button class="btn excluir" data-id="<?= $f['id'] ?>">Excluir</button>
                    </div>
                <?php endif ?>
    
            </div>
    
        <?php endwhile; ?>
    <?php else: ?>
    
        <p class="vazio">Nenhum funcionário encontrado.</p>
    
    <?php endif; ?>
    
    </section>
    <section id="modalEditar">
        <h3>Editar Cadastro</h3>
            <form action="cadastrar_produtos.php" method="post" id="formCadastroProduto" class="formProduto">
                <div class="grupo">
                    <label>
                        Nome <br>
                        <input type="text" name="nome" required>
                    </label>
                </div>

                <div class="grupo">
                    <label>
                        Usuário <br>
                        <input type="text" name="user" required>
                    </label>
                </div>

                <h4>Nível de Funcionário</h4>
                <div class="nivel">
                    <input type="radio" id="admin" name="nivel" value="admin" required>
                    <label for="admin">Administrador (Acesso total)</label>
                </div>

                <div class="nivel">
                    <input type="radio" id="operador" name="nivel" value="operador" required>
                    <label for="operador">Operador (Acesso ao estoque)</label>
                </div>

                <div class="nivel">
                    <input type="radio" id="leitor" name="nivel" value="leitor" required>
                    <label for="leitor">Analista (Só visualiza dados)</label>
                </div>
                    <div class="botoes">
                        <button type="button" id="alterarSenha" class="btnSenha">Alterar senha</button>
                        <button type="submit" class="btnSalvar">Salvar edição</button>
                        <button type="button" id="fechaEdicao" class="btnFechar">Cancelar</button>
                    </div>

            </form>
    </section> 
    <section id="modalAlterarSenha">
        <h3>Alterar Senha</h3>
        <form action="cadastrar_produtos.php" method="post" id="formAlterarSenha" class="formProduto">
            <div class="grupo">
                    <label>
                        Senha antiga <br>
                        <input type="password" name="sennhaAntiga" min="1" required>
                    </label>
                </div>

                <div class="grupo">
                    <label>
                        Nova senha <br>
                        <input type="password" name="novaSenha" min="1" required>
                    </label>
                </div>

                <div class="grupo">
                    <label>
                        Confirme a nova senha <br>
                        <input type="password" name="confirmacao" step="0.01" min="0" required>
                    </label>
                </div>

                <div class="botoes">
                    <button type="submit" class="btnSalvar">Salvar edição</button>
                    <button type="button" id="fechaEdicaoSenha" class="btnFechar">Cancelar</button>
                </div>
        </form>
    </section>
    </main>
    <?php if (pode('admin')): ?>
        <footer><a href="../autenticacao/cadastro.php" id="cadastrarFunc">Cadastrar novo colaborador</a></footer>
    <?php endif ?>

<script src="../../assets/js/funcionario/excluir_func.js"></script>
<script src="../../assets/js/funcionario/editar_cadastro.js"></script>
<script src="../../assets/js/painel/menu.js"></script>
</body>
</html>
