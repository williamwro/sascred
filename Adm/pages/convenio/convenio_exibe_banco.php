<?PHP
include "../../php/banco.php";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(isset($_POST["cod_convenio"])){
    $std = new stdClass();
    $cod_convenio = (int)$_POST["cod_convenio"];
    $query = "SELECT banco_convenio.id, 
                     banco_convenio.cod_convenio, 
                     banco_convenio.cod_banco, 
                     banco_convenio.agencia, 
                     banco_convenio.conta, 
                     banco_convenio.cod_tipo,         
                     conta_tipo.tipo, 
                     bancos.banco,
                     convenio.razaosocial
                FROM sascard.banco_convenio
                JOIN sascard.conta_tipo ON banco_convenio.cod_tipo = conta_tipo.id
                JOIN sascard.bancos ON banco_convenio.cod_banco = bancos.id
                JOIN sascard.convenio ON banco_convenio.cod_convenio = convenio.codigo
               WHERE banco_convenio.cod_convenio = ".$cod_convenio;
    $statment_banco = $pdo->prepare($query);
    $statment_banco->execute();
    $result = $statment_banco->fetchAll();

    foreach ($result as $row){

        $std->id            = $row["id"];
        $std->cod_convenio  = $row["cod_convenio"];
        $std->cod_banco     = $row["cod_banco"];
        $std->agencia       = $row["agencia"];
        $std->conta         = $row["conta"];
        $std->cod_tipo      = $row["cod_tipo"];
        $std->tipo          = $row["tipo"];
        $std->banco         = $row["banco"];
        $std->razaosocial   = $row["razaosocial"];

    }
    echo json_encode($std);
}
