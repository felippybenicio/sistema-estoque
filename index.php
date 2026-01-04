<?php
session_start();
include './php/conexao.php';

unset($_SESSION['origem_cadastro']);


$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user']);
    $senha = trim($_POST['senha']);

    // Busca o usuário
    $stmt = $conn->prepare("SELECT id, user, senha, nivel FROM autenticacao WHERE user = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verifica senha
        if (password_verify($senha, $usuario['senha'])) {

            // Salva dados na sessão
            $_SESSION['id']    = $usuario['id'];
            $_SESSION['user']  = $usuario['user'];
            $_SESSION['nivel'] = $usuario['nivel'];

            header("Location: php/produtos/painel.php");
            exit;
        } 
    }

    $erro = "Usuário ou senha incorretos.";
}
?> 




<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Estoque</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/autenticacao/login.css">
</head>
<body>
    
    <main class="login-container">
        <h1>Login</h1>

        <form method="POST">
            <label for="user">Usuário</label>
            <input type="text" id="user" name="user" required>

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required>

            <?php if (!empty($erro)): ?>
                <p class="msg"><?php echo $erro; ?></p>
            <?php endif; ?>
            <button type="submit">Entrar</button>
        </form>

        <a class="link" href="php/autenticacao/cadastro.php">Criar conta</a>
    </main>

</body>
</html>
