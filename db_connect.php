<?php
$servername = "localhost";
$username = "root"; // usuário padrão do XAMPP
$password = "";     // senha padrão do XAMPP (em geral é vazia)
$dbname = "calculos"; // nome do seu banco de dados

// Função para obter a conexão
function getConnection() {
    global $servername, $username, $password, $dbname; // Tornar as variáveis globais
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Checar conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    return $conn; // Retorna a conexão
}
?>
