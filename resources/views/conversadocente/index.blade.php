<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat do Docente</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .chat-container {
            max-width: 900px;
            margin: 100px auto 20px;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .message {
            margin-bottom: 20px;
        }
        .message.user {
            text-align: right;
            color: #0d6efd;
        }
        .message.ai {
            text-align: left;
            color: #333;
        }
        .btn-suggestion {
            margin: 8px 4px;
        }
    </style>
</head>
<body>

<div class="container chat-container">
    <h4 class="text-center mb-4">Olá {{ session('nome') ?? 'Docente' }}, em que posso ajudar?</h4>

    <div id="chat-box">
        <!-- Mensagens serão inseridas aqui -->
    </div>

    <div class="text-center mb-3">
        <button class="btn btn-primary btn-suggestion" onclick="selectSuggestion('Horario de aulas e turmas')">Horario de aulas e turmas</button>
        <button class="btn btn-primary btn-suggestion" onclick="selectSuggestion('Datas dos testes')">Datas dos testes</button>
        <button class="btn btn-primary btn-suggestion" onclick="selectSuggestion('Datas de exames')">Datas de exames</button>
        <button class="btn btn-primary btn-suggestion" onclick="selectSuggestion('Olá tudo bem?')">Olá</button>
    </div>
</div>

<script>
    function appendMessage(role, text) {
        const box = document.getElementById('chat-box');
        const msg = document.createElement('div');
        msg.className = 'message ' + role;
        msg.innerHTML = `<strong>${role === 'user' ? 'Você' : 'UCM'}:</strong> ${text}`;
        box.appendChild(msg);
        box.scrollTop = box.scrollHeight;
    }

    async function selectSuggestion(text) {
        appendMessage('user', text);
        appendMessage('ai', 'Digitando...');

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch('/docente-pergunta', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ message: text })
            });

            const data = await response.json();

            const loading = document.querySelector('.message.ai:last-child');
            if (loading) loading.remove();

            appendMessage('ai', data.response);
        } catch (e) {
            console.error(e);
            appendMessage('ai', 'Erro ao obter resposta da IA.');
        }
    }
</script>

</body>
</html>


