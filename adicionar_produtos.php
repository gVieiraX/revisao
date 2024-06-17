<?php
// Inclui o arquivo de conexão ao banco de dados
include 'conexao.php';

// Variáveis para armazenar mensagens de erro e sucesso
$mensagem = '';
$erro = false;

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura e sanitiza os dados do formulário
    $nome = htmlspecialchars($_POST['nome']);
    $descricao = htmlspecialchars($_POST['descricao']);
    $preco = floatval($_POST['preco']);
    $quantidade = intval($_POST['quantidade']);

    // Validação dos dados
    if (empty($nome) || empty($descricao) || $preco <= 0 || $quantidade <= 0) {
        $mensagem = '<div class="alert alert-danger">Por favor, preencha todos os campos corretamente.</div>';
        $erro = true;
    } else {
        // SQL para inserir um novo produto no banco de dados
        $sql = "INSERT INTO tb_produtos (nome, descricao, preco, quantidade) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdi", $nome, $descricao, $preco, $quantidade);

        // Condição para verificar se o produto foi adicionado
        if ($stmt->execute()) {
            $mensagem = '<div class="alert alert-success">Produto adicionado com sucesso!</div>';
        } else {
            $mensagem = '<div class="alert alert-danger">Erro ao adicionar o produto. Por favor, tente novamente.</div>';
            $erro = true;
        }

        // Fecha o statement
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Produto</title>
    <!-- Inclusão do bootstrap e do arquivo style.css -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <?php include 'menu.php'; ?> <!-- Inclui o menu de navegação -->

    <div class="container">
        <h1 class="mb-4">Adicionar Produto</h1>

        <!-- Exibe mensagens de erro ou sucesso -->
        <?php echo $mensagem; ?>

        <!-- Formulário para adicionar novo produto -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
            </div>
            <div class="form-group">
            <label for="preco">Preço (R$):</label>
                <input type="number" class="form-control" id="preco" name="preco" min="0.01" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" class="form-control" id="quantidade" name="quantidade" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Produto</button>
        </form>
    </div>

    <!-- Arquivo JavaScript -->
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
