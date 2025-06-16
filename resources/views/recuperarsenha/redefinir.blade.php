<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="text-center">Redefinir Senha</h3>
    <form action="{{ url('senha.atualizar', ['id' => $usuario->id]) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nova_senha" class="form-label">Nova senha</label>
            <input type="password" name="nova_senha" id="nova_senha" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirmar_senha" class="form-label">Confirmar nova senha</label>
            <input type="password" name="confirmar_senha" id="confirmar_senha" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Salvar nova senha</button>
    </form>
</div>
</body>
</html>
