<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta Funcionarios</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link" href="index.php">Cadastro</a>
                    <a class="nav-link active" href="#" aria-current="page">Consulta</a>
                    <a class="nav-link" href="alterar.php">Alterar</a>
                </div>
            </div>
        </div>
    </nav>

    <form method="POST" enctype="multipart/form-data">
        <br><br>
        <div class="container-md">
            <h1>Consulta de Funcionarios</h1>
            <br><br>
            <div class="mb-3">
                <input type="number" class="form-control" id="formGroupExampleInput" placeholder="NIT"
                    name="NIT">
                <br>
                <button class="btn btn-primary" type="submit">Consultar</button>
            </div>
            <br><br>
            <?php
            if ($_SERVER["REQUEST_METHOD"] === 'POST') {
                try {
                    $NIT = $_POST["NIT"];
                    include_once("funcoes.php");
                    consultarFuncionarios($NIT);
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
            ?>
        </div>
    </form>
</body>

</html>