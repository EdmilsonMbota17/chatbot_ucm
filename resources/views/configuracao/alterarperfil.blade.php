<!-- resources/views/configuracao/alterar-perfil.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Foto de Perfil</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: {{ session('tema') === 'escuro' ? '#212529' : '#f8f9fa' }};
            color: {{ session('tema') === 'escuro' ? '#ffffff' : '#212529' }};
        }
        .profile-container {
            position: relative;
            display: inline-block;
            text-align: center;
        }
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #0d6efd;
            cursor: pointer;
            transition: transform 0.3s ease-in-out;
        }
        .profile-img:hover {
            transform: scale(1.1);
        }
        .edit-icon {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: #0d6efd;
            color: white;
            border-radius: 50%;
            padding: 5px;
            font-size: 18px;
            cursor: pointer;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Alterar Foto de Perfil</h1>

        <!-- Container da Foto de Perfil -->
        <div class="profile-container text-center">
            <img src="{{ asset('storage/fotos-perfil/default.jpg') }}" alt="Foto de Perfil" id="profileImage" class="profile-img">
            <div class="edit-icon" id="editIcon">✎</div>
            <input type="file" id="photoInput" style="display: none;" accept="image/*">
        </div>

        <!-- Confirmação da Foto -->
        <div id="confirmationSection" class="hidden text-center mt-4">
            <p>Você escolheu esta foto:</p>
            <img src="" alt="Nova Foto de Perfil" id="newProfileImage" class="profile-img">
            <p class="mt-3">
                <button id="confirmPhotoBtn" class="btn btn-success">Confirmar</button>
                <button id="cancelPhotoBtn" class="btn btn-danger">Cancelar</button>
            </p>
        </div>

        <!-- Mensagem de Sucesso -->
        <div id="successMessage" class="alert alert-success hidden">Foto alterada com sucesso!</div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Variáveis globais
        const profileImage = document.getElementById('profileImage');
        const editIcon = document.getElementById('editIcon');
        const photoInput = document.getElementById('photoInput');
        const confirmationSection = document.getElementById('confirmationSection');
        const newProfileImage = document.getElementById('newProfileImage');
        const confirmPhotoBtn = document.getElementById('confirmPhotoBtn');
        const cancelPhotoBtn = document.getElementById('cancelPhotoBtn');
        const successMessage = document.getElementById('successMessage');

        let selectedFile = null; // Armazena o arquivo selecionado

        // Quando o usuário clica no ícone de edição
        editIcon.addEventListener('click', () => {
            photoInput.click(); // Abre o seletor de arquivos
        });

        // Quando um arquivo é selecionado
        photoInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                selectedFile = file; // Armazena o arquivo selecionado
                const reader = new FileReader();
                reader.onload = (e) => {
                    newProfileImage.src = e.target.result; // Atualiza a nova imagem
                    confirmationSection.classList.remove('hidden'); // Mostra a área de confirmação
                };
                reader.readAsDataURL(file);
            }
        });

        // Quando o usuário confirma a nova foto
        confirmPhotoBtn.addEventListener('click', () => {
            if (selectedFile) {
                profileImage.src = newProfileImage.src; // Atualiza a imagem principal
                confirmationSection.classList.add('hidden'); // Oculta a área de confirmação
                successMessage.classList.remove('hidden'); // Exibe mensagem de sucesso

                // Aqui você pode adicionar código para enviar a imagem ao servidor via AJAX ou formulário
            }
        });

        // Quando o usuário cancela a seleção da foto
        cancelPhotoBtn.addEventListener('click', () => {
            selectedFile = null; // Limpa o arquivo selecionado
            confirmationSection.classList.add('hidden'); // Oculta a área de confirmação
        });
    </script>
</body>
</html>
