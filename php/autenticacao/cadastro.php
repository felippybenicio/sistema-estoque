<?php
session_start();
include '../conexao.php';

$veioDoControle = ($_SESSION['origem_cadastro'] ?? '') === 'controle';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome']);
    $user = trim($_POST['user']);
    $senha = trim($_POST['senha']);
    $confirmacao = trim($_POST['confirm']);
    $nivel = trim($_POST['nivel']);

    if (empty($nome) || empty($user) || empty($senha) || empty($confirmacao) || empty($nivel)) {
        $msg = "<p class = 'msg'>Preencha todos os campos!</p>";
    } elseif ($senha !== $confirmacao) {
        $msg = "<p class = 'msg'>As senhas não coincidem!</p>";
    } else {
        // Hash da senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Cadastro
        $stmt = $conn->prepare("INSERT INTO autenticacao (nome, user, senha, nivel) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $user, $senhaHash, $nivel);

        if ($stmt->execute()) {
            if ($veioDoControle) {
                header("Location: ../funcionarios/lista.php?msg=sucesso");
                exit;
            } else {
                header("Location: ../produtos/painel.php?msg=sucesso");
                exit;
            }
            $acerto = "Usuário cadastrado com sucesso!";
        } else {
            $msg = "Erro ao cadastrar usuário: " . $conn->error;
        }
        $stmt->close();
    }
}
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Estoque</title>

    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/autenticacao/cadastro.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>

    <main class="login-container">
        <h1>Cadastro</h1>

        <form method="POST">
            
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" required>

            <label for="user">Usuário</label>
            <input type="text" id="user" name="user" required>

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required>

            <label for="confirm">Confirmar senha</label>
            <input type="password" id="confirm" name="confirm" required>

            <h2>Nível de Funcionário</h2>
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


            <?php if (!empty($msg)) : ?>
                <div class="alerta-msg">
                    <?= $msg ?>
                </div>
            <?php endif; ?>

            <button type="submit">Cadastrar</button>
        </form>

        <?php if ($veioDoControle) {
            echo "<a href='../funcionarios/lista.php' class='link' id='btnVoltar'>Voltar para tele de funcionários</a>";

            } else {
                echo "<a class='link' href='../../index.php'>Já possuo conta</a>";
            }
            
        ?>
    </main>

</body>
</html>
