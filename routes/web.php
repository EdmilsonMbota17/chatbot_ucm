<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DeepseekController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PerguntaDocumentoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\SenhaController;
use App\Http\Controllers\SecretariaController;
use App\Http\Middleware\VerificarSessao;


Route::get('/chat', [ChatController::class, 'index']);


Route::get('/', [AutenticacaoController::class, 'index']);

Route::post('autenticar', [AutenticacaoController::class, 'autenticar']);



// Rota para a página de perfil/configurações
Route::get('/perfil', [ConfiguracaoController::class, 'index'])->name('perfil');
// Route::post('/senha_atualizar/{id}', [ConfiguracaoController::class, 'atualizarSenha'])->name('senha.atualizar');


// Rotas para alterar senha
Route::get('/perfil/alterar-senha', function () {
    return view('configuracao.alterarpass'); // Nome da sua view
})->name('alterar.senha.form'); // Página do formulário
Route::post('/alterar-senha', [ConfiguracaoController::class, 'alterarSenha'])->name('alterar.senha'); // Processamento



// Rotas para alterar tema
Route::get('/perfil/alterar-tema', function () {
    return view('configuracao.alterartema');
})->name('alterar.tema.form'); // Página do formulário
Route::post('/alterar-tema', [ConfiguracaoController::class, 'alterarTema'])->name('alterar.tema'); // Processamento

Route::get('/ask-deepseek', function () {
   // Em seu código:
$result = (new DeepseekController)->askDeepSeek("Explique em poucas palavras sobre o livro de Matheus");

if (isset($result['success'])) {
    echo $result['answer']; // Resposta da AI
    print_r($result['usage']); // Tokens usados
} else {
    echo "Erro: " . $result['error'];

}
});

//Rotas para update de documentos
Route::post('/documento/{id}/perguntar', [PerguntaDocumentoController::class, 'perguntar']);

Route::post('/upload-pdf', [DocumentController::class, 'store'])->name('document.upload');

Route::post('/chate', [ChatController::class, 'sendMessage']);
Route::get('/chat/history', [ChatController::class, 'getHistory']);

Route::post('/documento/{documentId}/perguntar', [DeepseekController::class, 'perguntarSobreDocumento']);
Route::apiResource('documents', DocumentController::class)->except(['update']);
Route::post('/documents/{document}/query', [DocumentController::class, 'query']);
Route::get('/documento/pesquisar', [DocumentController::class, 'search']);
Route::get('/documentos', function () {
    return view('secretaria.index');
})->name('secretaria.index');














Route::get('/chat/historico', [ChatController::class, 'historico']);







// Mostrar o formulário de cadastro



Route::get('/criar-conta', function () {
    return view('criar-conta.criarconta');
})->name('criar-conta');
Route::post('/criar-conta', [UsuarioController::class, 'registrar'])->name('criar-conta.salvar');

Route::post('/perfil', [UsuarioController::class, 'alterarSenha'])->name('perfil');


// Processar o envio do formulário de cadastro
Route::get('recuperar-senha', [SenhaController::class, 'formEmail'])->name('senha.email');
Route::post('recuperar-senha', [SenhaController::class, 'verificarEmail'])->name('senha.verificarEmail');

Route::get('responder-pergunta/{id}', [SenhaController::class, 'formPergunta'])->name('senha.pergunta');
Route::post('responder-pergunta/{id}', [SenhaController::class, 'verificarResposta'])->name('senha.verificarResposta');

Route::get('redefinir-senha/{id}', [SenhaController::class, 'mostrarFormularioRedefinicao'])->name('senha.redefinir');
Route::post('redefinir-senha/{id}', [SenhaController::class, 'atualizarSenha'])->name('senha.atualizar');
Route::get('/', function () {
    return view('autenticacao.login');
})->name('/');


Route::get('/login-secretaria', [SecretariaController::class, 'indexSecretaria'])->name('secretaria.login');

Route::post('/secretariaautentica', [SecretariaController::class, 'login'])->name('secretaria.login.post');
Route::get('/documentos', [SecretariaController::class, 'documentos'])->name('secretaria.documentos');


Route::post('/senha.atualizar/{id}', [UsuarioController::class, 'atualizarSenha'])->name('senha.atualizar');

Route::get('/recuperar-senha', function () {
    return view('recuperarsenha.email');
});
Route::post('/verificar-recuperacao', [UsuarioController::class, 'verificarRecuperacao']);

Route::get('/documents/{id}', [DocumentController::class, 'show']);

Route::post('/docente-pergunta', [DocenteController::class, 'pergunta'])->name('docente.pergunta');

Route::post('/docenteautentica', [AutenticacaoController::class, 'autenticardocente']);
Route::get('/docentelogin', [AutenticacaoController::class, 'indexdocente']);
Route::get('/chatdocente', [DocenteController::class, 'chatdocente']);

Route::get('/logoutusuario', [UsuarioController::class, 'logout'])->name('usuario.logout');

// Docente
Route::get('/logoutdocente', [DocenteController::class, 'logout'])->name('logout.docente');


// Secretaria
Route::get('/logoutsecretaria', [SecretariaController::class, 'logout'])->name('secretaria.logout');

