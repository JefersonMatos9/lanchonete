<?php
// Eu ativei a exibição de erros para facilitar a identificação de problemas no código.
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Eu incluí as classes que vou usar para gerenciar os produtos.
require_once '../config/DatabaseConnection.php';
require_once 'Produto.php';
require_once 'ProdutoRepository.php';

// Eu usei uma sessão para guardar informações temporárias enquanto o usuário navega no site.
session_start();
$mensagem = isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : null;
// Depois de recuperar a mensagem, eu limpo ela para não ficar repetindo.
unset($_SESSION['mensagem']);

// Aqui, eu tento recuperar o repositório de produtos que pode ter sido salvo na sessão antes.
$repository = $_SESSION['produtoRepository'];

try {
    // Eu crio uma conexão com o banco de dados para gerenciar os produtos.
    $dbConnection = new DatabaseConnection("root", "", "lanchonete");
    $repository = new ProdutoRepository($dbConnection->getConnection());

    // Quando alguém envia o formulário de adição de produto, eu verifico o método da requisição.
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
        // Eu crio um novo produto com as informações enviadas no formulário.
        $novoProduto = new Produto();
        $novoProduto->setNome($_POST['nome']);
        $novoProduto->setDescricao($_POST['descricao'] ?? 'Sem descrição');
        $novoProduto->setPreco(floatval($_POST['preco']));
        $novoProduto->setQuantidade(intval($_POST['quantidade']));
        $novoProduto->setCategoria($_POST['categoria']);

        // Eu salvo esse produto no banco usando o repositório.
        $repository->criar($novoProduto);

        // Eu guardo uma mensagem de sucesso para mostrar ao usuário depois.
        $_SESSION['mensagem'] = 'Produto adicionado com sucesso.';

        // Depois de adicionar o produto, eu recarrego a página para limpar os dados do formulário.
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
} catch (Exception $e) {
    // Se algo der errado, eu mostro o erro na tela.
    echo "Erro: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Eu ocultei o formulário inicialmente */
        #formulario-adicionar {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Gerenciamento de Produtos</h1>
    
    <!-- Aqui eu mostro uma mensagem de sucesso, se existir -->
    <?php if (isset($mensagem)): ?>
        <div class="mensagem sucesso"><?php echo $mensagem; ?></div>
    <?php endif; ?>

    <!-- Aqui é para mensagens de erro, caso sejam adicionadas no futuro -->
    <?php if (isset($erro)): ?>
        <div class="mensagem-erro"><?php echo $erro; ?></div>
    <?php endif; ?>

    <!-- Este botão é para mostrar o formulário de adição -->
    <button id="botao-mostrar-formulario">Adicionar Novo Produto</button>

    <!-- Aqui está o formulário para adicionar novos produtos -->
    <div id="formulario-adicionar" class="formulario-container">
        <h2>Adicionar Novo Produto</h2>
        <form method="POST" action="">
            <!-- Eu uso esse campo oculto para saber que a ação é de adicionar produto -->
            <input type="hidden" name="acao" value="adicionar">

            <!-- Campos do formulário para preencher os detalhes do produto -->
            <label>Nome do Produto:</label>
            <input type="text" name="nome" required placeholder="Digite o nome do produto">

            <label>Descrição:</label>
            <input type="text" name="descricao" placeholder="Descreva o produto">

            <label>Preço:</label>
            <input type="number" name="preco" step="0.01" min="0.01" required placeholder="Preço do produto">

            <label>Quantidade:</label>
            <input type="number" name="quantidade" min="0" required placeholder="Quantidade em estoque">

            <label>Categoria:</label>
            <select name="categoria" required>
                <option value="">Selecione uma categoria</option>
                <option value="Lanches">Lanches</option>
                <option value="Bebidas">Bebidas</option>
                <option value="Sobremesas">Sobremesas</option>
                <option value="Salgados">Salgados</option>
            </select>

            <!-- Botões para adicionar ou cancelar -->
            <button type="submit" class="adicionar-produto">Adicionar Produto</button>
            <button type="button" id="botao-cancelar" class="botao-cancelar">Cancelar</button>
        </form>
    </div>

    <script>
        // Eu pego os botões e o formulário para controlar o que aparece na tela.
        const botaoMostrarFormulario = document.getElementById('botao-mostrar-formulario');
        const formularioAdicionar = document.getElementById('formulario-adicionar');
        const botaoCancelar = document.getElementById('botao-cancelar');

        // Quando clico no botão, eu mostro o formulário.
        botaoMostrarFormulario.addEventListener('click', function() {
            formularioAdicionar.style.display = 'block'; //mostra o formulario
            botaoMostrarFormulario.style.display = 'none'; // esconde o botao
        });

        // E quando clico no botão de cancelar, eu escondo o formulário.
        botaoCancelar.addEventListener('click', function() {
            formularioAdicionar.style.display = 'none'; //esconde o formulario
            botaoMostrarFormulario.style.display = 'block'; //mostra o botao
        });

        // Eu gerencio a exibição de mensagens temporárias aqui.
        function gerenciarMensagens() {
            const mensagemSucesso = document.querySelector('.mensagem.sucesso');
            if (mensagemSucesso) {
                mensagemSucesso.style.display = 'block';
                setTimeout(() => {
                    mensagemSucesso.style.display = 'none';
                }, 3000); // Depois de 3 segundos, a mensagem desaparece.
            }
        }

        // Eu chamo essa função quando a página carrega.
        window.addEventListener('load', gerenciarMensagens);
    </script>

    <h2>Remover Produto</h2>
    <p>Funcionalidade em desenvolvimento. A remoção dependerá de como você deseja identificar o produto.</p>
</body>
</html>
