<?PHP
header("Content-type: application/json");
include "../../php/banco.php";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$divisao = $_POST['divisao'];
$lote = $_POST['lote'];
if($lote == "aberto") {
    $sql = " WHERE c_cartaoassociado.cod_situacaocartao = 4 AND empregador.divisao = ".$divisao." AND lote isnull ORDER BY nome";
}else{
    $sql = " WHERE empregador.divisao = ".$divisao." AND lote=".$lote." ORDER BY nome";
}
$query = "SELECT c_cartaoassociado.cod_verificacao, associado.nome,
                 associado.codigo, divisao.id_divisao, empregador.abreviacao
            FROM sascard.associado
	  INNER JOIN sascard.empregador 
  			  ON associado.empregador = empregador.id 
      INNER JOIN sascard.c_cartaoassociado 
              ON associado.codigo = c_cartaoassociado.cod_associado and associado.empregador = c_cartaoassociado.empregador
      INNER JOIN sascard.divisao 
              ON empregador.divisao = divisao.id_divisao".$sql;

$someArray = array();
$statment = $pdo->prepare($query);
$statment->execute();
$result = $statment->fetchAll();
foreach ($result as $row){
    $sub_array = array();

    $sub_array["cartao"]       = $row['cod_verificacao'];
    $sub_array["codigo"]       = $row['codigo'];
    $sub_array["abreviacao"]   = $row['abreviacao'];
    $sub_array["nome"]         = $row['nome'];
    $sub_array["botaoexcluir"] = '<button type="button" name="btnexcluirCartao" id="'.$row["cod_verificacao"].'" class="btn btn-danger btn-xs btnexcluirCartao" disabled>Excluir</button>';

    $someArray["data"][] = array_map("utf8_encode",$sub_array);
}
$aux = json_encode($someArray);
echo json_encode($someArray);