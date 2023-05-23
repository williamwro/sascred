<?PHP
header("Content-type: application/json");
include "../../php/banco.php";
include "../../php/funcoes.php";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$mes = $_POST["mes"];
$empregador = $_POST["empregador"];
$tipo = $_POST["tipo"];

if($tipo === "0") {
    $query = "SELECT SUM(conta.valor) AS total, convenio.tipo AS tipoconvenio, tipoconvenio.categoria as codrg, conta.associado, associado.nome, tipoconvenio.nome AS nome_tipo
            FROM sascard.tipoconvenio 
      INNER JOIN (sascard.convenio 
      INNER JOIN (sascard.associado 
      INNER JOIN sascard.conta 
              ON (associado.empregador = conta.empregador) 
             AND (associado.codigo = conta.associado)) 
              ON convenio.codigo = conta.convenio) 
              ON tipoconvenio.Codigo = convenio.tipo
           WHERE conta.mes = '" . $mes . "'
             AND conta.empregador = " . $empregador . "
        GROUP BY convenio.tipo, conta.associado, associado.nome, tipoconvenio.nome, tipoconvenio.categoria ORDER BY conta.associado,tipoconvenio.categoria";
}else{
    $query = "SELECT SUM(conta.valor) AS total, convenio.tipo AS tipoconvenio, tipoconvenio.categoria as codrg, conta.associado, associado.nome, tipoconvenio.nome AS nome_tipo
            FROM sascard.tipoconvenio 
      INNER JOIN (sascard.convenio 
      INNER JOIN (sascard.associado 
      INNER JOIN sascard.conta 
              ON (associado.empregador = conta.empregador) 
             AND (associado.codigo = conta.associado)) 
              ON convenio.codigo = conta.convenio) 
              ON tipoconvenio.Codigo = convenio.tipo
           WHERE conta.mes = '" . $mes . "'
             AND conta.empregador = " . $empregador . "
             AND convenio.tipo = ". $tipo ."
        GROUP BY convenio.tipo, conta.associado, associado.nome, tipoconvenio.nome, tipoconvenio.categoria ORDER BY conta.associado,tipoconvenio.categoria";
}
$someArray = array();
$i=1;
$sql = $pdo->query($query);

while($row = $sql->fetch()) {

    $sub_array = array();
    $sub_array["associado"] = $row['associado'];
    $sub_array["nome"]      = trim($row['nome']);
    $sub_array["tipo"]      = $row['tipoconvenio'];
    $sub_array["nome_tipo"] = $row['nome_tipo'];
    $sub_array["codrg"]     = $row['codrg'];
    $sub_array["total"]     = $row['total'];

    $someArray[] = $sub_array;
}
$aux = json_encode($someArray);
echo json_encode($someArray);