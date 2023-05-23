<?PHP
header("Content-type: application/json");
include "../../php/banco.php";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$divisao = $_POST['divisao'];
$lote = $_POST['lote'];
$query = "SELECT c_cartaoassociado.cod_verificacao, associado.nome,
                 associado.codigo, divisao.id_divisao, empregador.nome as empregador
            FROM sascard.associado
      INNER JOIN sascard.c_cartaoassociado 
              ON associado.codigo = c_cartaoassociado.cod_associado
      INNER JOIN sascard.empregador 
              ON associado.empregador = empregador.id
      INNER JOIN sascard.divisao
              ON empregador.divisao = divisao.id_divisao
           WHERE c_cartaoassociado.cod_situacaocartao = 2 
             AND empregador.divisao = ".$divisao."
        ORDER BY nome";

$someArray = array();
$statment = $pdo->prepare($query);
$statment->execute();
$result = $statment->fetchAll();
foreach ($result as $row){
    $sub_array = array();

    $sub_array["cartao"]       = $row['cod_verificacao'];
    $sub_array["nome"]         = $row['nome'];
    $sub_array["empregador"]   = $row['empregador'];
    $sub_array["botaoexcluir"] = '<a href="#btnDesbloquear" id="btnDesbloquear" class="badge badge-danger btnDesbloquear" style="background: #ee2200";">Bloqueado</a>';

    $someArray["data"][] = array_map("utf8_encode",$sub_array);
}
$aux = json_encode($someArray);
echo json_encode($someArray);