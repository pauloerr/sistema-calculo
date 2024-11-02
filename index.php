<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../logon/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Cálculos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">Sistema de Cálculos</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#" id="link-novo-calculo"><i class="bi bi-calculator"></i> Novo Cálculo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#" id="link-meus-calculos"><i class="bi bi-file-earmark-bar-graph"></i> Meus Cálculos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#" id="link-sobre"><i class="bi bi-info-square"></i> Sobre</a>
                    </li>                    
                </ul>
            </div>
            <spam class="text-white">Conectado como <?php echo ucfirst(strtolower($_SESSION['user_name']));?></spam>&nbsp;&nbsp;&nbsp;<a class="text-white" href="logout.php"><b><i class="bi bi-box-arrow-right"></i></b></a>
        </div>
    </nav>

    <!-- Conteúdo Dinâmico -->
    <div id="conteudo" class="container mt-1 flex-grow-1">
        <!-- O conteúdo das outras páginas será carregado aqui -->
    </div>

    <footer class="bg-primary text-white text-center py-3 mt-auto">
        <p>&copy; 2024 Sistema de Cálculos</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts.js"></script>
    <!-- Em seguida, o CSS do DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- E por último, o JS do DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</body>
</html>
