<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
    <title>cadastro de funcionario CRUD</title>
    <style></style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link active" aria-current="page" href="#">Cadastro</a>
                    <a class="nav-link" href="consulta.php">Consulta</a>
                    <a class="nav-link" href="altera.php">Alterar</a>
                </div>
            </div>
        </div>
    </nav>
    <form method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="container-md">
            <br>
            <h1>Cadastro de Funcionario </h1>
            <br>
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" id="nome" aria-describedby="nome" name="nome">
            </div>
            <div class="mb-3">
                <label for="NIT" class="form-label">NIT:</label>
                <input type="text" class="form-control" id="NIT" name="NIT">
            </div>
   
            <div>
                <label for="setor" class="form-label">Setor:</label>
                <select class="form-select" aria-label="setor" name="setor">
                    <option selected>marketing</option>
                    <option value="1">almoxerifado</option>
                    <option value="2">RH</option>
                    <option value="3">TI</option>
                    <option value="4">Producao</option>
                    <option value="5">Estoque</option>
                    <option value="6">Outro</option>
                </select>
            </div>
            <br>
            <div>
                <label for="foto" class="form-label">Foto:</label>
                <input type="file" class="form-control" id="foto" accept="image/*" name="foto">
            </div>
            <br>
           
            <br><br>
            <input type="submit">
            <br><br>
<?php
            if ($_SERVER["REQUEST_METHOD"] === 'POST') {
                try {
                    $nome = $_POST["nome"];
                    $NIT = $_POST["NIT"];
                    $setor = $_POST["setor"];
                    $foto = $_FILES["foto"];

                    require_once("funcoes.php");
                    cadastrarfuncionario($nome, $NIT, $setor, $foto);
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
            ?>
        </div>
    </form>
</body>

</html>