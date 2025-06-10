<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Chat - Estilo IA</title>
    <!-- CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .navbar {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
            z-index: 1000;
            position: fixed;
            top: 0;
            width: 100%;
        }
        .navbar-brand {
            font-weight: bold;
            color: #0d6efd;
        }
        .sidebar {
            width: 250px;
            background-color: #ffffff;
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 60px; /* Abaixo da navbar */
            bottom: 0;
            left: 0;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
            z-index: 999;
        }
        .sidebar.collapsed {
            transform: translateX(-250px);
        }
        .sidebar-header {
            text-align: center;
            padding: 20px;
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu li {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
        }
        .sidebar-menu li:hover {
            background-color: #e9ecef;
        }
        .profile-section {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
            cursor: pointer;
        }
        .profile-icon {
            font-size: 1.5em;
            color: #0d6efd;
        }
        .chat-container {
            margin-left: 250px;
            margin-top: 60px; /* Abaixo da navbar */
            padding: 20px;
            height: calc(100vh - 160px); /* Ajustado para acomodar a navbar */
            overflow-y: auto;
            transition: margin-left 0.3s ease-in-out;
        }
        .chat-container.collapsed {
            margin-left: 0;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
        }
        .message.user {
            background-color: #e9ecef;
            text-align: right;
        }
        .message.ai {
            background-color: #f8f9fa;
            text-align: left;
        }
        .welcome-message {
            text-align: center;
            margin-top: 20%;
            font-size: 1.5em;
            color: #333;
        }
        .input-group {
            margin-top: 20px;
            position: fixed;
            bottom: 20px;
            left: 270px;
            width: calc(100% - 290px);
            transition: left 0.3s ease-in-out;
        }
        .input-group.collapsed {
            left: 20px;
            width: calc(100% - 40px);
        }
        .input-group textarea {
            resize: none;
            height: 60px;
            border-radius: 8px 0 0 8px;
        }
        .input-group button {
            border-radius: 0 8px 8px 0;
        }
        .toggle-sidebar-btn {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1001;
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-250px);
            }
            .chat-container {
                margin-left: 0;
            }
            .input-group {
                left: 20px;
                width: calc(100% - 40px);
            }
        }
    </style>
</head>
<body>

<!-- Barra de Navegação -->
<div class="navbar">
    <div class="navbar-brand">Assistente UCM</div>
</div>

<!-- Botão para alternar a barra lateral -->
<button class="toggle-sidebar-btn" onclick="toggleSidebar()">☰</button>

<!-- Barra Lateral -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">Assistente UCM</div>
    <ul class="sidebar-menu">
        <!-- Outras opções podem ser adicionadas aqui -->
    </ul>
    <div class="profile-section" onclick="openProfile()">
        <div class="profile-icon">👤</div>
        <p>Perfil</p>
    </div>
</div>



<!-- Área de Chat -->
<div class="chat-container" id="chat-container">
    <div class="welcome-message" id="welcome-message">
        Olá {{session('nome')}}! Como posso ajudar você hoje?
    </div>
</div>

<!-- Formulário de Entrada -->
<div class="input-group" id="input-group">
    <textarea class="form-control" id="user-input" placeholder="Digite sua mensagem..." aria-label="Mensagem"></textarea>
    <button class="btn btn-primary" type="button" onclick="sendMessage()">Enviar</button>
</div>

<!-- Meta CSRF para AJAX -->
 {{-- <meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Formulário e Botão de Upload -->
   <div class="input-group" id="input-group">
    <!-- Botão de Upload -->
    <label for="pdf-upload" class="btn btn-outline-primary me-2">
        📎 Carregar PDF
    </label>
    <input type="file" id="pdf-upload" name="document" accept="application/pdf" style="display: none;" onchange="uploadPdf(this)">

    <!-- Campo de Mensagem (opcional para perguntas) -->
    <textarea class="form-control" id="user-input" placeholder="Digite sua mensagem..." aria-label="Mensagem"></textarea>

    <!-- Botão Enviar (opcional) -->
    <button class="btn btn-primary" type="button" onclick="sendMessage()">Enviar</button>
</div>

<!-- Feedback visual -->
<div id="upload-status" class="mt-2 text-info" style="display:none;">Enviando PDF...</div> --}}



