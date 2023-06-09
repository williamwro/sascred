<?PHP
header("Content-type: application/json");
include "../../php/banco.php";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$tipo = $_POST['tipo'];

$total = 0;
$prolabore = 0;
$valor_prolabore = 0;
$total_liquido = 0;
if ( $tipo === "abertos" ) {
    $query = "SELECT cobranca.id,cobranca.cod_convenio,cobranca.total,cobranca.prolabore1,cobranca.prolabore2,
                     cobranca.val_pro2,cobranca.residuo,cobranca.acrescimo,cobranca.val_cob,cobranca.enviado,
                     cobranca.pago,cobranca.data_pgto,cobranca.mes,cobranca.id_categoria,convenio.razaosocial,
                     convenio.endereco,convenio.bairro,convenio.cep,convenio.cnpj,convenio.email
                FROM sascard.cobranca
          INNER JOIN sascard.convenio
                  ON cobranca.cod_convenio = convenio.codigo            
               WHERE cobranca.pago = false";
} else {
    $query = "SELECT cobranca.id,cobranca.cod_convenio,cobranca.total,cobranca.prolabore1,cobranca.prolabore2,
                     cobranca.val_pro2,cobranca.residuo,cobranca.acrescimo,cobranca.val_cob,cobranca.enviado,
                     cobranca.pago,cobranca.data_pgto,cobranca.mes,cobranca.id_categoria,convenio.razaosocial,
                     convenio.endereco,convenio.bairro,convenio.cep,convenio.cnpj,convenio.email
                FROM sascard.cobranca
          INNER JOIN sascard.convenio
                  ON cobranca.cod_convenio = convenio.codigo";
}
$someArray = array();
$statment = $pdo->query($query);
while($row = $statment->fetch()) {
    $sub_array = array();
    $sub_array["id"]              = $row["id"];
    $sub_array["cod_convenio"]    = $row["cod_convenio"];
    $sub_array["razaosocial"]     = $row["razaosocial"];
    $sub_array["email"]           = $row["email"];
    $sub_array["total"]           = $row["total"];
    $sub_array["prolabore1"]      = $row["prolabore1"];
    $sub_array["prolabore2"]      = $row["prolabore2"];
    $sub_array["acrescimo"]       = $row["acrescimo"];
    $sub_array["residuo"]         = $row["residuo"];
    $sub_array["valor_prolabore"] = $row["val_pro2"];
    $sub_array["total_cobranca"]  = $row["val_cob"];
    $sub_array["enviado"]         = $row["enviado"];
    $sub_array["pago"]            = $row["pago"];
    $sub_array["data_pgto"]       = $row["data_pgto"];
    $sub_array["mes"]             = $row["mes"];
    $someArray["data"][] = array_map("utf8_encode",$sub_array);
}
$aux = json_encode($someArray);
echo $aux;