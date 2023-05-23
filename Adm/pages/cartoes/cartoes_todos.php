<?PHP
header("Content-type: application/json");
include "../../php/banco.php";
include "../../php/funcoes.php";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$divisao = $_POST["divisao"];

$query = "SELECT associado.codigo, associado.nome, c_cartaoassociado.cod_verificacao, 
	      c_cartaoassociado.cod_situacaocartao, associado.empregador, 
	      c_situacaocartao.descri AS descri_situacao, empregador.nome AS nome_empregador, 
	      divisao.nome AS nome_divisao, divisao.id_divisao, empregador.id AS id_empregador
     FROM sascard.divisao 
INNER JOIN (sascard.empregador 
INNER JOIN (sascard.c_situacaocartao
INNER JOIN (sascard.associado 
INNER JOIN sascard.c_cartaoassociado 
        ON (associado.codigo = c_cartaoassociado.cod_associado) 
       AND (associado.empregador = c_cartaoassociado.empregador)) 
        ON c_situacaocartao.id = c_cartaoassociado.cod_situacaocartao) 
        ON empregador.id = associado.empregador) ON divisao.id_divisao = empregador.divisao
       AND divisao.id_divisao=".$divisao.";";

$someArray = array();

$statment = $pdo->prepare($query);

$statment->execute();

$result = $statment->fetchAll();

$data = array();

$linhas_filtradas = $statment->rowCount();

foreach ($result as $row){
    $sub_array = array();

    $sub_array["matricula"]           = $row["codigo"];
    $sub_array["nome"]                = $row["nome"];
    $sub_array["id_empregador"]       = $row["id_empregador"];
    $sub_array["empregador"]          = $row["nome_empregador"];
    $sub_array["cod_verificacao"]     = (real)$row["cod_verificacao"];
    $sub_array["cod_situacaocartao"]  = $row["cod_situacaocartao"];
    $sub_array["descri_situacao"]     = $row["descri_situacao"];

    $someArray["data"][] = array_map("utf8_encode",$sub_array);

}
echo json_encode($someArray);