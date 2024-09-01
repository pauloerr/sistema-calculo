<?php
$servername = "localhost";
$username = "root"; // usuário padrão do XAMPP
$password = "";     // senha padrão do XAMPP (em geral é vazia)
$dbname = "calculos"; // nome do seu banco de dados

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
