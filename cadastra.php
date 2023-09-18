<!-- REFERÊNCIAS
  https://www.devmedia.com.br/upload-de-imagens-em-php-e-mysql/10041
  http://rafaelcouto.com.br/salvar-imagem-no-banco-de-dados-com-php-mysql/
-->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD - Controle de funcionarios</title>

    <style>
        #sucess {
            color: green;
            font-weight: bold;
        }

        #error {
            color: red;
            font-weight: bold;
        }

        #warning {
            color: orange;
            font-weight: bold;
        }

    </style>

</head>

<body>

<a href="index.html">Home</a>
<hr>

<h2>Cadastro de Funcionarios</h2>
<div>

 
    <form method="POST" enctype="multipart/form-data">

        NIT:<br>
        <input type="text" size="10" name="ra"><br><br>

        Nome:<br>
        <input type="text" size="30" name="nome"><br><br>

        Setor:<br>
        <select name="Setor">
            <option></option>
            <option value="Marketing">Marketing</option>
            <option value="Almoxerifado">Almoxerifado</option>
            <option value="RH">RH</option>
            <option value="TI">TI</option>
            <option value="Producao">Producao</option>
            <option value="Estoque">Estoque</option>
        </select><br><br>

        Foto:<br>
        <input type="file" name="foto" accept="image/*"><br><br>

        <input type="submit" value="Cadastrar">

        <hr>

    </form>
</div>

</body>
</html>

<?php

   include("conexaoBD.php");

    // Constante para o tam máximo de arquivo de foto
    define('TAMANHO_MAXIMO', (2 * 1024 * 1024));

    if ($_SERVER["REQUEST_METHOD"] === 'POST') {

        try {
            //inserindo dados
            $NIT = $_POST["NIT"];
            $nome = $_POST["nome"];
            $setor = $_POST["setor"];

            //upload dir
            $uploaddir = 'upload/fotos/'; //directório onde será gravado a imagem

            //foto
            $foto = $_FILES['foto'];
            $nomeFoto = $foto['name'];
            $tipoFoto = $foto['type'];
            $tamanhoFoto = $foto['size'];

            //gerando novo nome para a foto
            $info = new SplFileInfo($nomeFoto);
            $extensaoArq = $info->getExtension();
            $novoNomeFoto = $NIT . "." . $extensaoArq;

            if ((trim($NIT) == "") || (trim($nome) == "")) {
                echo "<span id='warning'>NIT e nome são obrigatórios!</span>";

            } else if ( ($nomeFoto != "") && (!preg_match('/^image\/(jpeg|png|gif)$/', $tipoFoto)) ) { //validção tipo arquivo
                echo "<span id='error'>Isso não é uma imagem válida</span>";

            } else if ( ($nomeFoto != "") && ($tamanhoFoto > TAMANHO_MAXIMO) ) { //validação tamanho arquivo
                echo "<span id='error'>A imagem deve possuir no máximo 2 MB</span>";

            } else {
                //verificando se o RA informado já existe no BD para não dar exception
                $stmt = $pdo->prepare("select * from funcionarios where NIT = :NIT");
                $stmt->bindParam(':NIT', $NIT);
                $stmt->execute();

                $rows = $stmt->rowCount();

                if ($rows <= 0) {

                    if (($nomeFoto != "") && (move_uploaded_file($_FILES['foto']['tmp_name'], $uploaddir . $novoNomeFoto))) {
                        $uploadfile = $uploaddir . $novoNomeFoto; // caminho/nome da imagem
                    } else {
                        $uploadfile = null;
                        echo "Sem upload de imagem.";
                    }

                    $stmt = $pdo->prepare("insert into Funcionarios (NIT, nome, setor, arquivoFoto) values(:NIT, :nome, :setor, :arquivoFoto)");
                    $stmt->bindParam(':NIT', $NIT);
                    $stmt->bindParam(':nome', $nome);
                    $stmt->bindParam(':setor', $setor);
                    $stmt->bindParam(':arquivoFoto', $uploadfile);
                    $stmt->execute();

                    echo "<span id='sucess'>Funcionario Cadastrado!</span>";
                } else {
                    echo "<span id='error'>NIT já existente!</span>";
                }
            }

        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
?>