<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">Sistema de Cálculos</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <div class="container mt-5 conteudo">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">Logon</h5>
                        <form id="logonForm" action="authenticate.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Usuário</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Entrar</button>
                        </form>
                        <div id="error-message" class="alert alert-danger mt-3 d-none"></div>
                        <p class="text-center mt-3">
                            Não tem uma conta? <a href="register.php">Cadastre-se</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-primary text-white text-center py-3 mt-auto">
        <p>&copy; 2024 Sistema de Cálculos</p>
    </footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="scripts.js"></script>
</body>
</html>
