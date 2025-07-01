<!-- resources/views/configuracao/alterar-senha.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .password-toggle {
            cursor: pointer;
            color: #0d6efd;
        }
    </style>
</head>
<body>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <div class="container mt-5">
        <h1 class="mb-4">Alterar Senha</h1>
        <?php
            $id = session('usuario_id');
        ?>
        <form action="{{ url('/perfil') }}" method="POST">


            @csrf
            <div class="mb-3">
                <label for="senha_atual" class="form-label">Senha Atual</label>
                <input type="password" name="senha_atual" id="senha_atual" class="form-control" required>
                <small class="password-toggle" onclick="togglePassword('senha_atual')">Mostrar Senha</small>
            </div>

            <div class="mb-3">
                <label for="nova_senha" class="form-label">Nova Senha</label>
                <input type="password" name="nova_senha" id="nova_senha" class="form-control" required>
                <small class="password-toggle" onclick="togglePassword('nova_senha')">Mostrar Senha</small>
            </div>

            <div class="mb-3">
                <div class="mb-3">
                    <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                    <input type="password" name="nova_senha_confirmation" id="confirmar_senha" class="form-control" required>
                    <small class="password-toggle" onclick="togglePassword('confirmar_senha')">Mostrar Senha</small>
                </div>


            <button type="submit" class="btn btn-primary">Alterar Senha</button>
            <a href="{{ route('perfil') }}" class="btn btn-secondary">Voltar</a>
        </form>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para alternar a visibilidade da senha
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            if (field.type === 'password') {
                field.type = 'text';
            } else {
                field.type = 'password';
            }
        }
    </script>
</body>
</html>
