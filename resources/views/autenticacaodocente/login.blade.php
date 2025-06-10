<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Login</title>
    <!-- CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            margin-top: 100px;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .login-container .form-control {
            margin-bottom: 15px;
        }
        .login-container .btn-login {
            width: 100%;
            padding: 10px;
            background-color: #0d6efd;
            border: none;
            border-radius: 4px;
        }
        .login-container .btn-login:hover {
            background-color: #0b5ed7;
        }
        .login-container .forgot-password {
            text-align: right;
            font-size: 0.9em;
            color: #0d6efd;
            text-decoration: none;
        }
        .login-container .forgot-password:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-container">
        <h2>Login</h2>
        <form action="{{url('/docenteautentica')}}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Digite seu e-mail" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" name="senha" class="form-control" id="password" placeholder="Digite sua senha" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Lembrar-me</label>
            </div>
            <button type="submit" class="btn btn-primary btn-login">Entrar</button>
            <a href="{{url('teste')}}" class="forgot-password d-block mt-3">Esqueceu sua senha?</a>
        </form>
    </div>
</div>

<!-- JavaScript do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
