<?php
function selectFuncionario($NIT)
{
    include("conexaoBD.php");

    $stmt = $pdo->prepare("select * from Funcionarios where NIT = :NIT");
    $stmt->bindValue(':NIT',);
    $stmt->execute();

    $rows = $stmt->rowCount();

    if ($rows > 0) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    } else {
        return false;
    }
}

function selectNomeFuncio($nome)
{
    include("conexaoBD.php");

    $stmt = $pdo->prepare("select * from Funcionarios where nome like :nome");
    $stmt->bindValue(':nome', $nome);
    $stmt->execute();

    $rows = $stmt->rowCount();

    if ($rows > 0) {
        $result = $stmt->fetch();
        return $result;
    } else {
        return false;
    }
}

function cadastrarfuncionario($nome, $NIT, $setor, $foto)
{
    session_start();
    include("conexaoBD.php");

    // Constante para o tam máximo de arquivo de foto
    define('TAMANHO_MAXIMO', (2 * 1024 * 1024));

    $upload_dir = 'img/';

    $nome_foto = $foto['name'];
    $tipo_foto = $foto['type'];
    $tamanho_foto = $foto['size'];

    $info = new SplFileInfo($nome_foto);
    $extensao_arq = $info->getExtension();
    $novo_nome_foto = $nome . "." . $extensao_arq;

    if (trim($nome) == "" || trim($NIT) == "" || trim($setor, ) == "") {
        echo "<div class='alert alert-danger' role='alert'>Todos os campos são obrigatórios ! </div>";
    } else if (($nome_foto != "") && (!preg_match('/^image\/(jpeg|png|gif)$/', $tipo_foto))) {
        echo "<div class='alert alert-danger' role='alert'>Imagem inválida ! </div>";
    } else if (($nome_foto != "") && ($tamanho_foto > TAMANHO_MAXIMO)) {
        echo "<div class='alert alert-danger' role='alert'>A imagem deve ser menor que 2MB ! </div>";
    } else {
        if ($nome_foto != "") {
            if (move_uploaded_file($foto['tmp_name'], $upload_dir . $novo_nome_foto)) {
                $stmt = $pdo->prepare("select * from Funcionarios where nome = :nome");
                $stmt->bindValue(':nome', $nome);
                $stmt->execute();

                $rows = $stmt->rowCount();

                if ($rows <= 0) {
                    $sql = "INSERT INTO Funcionarios (nome, NIT, setor, to) VALUES (:nome, :NIT, :setor, :foto)";

                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':nome', $nome);
                    $stmt->bindValue(':NIT', $NIT);
                    $stmt->bindValue(':setor', $setor);
                    $stmt->bindValue(':foto', $novo_nome_foto);

                    $stmt->execute();



                    echo "<div class='alert alert-success' role='alert'>funcionario cadastrada com sucesso ! </div>";

                    if (!$stmt) {
                        die('Erro ao cadastrar funcionario');
                    }
                } else {
                    echo "<div class='alert alert-danger' role='alert'>Erro ao cadastrar funcionario! </div>";
                }
                echo "<div class='alert alert-success' role='alert'>Imagem enviada com sucesso !</div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>Erro ao enviar imagem !</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Erro ao enviar imagem !</div>";
        }
    }
    $pdo = null;
}

function consultarfuncionarioes($NIT)
{
    $acao = true; 
    include("conexaoBD.php");

    if ($_POST["NIT"] != "") {
        $stmt = $pdo->prepare("select * from Funcionarios where NIT= :NIT order by nome");
        $stmt->bindParam(':NIT', $NIT);
    } else {
        $stmt = $pdo->prepare("select * from Funcionarios order by nome");
    }

    try {
        $stmt->execute();
        $acao = false;
        echo "<form method='post'>";
        echo "<table class='table table-bordered'>";
        echo "<tr class='table table-bordered'>";
        echo "<th class='table table-bordered'>Nome</th>";
        echo "<th class='table table-bordered'>NIT</th>";
        echo "<th class='table table-bordered'>setor</th>";
        echo "<th class='table table-bordered'>Foto</th>";
        echo "</tr>";

        if ($stmt->rowCount() == 0) {
            echo "<div class='alert alert-danger' role='alert'>Não foi possível encontrar um funcionario! </div>";
        } else {

            while ($linha = $stmt->fetch()) {
                $idfuncionario = $linha["NIT"];
                echo "<tr class='table table-bordered'>";
                echo "<td class='table table-bordered'> <span style='padding:10px;'>" . $linha["nome"] . "</span><button type='submit' class='btn btn-danger' name='acaoDeletar' value='" . $idfuncionario . "'>Excluir funcionario</button><button type='submit' class='btn btn-warning' name='acaoAlterar' value='" . $idfuncionario . "' style='margin:10px'>Alterar Dados</button></td>";
                echo "<td class='table table-bordered'>" . $linha["NIT"] . "</td>";
                echo "<td class='table table-bordered'>" . $linha["setor"] . "</td>";
                echo "<td class='table table-bordered'><img src='img/" . $linha["foto"] . "' width='100px' height='100px'></td>";
                echo "</tr>";
            }

            echo "</table>";
            echo "<br><br>";
            if ($_SERVER["REQUEST_METHOD"] === 'POST') {
                if (isset($_POST["acaoDeletar"]) && !$acao) {
                    $acao = true;
                    deletarfuncionario($_POST["acaoDeletar"]);
                } else if (isset($_POST["acaoAlterar"]) && !$acao) {
                    $acao = true;
                    $funcionarioAlterar = selectfuncionario($_POST["acaoAlterar"]);
                    $_POST["funcionarioAlterarNIT"] = $funcionarioAlterar["NIT"];
                    $_POST["funcionarioAlterarnome"] = $funcionarioAlterar["nome"];
                    $_POST["funcionarioAlterarsetor"] = $funcionarioAlterar["setor"];

                    header("Location: alterar.php");
                }
            }
            echo "</form>";
        }

    } catch (PDOException $e) {
        echo "<div class='alert alert-danger' role='alert'>Não foi possível encontrar um funcionario! ❌</div>";
        die("Erro: " . $e->getMessage());
    }
    $pdo = null;
}

function deletarfuncionario($NIT)
{
    include("conexaoBD.php");

    // selecionar nome da foto na coluna foto cujo NIT é igual ao NIT da funcionario
    $stmt = $pdo->prepare("select foto from Funcionarios where NIT = :NIT");
    $stmt->bindValue(':NIT',);
    $nomeFoto = $stmt->execute();
    $row = $stmt->fetch();
    $arquivoFoto = $row["foto"];

    unlink("img/" . $arquivoFoto);

    // deletar funcionario do banco de dados
    $stmt = $pdo->prepare("delete from Funcionarios where NIT = :NIT");
    $stmt->bindValue(':NIT',);
    $stmt->execute();

    echo "<div class='alert alert-success' role='alert'>funcionario deletado com sucesso ! 💚</div>";

    $pdo = null;
}
function alterarfuncionario($funcionario)
{
    try {

        include("conexaoBD.php");

        $stmt = $pdo->prepare("update Funcionarios set NIT = :NIT, peso = :peso, setor,=e");
        $stmt->bindValue(':nome', $funcionario['nome']);
        $stmt->bindValue(':NIT', $funcionario['NIT']);
        $stmt->bindValue(':setor', $funcionario['peso']);
      
      

    } catch (PDOException $e) {
      
        echo "Erro ao atualizar a funcionario: " . $e->getMessage();
    } catch (Exception $e) {
       
        echo "Erro genérico ao atualizar a funcionario: " . $e->getMessage();
    }
}