<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let documentoId = null;

    function uploadPdf(input) {
        const file = input.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('document', file);

        // Mostra feedback visual
        document.getElementById('upload-status').style.display = 'block';
        document.getElementById('upload-status').innerText = 'Enviando PDF...';

        fetch('/upload-pdf', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('upload-status').innerText = data.message || 'Upload completo!';
            console.log('Resposta:', data);
            if (data.documento_id) {
        documentoId = data.documento_id;  // <-- Salva o ID para usar depois
    }
        })
        .catch(error => {
            document.getElementById('upload-status').innerText = 'Erro ao enviar o PDF.';
            console.error('Erro:', error);
        });


    }
    function sendMessage() {
    const pergunta = document.getElementById("user-input").value;

    if (!documentoId) {
        alert("Por favor, envie um documento primeiro.");
        return;
    }

    fetch('documento/${documentoId}/perguntar', {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ pergunta: pergunta })
    })
    .then(response => response.json())
    .then(data => {
        console.log("Resposta da IA:", data.answer ?? data);
        alert("Resposta: " + (data.answer ?? "Sem resposta"));
    })
    .catch(error => {
        console.error("Erro ao perguntar:", error);
        alert("Erro ao perguntar sobre o documento.");
    });

    }



    // Função para rolar automaticamente para o final do chat
    function scrollToBottom() {
        const chatContainer = document.getElementById('chat-container');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Função para enviar uma mensagem
    async function sendMessage() {
        const userInput = document.getElementById('user-input');
        const message = userInput.value.trim();

        if (message === '') {
            alert('Por favor, digite uma mensagem.');
            return;
        }

        // Remove a mensagem de boas-vindas se ainda estiver visível
        const welcomeMessage = document.getElementById('welcome-message');
        if (welcomeMessage) {
            welcomeMessage.remove();
        }

        // Adiciona a mensagem do usuário ao chat
        const chatContainer = document.getElementById('chat-container');
        const userMessageDiv = document.createElement('div');
        userMessageDiv.className = 'message user';
        userMessageDiv.innerHTML = `<p><strong>Você:</strong> ${message}</p>`;
        chatContainer.appendChild(userMessageDiv);

        // Limpa o campo de entrada
        userInput.value = '';

        // Rola para o final do chat
        scrollToBottom();

        // Adiciona indicador de que a IA está digitando
        const typingIndicator = document.createElement('div');
        typingIndicator.className = 'message ai typing-indicator';
        typingIndicator.id = 'typing-indicator';
        typingIndicator.innerHTML = '<p><strong>UCM:</strong> Digitando...</p>';
        chatContainer.appendChild(typingIndicator);
        scrollToBottom();


        const csrfToken = '{{ csrf_token() }}';

        try {
            // Faz a chamada para sua API Laravel
            const response = await fetch('/chate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    message: message
                })
            });

            if (!response.ok) {
                throw new Error('Erro na resposta da API');
            }

            const data = await response.json();

            // Remove o indicador de digitação
            document.getElementById('typing-indicator').remove();

            // Adiciona a resposta da IA
            const aiMessageDiv = document.createElement('div');
            aiMessageDiv.className = 'message ai';
            aiMessageDiv.innerHTML = `<p><strong>UCM:</strong> ${data.response}</p>`;
            chatContainer.appendChild(aiMessageDiv);

        } catch (error) {
            console.error('Erro:', error);
            document.getElementById('typing-indicator').remove();

            const errorDiv = document.createElement('div');
            errorDiv.className = 'message ai';
            errorDiv.innerHTML = '<p><strong>UCM:</strong> Desculpe, ocorreu um erro ao processar sua mensagem.</p>';
            chatContainer.appendChild(errorDiv);
        }

        // Rola para o final do chat novamente
        scrollToBottom();
    }

    // Função para abrir o perfil
    function openProfile() {
        window.location.href = "{{ route('perfil') }}";
    }

    // Função para alternar a barra lateral
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const chatContainer = document.getElementById('chat-container');
        const inputGroup = document.getElementById('input-group');
        sidebar.classList.toggle('collapsed');
        chatContainer.classList.toggle('collapsed');
        inputGroup.classList.toggle('collapsed');
    }

    // Evento para enviar mensagem ao pressionar Enter (com Shift+Enter para quebra de linha)
    document.getElementById('user-input').addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function perguntarDocumento(idDocumento) {
    const pergunta = prompt("Digite sua pergunta:");

    if (!pergunta || pergunta.trim() === '') {
        alert("Pergunta vazia.");
        return;
    }

    fetch(`/documento/${idDocumento}/perguntar`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ pergunta: pergunta.trim() })
    })
    .then(response => response.json())
    .then(data => {
        alert("Resposta: " + (data.answer ?? "Sem resposta"));
    })
    .catch(error => {
        console.error("Erro ao perguntar:", error);
        alert("Erro ao perguntar sobre o documento.");
    });


}

</script>
</body>
</html>
