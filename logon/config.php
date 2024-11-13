<?php
$servername = "localhost";
$username = "root"; // Seu usuário MySQL
$password = ""; // Sua senha MySQL
$dbname = "calculos"; // Substitua pelo nome do seu banco de dados

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
