<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Chat - Estilo IA</title>
    <!-- CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap @5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .suggestion-buttons {
            text-align: center;
            margin-top: 40px;
        }
        .suggestion-buttons button {
            margin: 10px;
            padding: 12px 20px;
            font-size: 1em;
            border-radius: 8px;
            border: none;
            background-color: #0d6efd;
            color: white;
            cursor: pointer;
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
    <div class="suggestion-buttons" id="suggestion-buttons">
        <h4>Sugestões:</h4><br>
        <button onclick="selectSuggestion('Horario de aulas e turmas')">Horario de aulas e turmas</button>
        <button onclick="selectSuggestion('Datas de Avaliações')">Datas de Avaliações</button>
        <button onclick="selectSuggestion('Datas de exames')">Datas de exames</button>
        {{-- <button onclick="selectSuggestion('Marketing Digital')">Marketing Digital</button>
        <button onclick="selectSuggestion('Finanças Pessoais')">Finanças Pessoais</button> --}}
    </div>
</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap @5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Função para rolar automaticamente para o final do chat
    function scrollToBottom() {
        const chatContainer = document.getElementById('chat-container');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Função para selecionar uma sugestão
    async function selectSuggestion(suggestion) {
        const welcomeMessage = document.getElementById('welcome-message');
        if (welcomeMessage) {
            welcomeMessage.remove();
        }

        const suggestionButtons = document.getElementById('suggestion-buttons');
        if (suggestionButtons) {
            suggestionButtons.remove();
        }

        const chatContainer = document.getElementById('chat-container');

        // Adiciona a mensagem do usuário ao chat
        const userMessageDiv = document.createElement('div');
        userMessageDiv.className = 'message user';
        userMessageDiv.innerHTML = `<p><strong>Você:</strong> ${suggestion}</p>`;
        chatContainer.appendChild(userMessageDiv);

        // Rola para o final do chat
        scrollToBottom();

        // Indicador de que a IA está digitando
        const typingIndicator = document.createElement('div');
        typingIndicator.className = 'message ai typing-indicator';
        typingIndicator.id = 'typing-indicator';
        typingIndicator.innerHTML = '<p><strong>UCM:</strong> Digitando...</p>';
        chatContainer.appendChild(typingIndicator);
        scrollToBottom();

        const csrfToken = '{{ csrf_token() }}';
        try {
            const response = await fetch('/chate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    message: suggestion
                })
            });

            if (!response.ok) throw new Error('Erro na resposta da API');

            const data = await response.json();

            // Remove indicador de digitação
            document.getElementById('typing-indicator').remove();

            // Adiciona resposta da IA
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
        sidebar.classList.toggle('collapsed');
        chatContainer.classList.toggle('collapsed');
    }
</script>
</body>
</html>
