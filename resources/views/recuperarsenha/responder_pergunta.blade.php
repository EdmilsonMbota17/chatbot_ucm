<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responder Pergunta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="text-center">Confirme sua identidade</h3>
    <p><strong>Pergunta:</strong> {{ $usuario->pergunta_recuperacao }}</p>
    <form action="{{ url('responder-pergunta/{id}') }}" method="POST">
        @csrf
        <input type="hidden" name="usuario_id" value="{{ $usuario->id }}">
        <div class="mb-3">
            <label for="resposta" class="form-label">Sua resposta</label>
            <input type="text" name="resposta" id="resposta" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Verificar</button>
    </form>
</div>
</body>
</html>
