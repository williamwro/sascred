<?PHP
include "../../php/banco.php";
if(isset($_POST["cod_categoria"])){
    $std = new stdClass();
    $cod_categoria = $_POST["cod_categoria"];
    $nome = $_POST["nome"];

    $query = "SELECT codigo,nome
                FROM sascard.categoriaconvenio WHERE codigo = ".$cod_categoria;
    $statment = $pdo->prepare($query);
    $statment->execute();
    $result = $statment->fetchAll();

    foreach ($result as $row){
        $std->codigo = $row["codigo"];
        $std->nome   = utf8_encode($row["nome"]);
    }
    echo json_encode($std);
}