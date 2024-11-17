<?php
session_start();
include 'config.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos.']);
    exit;
}

// Consulta ao banco de dados
$query = "SELECT senha, cod_usuario, usuario FROM usuarios WHERE usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($hashed_password, $cod_usuario, $usuario);
    $stmt->fetch();

    // Verifica se a senha está correta
    if (password_verify($password, $hashed_password)) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $cod_usuario; 
        $_SESSION['user_name'] = $usuario; 
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Credenciais incorretas. Verifique usuário ou senha.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Usuário não cadastrado. Verifique credencial ou cadastre um novo usuário.']);
}

$stmt->close();
$conn->close();
?>
