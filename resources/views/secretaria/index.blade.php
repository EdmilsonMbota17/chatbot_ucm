<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Upload de Documento PDF - Secretaria</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin: 0;
            padding: 2rem;
        }
        .container {
            background: #fff;
            padding: 2rem 2.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);
            width: 100%;
            max-width: 480px;
        }
        h2 {
            margin-bottom: 1.5rem;
            color: #333;
            text-align: center;
        }
        label {
            font-weight: 600;
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }
        input[type="file"] {
            display: block;
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1.5px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: border-color 0.2s ease-in-out;
        }
        input[type="file"]:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 5px #0d6efd80;
        }
        #upload-status {
            margin-top: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.1rem;
        }
        .text-info {
            color: #0d6efd;
        }
        .text-success {
            color: #198754;
        }
        .text-danger {
            color: #dc3545;
        }
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #0d6efd;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
        .icon {
            font-size: 1.3rem;
            line-height: 1;
        }
        .icon-success {
            color: #198754;
        }
        .icon-error {
            color: #dc3545;
        }
        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }
            .container {
                padding: 1.5rem 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📤 Upload de Documento PDF</h2>

        <label for="documento">Selecione um arquivo PDF:</label>
        <input type="file" id="documento" accept="application/pdf" onchange="uploadPdf(this)" />

        <div id="upload-status" style="display:none;"></div>
    </div>

    <script>
        function uploadPdf(input) {
            const file = input.files[0];
            const uploadStatus = document.getElementById('upload-status');
            if (!file) {
                uploadStatus.style.display = 'none';
                return;
            }

            const formData = new FormData();
            formData.append('document', file);

            uploadStatus.style.display = 'flex';
            uploadStatus.className = 'text-info';
            uploadStatus.innerHTML = '<div class="spinner"></div> Enviando PDF...';

            fetch('/upload-pdf', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Erro na resposta do servidor.');
                return response.json();
            })
            .then(data => {
                uploadStatus.className = 'text-success';
                uploadStatus.innerHTML = '<span class="icon icon-success">✔️</span> ' + (data.message ?? 'Documento enviado com sucesso!');
                input.value = ''; // limpa o input para upload novo
            })
            .catch(error => {
                uploadStatus.className = 'text-danger';
                uploadStatus.innerHTML = '<span class="icon icon-error">❌</span> Falha ao enviar o documento.';
                console.error('Erro:', error);
            });
        }
    </script>
</body>
</html>



