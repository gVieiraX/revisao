<?php
// Inclui o arquivo de conexão ao banco de dados
include 'conexao.php';

// Verifica se foi passado um ID válido pela URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Prepara a query SQL para excluir o produto do banco de dados
    $sql = "DELETE FROM tb_produtos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // Executa a query e verifica se foi bem-sucedida
    if ($stmt->execute()) {
        // Redireciona para a página principal com mensagem de sucesso
        header("Location: index.php?excluido=true");
        exit();
    } else {
        // Redireciona para a página principal com mensagem de erro
        header("Location: index.php?excluido=false");
        exit();
    }

    // Fecha o statement
    $stmt->close();
} else {
    // Caso não seja fornecido um ID válido, redireciona para a página principal
    header("Location: index.php");
    exit();
}
?>
