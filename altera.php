<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD - Controle de Funcionarios</title>
</head>

<body>

<a href="index.html">Home</a> | <a href="consulta.php">Consulta</a>
<hr>

<h2>Edição de Funcionarios</h2>

</body>
</html>

<?php

    include("conexaoBD.php");
    define('TAMANHO_MAXIMO', (2 * 1024 * 1024));

    //upload dir
    $uploaddir = 'upload/fotos/'; //directório onde será gravado a imagem

    $ra = $_POST['NIT'];
    $novoNome = $_POST['nome'];
    $novoCurso = $_POST['setor'];

    //foto
    $foto = $_FILES['foto'];
    $nomeFoto = $foto['name'];
    $tipoFoto = $foto['type'];
    $tamanhoFoto = $foto['size'];

    //gerando novo nome para a foto
    $info = new SplFileInfo($nomeFoto);
    $extensaoArq = $info->getExtension();
    $novoNomeFoto = $ra . "." . $extensaoArq;

    if ( ($nomeFoto != "") && (!preg_match('/^image\/(jpeg|png|gif)$/', $tipoFoto)) ) { 
     echo "<span id='error'>Isso não é uma imagem válida</span>";

    } else if ( ($nomeFoto != "") && ($tamanhoFoto > TAMANHO_MAXIMO) ) { //validação tamanho arquivo
        echo "<span id='error'>A imagem deve possuir no máximo 2 MB</span>";
    } else if (($nomeFoto != "") && (move_uploaded_file($_FILES['foto']['tmp_name'], $uploaddir . $novoNomeFoto))) {
        $uploadfile = $uploaddir . $novoNomeFoto; // caminho/nome da imagem

        //só altera a foto se for feito um novo upload
        $stmt = $pdo->prepare('UPDATE Funcionarios SET nome = :novoNome, setor = :novoSetor, arquivoFoto = :novaFoto WHERE NIT = :NIT');
        $stmt->bindParam(':novoNome', $novoNome);
        $stmt->bindParam(':novoSetor', $novoSetor);
        $stmt->bindParam(':novaFoto', $uploadfile);
        $stmt->bindParam(':NIT', $NIT);

    } else {
        //senão mantem a foto anterior, não fazendo update do campo arquivoFoto
        $stmt = $pdo->prepare('UPDATE Funcionarios SET nome = :novoNome, setor = :novoSetor WHERE NIT = :NIT');
        $stmt->bindParam(':novoNome', $novoNome);
        $stmt->bindParam(':novoSetor', $novoSetor);
        $stmt->bindParam(':NIT', $NIT);
    }

    try {
        $stmt->execute();

        echo "Os dados do funcionario de NIT $NIT foram alterados!";

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }

?>