<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Upload de Documento PDF - Secretaria</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
        }
        .container {
            background: #fff;
            padding: 2.5rem;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 480px;
        }
        h2 {
            text-align: center;
            margin-bottom: 1rem;
            color: #333;
        }
        .info-text {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .custom-file {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        #file-label {
            font-weight: 500;
        }
        input[type="file"] {
            padding: 0.6rem;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .btn-upload {
            margin-top: 1rem;
        }
        #upload-status {
            margin-top: 1rem;
            font-weight: 500;
            font-size: 0.95rem;
            display: none;
        }
        .spinner {
            border: 3px solid #eee;
            border-top: 3px solid #0d6efd;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 0.5rem;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
  <!-- Botão Logout -->
<button type="button" class="btn btn-outline-danger btn-sm"
style="position: fixed; top: 20px; right: 20px; z-index: 1050;"
data-bs-toggle="modal" data-bs-target="#confirmLogoutModal">
<i class="bi bi-box-arrow-right"></i> Sair
</button>


<div class="container">
    <h2>📤 Upload de Documento</h2>
    <p class="info-text">Envie um arquivo PDF institucional da secretaria com no máximo 5MB.</p>

    <div class="custom-file">
        <label for="documento" id="file-label">Selecionar PDF:</label>
        <input type="file" id="documento" accept="application/pdf" />
        <button class="btn btn-primary btn-upload" onclick="uploadPdf()">Enviar</button>
    </div>

    <div id="upload-status" class="text-info" aria-live="polite"></div>
</div>

<script>
function uploadPdf() {
    const input = document.getElementById('documento');
    const file = input.files[0];
    const status = document.getElementById('upload-status');

    if (!file) {
        status.style.display = 'block';
        status.className = 'text-danger';
        status.textContent = 'Nenhum arquivo selecionado.';
        return;
    }

    if (file.type !== 'application/pdf') {
        status.style.display = 'block';
        status.className = 'text-danger';
        status.textContent = 'Somente arquivos PDF são permitidos.';
        return;
    }

    if (file.size > 5 * 1024 * 1024) {
        status.style.display = 'block';
        status.className = 'text-danger';
        status.textContent = 'O arquivo excede o limite de 5MB.';
        return;
    }

    const formData = new FormData();
    formData.append('document', file);

    status.style.display = 'block';
    status.className = 'text-info';
    status.innerHTML = '<div class="spinner"></div> Enviando documento...';

    fetch('/upload-pdf', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        status.className = 'text-success';
        status.textContent = data.message || 'Documento enviado com sucesso!';
        input.value = '';
    })
    .catch(err => {
        console.error(err);
        status.className = 'text-danger';
        status.textContent = 'Erro ao enviar o documento.';
    });
}
</script>
<!-- Modal de Confirmação de Logout -->
<div class="modal fade" id="confirmLogoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutModalLabel">Encerrar Sessão</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          Tem certeza que deseja sair?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

          <a href="{{url('/logoutsecretaria')}}" type="button" class="btn btn-danger">Sim, Sair</a>

          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>



