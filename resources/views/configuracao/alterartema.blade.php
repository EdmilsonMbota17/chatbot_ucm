<!-- resources/views/configuracao/alterar-tema.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Tema</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: {{ session('tema') === 'escuro' ? '#212529' : '#f8f9fa' }};
            color: {{ session('tema') === 'escuro' ? '#ffffff' : '#212529' }};
            transition: background-color 0.3s, color 0.3s; /* Transição suave */
        }
        .navbar {
            background-color: {{ session('tema') === 'escuro' ? '#343a40' : '#ffffff' }};
            transition: background-color 0.3s; /* Transição suave */
        }
        .theme-switcher {
            cursor: pointer;
            font-size: 1.5rem;
            color: {{ session('tema') === 'escuro' ? '#ffffff' : '#212529' }};
        }
    </style>
</head>
<body class="{{ session('modo') === 'diurno' ? 'bg-white' : 'bg-dark text-white' }}">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Alterar Tema</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('perfil') }}">Voltar ao Perfil</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5 text-center">
        <h1>Alterar Tema</h1>
        <p>Escolha entre o modo Diurno ou Noturno: {{session('modo')}}</p>

        <!-- Botão Dinâmico para Alternar Tema -->
        <div class="theme-switcher" id="themeSwitcher">
            ☀️ <!-- Ícone de sol (modo diurno) -->
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Variáveis globais
        const themeSwitcher = document.getElementById('themeSwitcher');
        const body = document.body;
        const navbar = document.querySelector('.navbar');

        // Função para aplicar o tema
        function applyTheme(isDarkMode) {
            if (isDarkMode == 'noturno') {

                themeSwitcher.innerHTML = '🌙'; // Ícone de lua (modo noturno)
                {{session(['modo' => "noturno" ])}}

            } else {

                themeSwitcher.innerHTML = '☀️'; // Ícone de sol (modo diurno)

                {{session(['modo' => "diurno" ])}}

            }
        }

        // Verificar o tema atual na sessão
        let isDarkMode = null;
        if (isDarkMode == 'diurno') {
                isDarkMode = 'noturno';

            } else {
                isDarkMode = 'diurno';
            }
        applyTheme(isDarkMode);

        // Quando o usuário clica no ícone de alternância
        themeSwitcher.addEventListener('click', () => {
            // Alterna entre os modos
            if (isDarkMode == 'diurno') {
                isDarkMode = 'noturno';

            } else {
                isDarkMode = 'diurno';
            }

            applyTheme(isDarkMode);

            // Enviar o tema escolhido para o servidor via AJAX
            fetch('{{ route('alterar.tema') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ modo: isDarkMode })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Tema alterado:', data.message);
                location.reload();
            })
            .catch(error => {
                console.error('Erro ao alterar o tema:', error);
            });
        });
    </script>
</body>
</html>
