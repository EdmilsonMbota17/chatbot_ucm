<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: {{ session('tema') === 'escuro' ? '#212529' : '#f8f9fa' }};
            color: {{ session('tema') === 'escuro' ? '#ffffff' : '#212529' }};
        }
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #0d6efd;
            margin-bottom: 1rem;
        }
        .list-group-item-action:hover {
            background-color: #0d6efd;
            color: white;
        }
        .btn-back {
            margin-bottom: 20px; /* Espaçamento abaixo do botão */
        }
    </style>
</head>
<body class="{{ session('modo') === 'diurno' ? 'bg-white' : 'bg-dark text-white' }}">
    <div class="container mt-5">
        <!-- Botão Voltar -->
        <a class="btn btn-secondary btn-back" href="{{ url('chat') }}">Voltar à Página Principal</a>

        <h1 class="text-center">Perfil</h1>

        <!-- Foto de Perfil -->
        <div class="text-center mb-4">
            <img src="{{ asset('storage/fotos-perfil/default.jpg') }}" alt="Foto de Perfil" id="profileImage" class="profile-img">
            <p class="mt-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#alterarFotoModal">
                    Alterar Foto
                </button>
            </p>
        </div>

        <!-- Menu de Configurações -->
        <div class="list-group mb-4 {{ session('modo') === 'diurno' ? 'bg-white' : 'bg-dark text-white' }}">
            <a href="{{ route('alterar.senha.form') }}" class="list-group-item list-group-item-action {{ session('modo') === 'diurno' ? 'bg-white' : 'bg-dark text-white' }}">Alterar Senha</a>
            <a href="{{ route('alterar.tema.form') }}" class="list-group-item list-group-item-action {{ session('modo') === 'diurno' ? 'bg-white' : 'bg-dark text-white' }}">Alterar Tema</a>
            {{-- <a href="{{ route('sobre.nos') }}" class="list-group-item list-group-item-action">Sobre Nós</a> --}}
        </div>

        <!-- Mensagens de Sucesso ou Erro -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    </div>

    <!-- Modal para Alterar Foto -->
    <div class="modal fade" id="alterarFotoModal" tabindex="-1" aria-labelledby="alterarFotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alterarFotoModalLabel">Alterar Foto de Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p>Selecione uma nova foto para o seu perfil:</p>
                    <input type="file" id="photoInput" accept="image/*" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="savePhotoBtn">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para abrir o perfil
        function openProfile() {
            window.location.href = "{{ route('perfil') }}"; // Redireciona para a página de perfil
        }

        // Função para voltar à página principal
        function goBack() {
            window.location.href = "{{ url('chat') }}"; // Redireciona para a página principal
        }

        // Elementos do DOM
        const profileImage = document.getElementById('profileImage');
        const photoInput = document.getElementById('photoInput');
        const savePhotoBtn = document.getElementById('savePhotoBtn');

        // Quando o botão "Salvar" é clicado, atualiza a imagem do perfil
        savePhotoBtn.addEventListener('click', () => {
            const file = photoInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    profileImage.src = e.target.result; // Atualiza a imagem
                };
                reader.readAsDataURL(file); // Lê o arquivo como uma URL de dados

                // Fecha o modal após salvar
                const modalElement = document.getElementById('alterarFotoModal');
                const modal = bootstrap.Modal.getInstance(modalElement);
                modal.hide();
            } else {
                alert('Por favor, selecione uma imagem antes de salvar.');
            }
        });
    </script>
</body>
</html>
