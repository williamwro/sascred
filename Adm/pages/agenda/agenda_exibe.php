<?PHP
include "../../php/banco.php";
include "../../php/funcoes.php";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$tem_cadastro_conta = false;
function converte_data($date) {
    return substr($date,8,2).'/'.substr($date,5,2).'/'.substr($date,0,4).' - '.substr($date,11,5);
}
if(isset($_POST["cod_agenda"])){
    $std = new stdClass();
    $cod_agenda = $_POST["cod_agenda"];

    $query = "SELECT agenda.id, 
                     agenda.resposta, 
                     agenda.data_resposta, 
                     agenda.data_msg, 
                     agenda.cod_convenio, 
                     agenda.nome_convenio,
                     agenda.nome_categoria,
                     agenda.finalizado
                FROM sascard.agenda 
               WHERE agenda.id = '".$cod_agenda."' ";

    $statment = $pdo->prepare($query);

    $statment->execute();

    $result = $statment->fetchAll();

    $linha = array();

    foreach ($result as $row){
        $std->id            = $row["id"];
        $std->resposta      = htmlspecialchars($row["resposta"]);
        if($row["data_resposta"] != null){
            $std->data_resposta = converte_data($row["data_resposta"]);
        }else{
            $std->data_resposta = "";
        }
       
        $std->cod_convenio   = $row["cod_convenio"];
        $std->nome_convenio  = $row["nome_convenio"];
        $std->nome_categoria = $row["nome_categoria"];
        $std->finalizado     = $row["finalizado"];
        $std->data_msg       = date('d/m/Y H:i:s', strtotime($row["data_msg"]));

    }
    echo json_encode($std);}