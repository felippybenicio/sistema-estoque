<?php
// Detecta se está rodando localmente ou em rede/web
$servidor_local = (
    $_SERVER['HTTP_HOST'] === 'localhost'
    || $_SERVER['HTTP_HOST'] === '127.0.0.1'
);


// Dados padrão


// Configurações para ambiente local
if ($servidor_local) {
    $database = "sistema-estoque";
    $servername = "localhost";
    $username = "root";
    $password = "Duk23092020$$";
} else {
    $database = "if0_40669997_sistema_estoque";
    $servername = "sql112.infinityfree.com"; // confira no hPanel qual é o host real
    $username = "if0_40669997";   
    $password = 'Duk23092020'; 
}

$conn = new mysqli($servername, $username, $password, $database);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>
