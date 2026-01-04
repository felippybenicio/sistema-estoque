<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica se o usuário tem permissão
 */
function pode($niveisPermitidos) {
    if (!isset($_SESSION['nivel'])) {
        return false;
    }

    return in_array($_SESSION['nivel'], (array)$niveisPermitidos);
}

/**
 * Bloqueia acesso direto ao arquivo
 */
function exigirPermissao($niveisPermitidos) {
    if (!pode($niveisPermitidos)) {
        http_response_code(403);
        echo "Acesso negado.";
        exit;
    }
}
