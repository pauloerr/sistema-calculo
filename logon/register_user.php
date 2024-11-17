<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$email = $_POST['email'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos.']);
    exit;
}

$query = "SELECT cod_usuario FROM usuarios WHERE usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Usuário já existe.']);
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->close();

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO usuarios (usuario, senha, email) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('sss', $username, $hashed_password, $email);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar usuário.']);
}

$stmt->close();
$conn->close();
?>
