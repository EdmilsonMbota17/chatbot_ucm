<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Assistente UCM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0; padding: 0; overflow-x: hidden;
        }
        .navbar {
            display: flex; justify-content: center; align-items: center;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 10px 20px;
            z-index: 1000;
            position: fixed; top: 0; width: 100%;
        }
        .navbar-brand {
            font-weight: bold; color: #0d6efd;
        }
        .sidebar {
            width: 250px;
            background-color: #fff;
            box-shadow: 2px 0 4px rgba(0,0,0,0.1);
            position: fixed;
            top: 60px; bottom: 0; left: 0;
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
            padding: 0; margin: 0;
        }
        .sidebar-menu li {
            padding: 10px 15px;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
            font-size: 14px;
            color: #333;
        }
        .sidebar-menu li:hover,
        .sidebar-menu li.active {
            background-color: #e9ecef;
        }
        .profile-section {
            position: absolute;
            bottom: 0; width: 100%;
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
            cursor: pointer;
        }
        .profile-icon {
            font-size: 1.5em; color: #0d6efd;
        }
        .chat-container {
            margin-left: 250px;
            margin-top: 60px;
            padding: 20px;
            height: calc(100vh - 160px);
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
            max-width: 70%;
        }
        .message.user {
            background-color: #e9ecef;
            text-align: right;
            margin-left: auto;
        }
        .message.ai {
            background-color: #f8f9fa;
            text-align: left;
            margin-right: auto;
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
            top: 10px; left: 10px;
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

<!-- Navbar -->
<div class="navbar">
    <div class="navbar-brand">Assistente UCM</div>
</div>

<!-- Toggle Sidebar Button -->
<button class="toggle-sidebar-btn" onclick="toggleSidebar()">☰</button>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">Histórico</div>
    <ul class="sidebar-menu" id="historico-lista">
        <!-- Histórico carregado aqui -->
    </ul>
    <div class="profile-section" onclick="openProfile()">
        <div class="profile-icon">
            <i class="bi bi-person-circle" style="font-size: 2rem; color: #0d6efd;"></i>
        </div>
        <p>Perfil</p>
    </div>

</div>

<!-- Chat area -->
<div class="chat-container" id="chat-container">
    <div class="welcome-message" id="welcome-message">
        Olá {{ session('nome') }}! Como posso ajudar você hoje?
    </div>
</div>

<!-- Input -->
<div class="input-group" id="input-group">
    <textarea class="form-control" id="user-input" placeholder="Digite sua mensagem..." aria-label="Mensagem"></textarea>
    <button class="btn btn-primary" type="button" onclick="sendMessage()">Enviar</button>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    // Variável global para guardar histórico
    let historicoCompleto = [];

    // Carregar histórico das conversas para sidebar
    function carregarHistorico() {
        fetch('/chat/historico')
            .then(response => response.json())
            .then(data => {
                historicoCompleto = data;
                const lista = document.getElementById('historico-lista');
                lista.innerHTML = '';

                if (data.length === 0) {
                    lista.innerHTML = '<li>Nenhuma conversa ainda.</li>';
                    return;
                }

                data.forEach((conversa, index) => {
                    const li = document.createElement('li');
                    li.textContent = (conversa.user_message.length > 30 ? conversa.user_message.substring(0,30) + '...' : conversa.user_message) || 'Sem mensagem';
                    li.classList.add('historico-item');
                    li.onclick = () => mostrarConversa(index);
                    lista.appendChild(li);
                });
            })
            .catch(err => {
                console.error('Erro ao carregar histórico:', err);
            });
    }

    // Mostrar conversa selecionada no painel principal
    function mostrarConversa(index) {
        const conversa = historicoCompleto[index];
        if (!conversa) return;

        const chatContainer = document.getElementById('chat-container');
        chatContainer.innerHTML = ''; // limpa

        const userDiv = document.createElement('div');
        userDiv.className = 'message user';
        userDiv.innerHTML = `<p><strong>Você:</strong> ${conversa.user_message}</p>`;
        chatContainer.appendChild(userDiv);

        const aiDiv = document.createElement('div');
        aiDiv.className = 'message ai';
        aiDiv.innerHTML = `<p><strong>UCM:</strong> ${conversa.ai_response}</p>`;
        chatContainer.appendChild(aiDiv);

        scrollToBottom();
    }

    // Rolar para o final do chat
    function scrollToBottom() {
        const chatContainer = document.getElementById('chat-container');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Enviar mensagem nova
    async function sendMessage() {
        const userInput = document.getElementById('user-input');
        const message = userInput.value.trim();

        if (!message) {
            alert('Por favor, digite uma mensagem.');
            return;
        }

        const welcome = document.getElementById('welcome-message');
        if (welcome) welcome.remove();

        const chatContainer = document.getElementById('chat-container');

        // Mostrar mensagem do usuário
        const userDiv = document.createElement('div');
        userDiv.className = 'message user';
        userDiv.innerHTML = `<p><strong>Você:</strong> ${message}</p>`;
        chatContainer.appendChild(userDiv);

        userInput.value = '';
        scrollToBottom();

        // Indicador de "digitando..."
        const typingDiv = document.createElement('div');
        typingDiv.className = 'message ai typing-indicator';
        typingDiv.id = 'typing-indicator';
        typingDiv.innerHTML = '<p><strong>UCM:</strong> Digitando...</p>';
        chatContainer.appendChild(typingDiv);
        scrollToBottom();

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch('/chate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ message }),
            });

            if (!response.ok) throw new Error('Erro na resposta da API');

            const data = await response.json();

            // Remove indicador
            typingDiv.remove();

            // Mostrar resposta da IA
            const aiDiv = document.createElement('div');
            aiDiv.className = 'message ai';
            aiDiv.innerHTML = `<p><strong>UCM:</strong> ${data.response}</p>`;
            chatContainer.appendChild(aiDiv);

            scrollToBottom();

            // Atualizar histórico (pode recarregar ou atualizar manualmente)
            carregarHistorico();

        } catch (error) {
            console.error('Erro:', error);
            typingDiv.remove();

            const errorDiv = document.createElement('div');
            errorDiv.className = 'message ai';
            errorDiv.innerHTML = '<p><strong>UCM:</strong> Desculpe, ocorreu um erro ao processar sua mensagem.</p>';
            chatContainer.appendChild(errorDiv);
            scrollToBottom();
        }
    }

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const chatContainer = document.getElementById('chat-container');
        const inputGroup = document.getElementById('input-group');

        sidebar.classList.toggle('collapsed');
        chatContainer.classList.toggle('collapsed');
        inputGroup.classList.toggle('collapsed');
    }

    function openProfile() {
        window.location.href = "{{ route('perfil') }}";
    }

    // Enviar mensagem ao pressionar Enter (sem Shift)
    document.getElementById('user-input').addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    window.addEventListener('DOMContentLoaded', carregarHistorico);


</script>

</body>
</html>

