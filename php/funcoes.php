<?php
include 'conexao.php';

function temPermissao($acao) {
    if(!isset($_SESSION['usuario_nivel'])){
        return false;
    }

    $nivel = $_SESSION['usuario_nivel'];

    // Define permissões por nível
    $permissoes = [
        'admin' => ['cadastrar', 'editar', 'excluir', 'visualizar'],
        'operador' => ['cadastrar', 'editar', 'visualizar'],
        'leitor' => ['visualizar']
    ];

    return in_array($acao, $permissoes[$nivel]);
}
