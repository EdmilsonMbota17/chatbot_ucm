<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 500px;
            margin: auto;
            margin-top: 50px;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .btn-back {
            background: none;
            border: none;
            color: #0d6efd;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="register-container">
        <h2>Criar Conta</h2>
        <form action="{{ route('registrar') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" name="nome" id="nome" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>
            <div class="mb-3">

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="senha" id="senha" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="toggleSenha('senha', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="confirmar_senha" class="form-label">Confirmar Senha</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="confirmar_senha" id="confirmar_senha" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="toggleSenha('confirmar_senha', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>


            <div class="mb-3">
                <label for="pergunta" class="form-label">Pergunta de recuperação</label>
                <select class="form-select" name="pergunta_recuperacao" id="pergunta" required>
                    <option value="" selected disabled>Selecione uma pergunta</option>
                    <option value="Qual é o nome do seu primeiro animal de estimação?">Qual é o nome do seu primeiro animal de estimação?</option>
                    <option value="Qual é o nome da cidade onde você nasceu?">Qual é o nome da cidade onde você nasceu?</option>
                    <option value="Qual é o nome da sua mãe?">Qual é o nome da sua mãe?</option>
                    <option value="Qual foi a sua primeira escola?">Qual foi a sua primeira escola?</option>
                    <option value="Qual é a sua comida favorita?">Qual é a sua comida favorita?</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="resposta" class="form-label">Resposta da pergunta</label>
                <input type="text" class="form-control" name="resposta_recuperacao" id="resposta" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Confirmar Cadastro</button>
        </form>


    </div>
</div>
<script>
    function toggleSenha(id, btn) {
        const campo = document.getElementById(id);
        const icone = btn.querySelector('i');

        if (campo.type === "password") {
            campo.type = "text";
            icone.classList.remove("bi-eye");
            icone.classList.add("bi-eye-slash");
        } else {
            campo.type = "password";
            icone.classList.remove("bi-eye-slash");
            icone.classList.add("bi-eye");
        }
    }
    </script>

</body>
</html>
