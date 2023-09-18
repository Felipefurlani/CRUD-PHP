<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD - Controle de Funcionarios</title>
</head>

<body>

<a href="index.html">Home</a>
<hr>

<h2>Consulta de Funcionarios</h2>
<div>
    <form method="post">

        RA:<br>
        <input type="text" size="10" name="NIT">
        <input type="submit" value="Consultar">
        <hr>
    </form>
</div>

</body>
</html>

<?php
    include("conexaoBD.php");

     if ($_SERVER["REQUEST_METHOD"] === 'POST') {

         if (isset($_POST["NIT"]) && ($_POST["NIT"] != "")) {
             $ra = $_POST["NIT"];
             $stmt = $pdo->prepare("select * from Funcionarios 
             where NIT= :NIT order by NIT, nome, setor");
             $stmt->bindParam(':ra', $ra);
         } else {
             $stmt = $pdo->prepare("select * from Funcionarios 
             order by NIT, nome, setor");
         }

         try {
             //buscando dados
             $stmt->execute();

             echo "<form method='post'><table border='1px'>";
             echo "<tr><th></th><th>NIT</th><th>Nome</th><th>Setor</th><th>Foto</th></tr>";

             while ($row = $stmt->fetch()) {
                 echo "<tr>";
                 echo "<td><input type='radio' name='NITFuncionario' 
                      value='" . $row['NIT'] . "'>";
                 echo "<td>" . $row['NIT'] . "</td>";
                 echo "<td>" . $row['nome'] . "</td>";
                 echo "<td>" . $row['setor'] . "</td>";

                 if ($row["arquivoFoto"] == null) {
                     echo "<td align='center'>-</td>";
                 } else {
                    echo "<td align='center'><img src=".$row['arquivoFoto'] . " width='50px' height='50px'></td>";
                 }
                 echo "</tr>";
             }

             echo "</table><br>
             
             <button type='submit' formaction='remove.php'>Excluir funcionario</button>
             <button type='submit' formaction='edicao.php'>Editar funcionario</button>
             
             </form>";


         } catch (PDOException $e) {
             echo 'Error: ' . $e->getMessage();
         }

     }
?>