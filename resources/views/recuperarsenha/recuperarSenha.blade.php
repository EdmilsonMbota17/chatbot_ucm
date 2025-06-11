<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .recovery-container {
            max-width: 500px;
            margin: auto;
            margin-top: 100px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="recovery-container">
            <h2>Recuperar Senha</h2>
            <form action="{{ url('verificar-recuperacao') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail cadastrado</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="pergunta" class="form-label">Pergunta de segurança</label>
                    <select name="pergunta_recuperacao" class="form-select" required>
                        <option value="">Selecione uma pergunta</option>
                        <option value="pet">Qual o nome do seu primeiro animal de estimação?</option>
                        <option value="mae">Qual o nome da sua mãe?</option>
                        <option value="escola">Qual o nome da sua primeira escola?</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="resposta" class="form-label">Resposta</label>
                    <input type="text" name="resposta_recuperacao" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Verificar</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
