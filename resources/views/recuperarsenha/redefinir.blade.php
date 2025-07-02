<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5" style="max-width: 500px;">
        <h3 class="text-center">Redefinir Senha</h3>
        <form action="{{ url('senha.atualizar', ['id' => $usuario->id]) }}" method="POST">
            @csrf

            <div class="mb-3 position-relative">
                <label for="nova_senha" class="form-label">Nova senha</label>
                <input type="password" name="nova_senha" id="nova_senha" class="form-control" required>
                <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3"
                   onclick="togglePassword('nova_senha', this)" style="cursor: pointer;"></i>
            </div>

            <div class="mb-3 position-relative">
                <label for="confirmar_senha" class="form-label">Confirmar nova senha</label>
                <input type="password" name="nova_senha_confirmation" id="confirmar_senha" class="form-control" required>

                <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3"
                   onclick="togglePassword('confirmar_senha', this)" style="cursor: pointer;"></i>
            </div>

            <button type="submit" class="btn btn-success w-100">Salvar nova senha</button>
        </form>
    </div>

    <!-- Script para alternar visibilidade -->
    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        }
    </script>
</body>
</html>
