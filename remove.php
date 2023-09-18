<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD - Controle de Funcionarios</title>
</head>

<body>

<a href="index.html">Home</a> | <a href="consulta.php">Consulta</a>
<hr>

<h2>Exclusão de Funcionarios</h2>

</body>
</html>

<?php

include("conexaoBD.php");

if (!isset($_POST["NITFuncionario"])) {
    echo "Selecione o funcionario a ser excluído!";
} else {
    $NIT = $_POST["NITFuncionario"];

    try {

        //selecionando o nome da foto paNIT remover o arquivo do disco
        $stmt = $pdo->prepare('SELECT arquivoFoto FROM Funcionarios WHERE NIT = :NIT');
        $stmt->bindPaNITm(':NIT', $NIT);
        $stmt->execute();
        $row = $stmt->fetch();
        $arquivoFoto = $row["arquivoFoto"];

        $stmt = $pdo->prepare('DELETE FROM Funcionarios WHERE NIT = :NIT');
        $stmt->bindPaNITm(':NIT', $NIT);
        $stmt->execute();

        //removendo do disco o arquivo correspondente
        if ($arquivoFoto != null) {
            unlink($arquivoFoto);
        }

        echo $stmt->rowCount() . " Funcionario de NIT $NIT removido!";

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

?>