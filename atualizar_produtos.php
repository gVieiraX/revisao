<?php
// Inclui o arquivo de conexão ao banco de dados
include 'conexao.php';

// Variáveis para armazenar mensagens de erro e sucesso
$mensagem = '';
$erro = false;

// Verifica se foi passado um ID válido pela URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Verifica se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Captura e sanitiza os dados do formulário
        $nome = htmlspecialchars($_POST['nome']);
        $descricao = htmlspecialchars($_POST['descricao']);
        $preco = floatval($_POST['preco']);
        $quantidade = intval($_POST['quantidade']);

        // Validação  dos dados
        if (empty($nome) || empty($descricao) || $preco <= 0 || $quantidade <= 0) {
            $mensagem = '<div class="alert alert-danger">Por favor, preencha todos os campos corretamente.</div>';
            $erro = true;
        } else {
        // SQL para atualizar um novo produto no banco de dados
        $sql = "UPDATE tb_produtos SET nome = ?, descricao = ?, preco = ?, quantidade = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdii", $nome, $descricao, $preco, $quantidade, $id);

        // Condição para verificar se o produto foi adicionado
        if ($stmt->execute()) {
                $mensagem = '<div class="alert alert-success">Produto atualizado com sucesso!</div>';
            } else {
                $mensagem = '<div class="alert alert-danger">Erro ao atualizar o produto. Por favor, tente novamente.</div>';
                $erro = true;
            }

            // Fecha o statement
            $stmt->close();
        }
    } else {
        // Query para selecionar os dados do produto pelo ID
        $sql = "SELECT * FROM tb_produtos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se encontrou o produto pelo ID
        if ($result->num_rows == 1) {
            // Obtém os dados do produto
            $row = $result->fetch_assoc();
            $nome = $row['nome'];
            $descricao = $row['descricao'];
            $preco = $row['preco'];
            $quantidade = $row['quantidade'];
        } else {
            // Caso não encontre o produto, volta para a página principal
            header("Location: index.php");
            exit();
        }

        // Fecha o statement
        $stmt->close();
    }
} else {
    // Caso não seja fornecido um ID válido, volta para a página principal
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Atualizar Produto</title>
    <!-- Inclusão do bootstrap e do arquivo style.css -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <?php include 'menu.php'; ?> <!-- Inclui o menu de navegação -->

    <div class="container">
        <h1 class="mb-4">Atualizar Produto</h1>

        <!-- Exibe mensagens de erro ou sucesso -->
        <?php echo $mensagem; ?>

        <!-- Formulário para atualizar produto -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3" required><?php echo htmlspecialchars($descricao); ?></textarea>
            </div>
            <div class="form-group">
                <label for="preco">Preço:</label>
                <input type="number" class="form-control" id="preco" name="preco" min="0.01" step="0.01" value="<?php echo htmlspecialchars($preco); ?>" required>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" class="form-control" id="quantidade" name="quantidade" min="1" value="<?php echo htmlspecialchars($quantidade); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Atualizar Produto</button>
        </form>
    </div>
    <!-- Scripts JavaScript necessários -->
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
