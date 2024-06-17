<?php
// Inclui o arquivo de conexão ao banco de dados
include 'conexao.php';

// cookie que expira em 15 dias
setcookie("ultimo_acesso", date("Y-m-d H:i:s"), time() + (86400 * 15), "/");

// Seleciona todos os produtos do banco de dados
$sql = "SELECT * FROM tb_produtos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Estoque</title>
    <!-- Inclusão do bootstrap e do arquivo style.css -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
  
</head>
<body>
    <?php include 'menu.php'; ?> <!-- Inclui o menu de navegação -->
    <div class="container">
        <h1 class="mb-4">Lista de Produtos</h1>
        
        <!-- Exibe a última vez que o usuário acessou o site -->
        <?php if(isset($_COOKIE["ultimo_acesso"])): ?>
            <p>Último acesso em: <?php echo $_COOKIE["ultimo_acesso"]; ?></p>
        <?php endif; ?>
        
        <!-- Tabela que lista todos os produtos -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop para exibir cada produto -->
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nome']; ?></td>
                        <td><?php echo $row['descricao']; ?></td>
                        <td><?php echo $row['preco']; ?></td>
                        <td><?php echo $row['quantidade']; ?></td>
                        <td>
                            <!-- Links para atualizar e excluir produtos -->
                            <a href="atualizar_produtos.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Atualizar</a>
                            <a href="excluir_produtos.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Arquivo JavaScript -->
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
