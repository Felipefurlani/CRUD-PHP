<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD - Controle de Funcionarios
    </title>
</head>

<body>

<a href="index.html">Home</a> | <a href="consulta.php">Consulta</a>
<hr>

<h2>Edição de Funcionarios</h2>

</body>
</html>

<?php

include("conexaoBD.php");

if (!isset($_POST["NITFuncionario"])) {
    echo "Selecione o Funcionarios a ser editado!";
} else {
    $ra = $_POST["NITFuncionario"];

    try {
        $stmt = $pdo->prepare('select * from Funcionarios where NIT = :NIT');
        $stmt->bindParam(':NIT', $NIT);
        $stmt->execute();
        
        $Marketing = "";
        $Almoxerifado = "";
        $RH = "";
        $TI = "";
        $Producao = "";
        $Estoque = "";
        
        while ($row = $stmt->fetch()) {

            //para setar o curso correto no combo
            if ($row['setor'] == "Marketing") {
                $Marketing = "selected";
            } else if ($row['setor'] == "Almoxerifado") {
                $Almoxerifado = "selected";
            } else if ($row['setor'] == "RH") {
                $RH = "selected";
            } else if ($row['setor'] == "TI") {
                $TI = "selected";
            } else if ($row['setor'] == "Producao") {
                $Producao = "selected";
            } else if ($row['setor'] == "Estoque") {
                $Estoque = "selected";
            }

            $arquivoFoto = $row['arquivoFoto'];

            echo "<form method='post' action='altera.php' enctype='multipart/form-data'>\n
            NIT:<br>\n
            <input type='text' size='10' name='NIT' value='$row[NIT]' readonly><br><br>\n
            Nome:<br>\n
            <input type='text' size='30' name='nome' value='$row[nome]'><br><br>\n
            Setor:<br>\n
            <select name='setor'>\n
                <option></option>\n

                <option></option>
                <option value='Marketing'>Marketing</option>\n
                <option value='Almoxerifado'>Almoxerifado</option>\n
                <option value='RH'>RH</option>\n
                <option value='TI'>TI</option>\n
                <option value='Producao'>Producao</option>\n
                <option value='Estoque'>Estoque</option>\n
             </select><br><br>\n
             
             Foto:<br>";

            if ($arquivoFoto == null) {
              echo "-<br><br>";
            } else {
              echo  "<img src=".$row['arquivoFoto'] . " width='50px' height='50px'><br><br>";
            }

            echo "
             <input type='file' name='foto'><br><br>
             <input type='submit' value='Salvar Alterações'>\n        
             <hr>\n
            </form>";
        }

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }

}

?>