<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="text-center">Recuperar Senha</h3>
    <form action="{{ url('recuperar-senha') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Digite seu e-mail</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Avançar</button>
    </form>
</div>
</body>
</html>
