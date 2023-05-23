<?PHP
ini_set('display_errors', true);
error_reporting(E_ALL);
include "../../php/banco.php";
include "../../php/funcoes.php";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$someArray = array();
if($_POST['id_situacao'] == "0" ){//TODOS
    
    $query = "SELECT agenda.id, 
                 agenda.cod_associado, 
                 agenda.cod_empregador, 
                 agenda.celular, 
                 agenda.data_msg, 
                 agenda.data_resposta, 
                 agenda.mensagem, 
                 agenda.resposta, 
                 agenda.cod_convenio, 
                 agenda.finalizado, 
                 agenda.nome_associado, 
                 agenda.nome_empregador, 
                 agenda.nome_convenio,
                 agenda.nome_categoria
            FROM sascard.agenda";

}elseif($_POST['id_situacao'] == "1"){// NOVOS

    $query = "SELECT agenda.id, 
                 agenda.cod_associado, 
                 agenda.cod_empregador, 
                 agenda.celular, 
                 agenda.data_msg, 
                 agenda.data_resposta, 
                 agenda.mensagem, 
                 agenda.resposta, 
                 agenda.cod_convenio, 
                 agenda.finalizado, 
                 agenda.nome_associado, 
                 agenda.nome_empregador, 
                 agenda.nome_convenio,
                 agenda.nome_categoria
            FROM sascard.agenda
           WHERE agenda.resposta isnull or agenda.resposta = ''
             AND agenda.finalizado = false";
}elseif($_POST['id_situacao'] == "2"){// RESPONDIDOS

    $query = "SELECT agenda.id, 
                 agenda.cod_associado, 
                 agenda.cod_empregador, 
                 agenda.celular, 
                 agenda.data_msg, 
                 agenda.data_resposta, 
                 agenda.mensagem, 
                 agenda.resposta, 
                 agenda.cod_convenio, 
                 agenda.finalizado, 
                 agenda.nome_associado, 
                 agenda.nome_empregador, 
                 agenda.nome_convenio,
                 agenda.nome_categoria
            FROM sascard.agenda
       WHERE NOT agenda.resposta isnull and agenda.finalizado = false
             AND agenda.resposta <> ''  and agenda.finalizado = false";
}elseif($_POST['id_situacao'] == "3"){// FINALIZADOS

    $query = "SELECT agenda.id, 
                 agenda.cod_associado, 
                 agenda.cod_empregador, 
                 agenda.celular, 
                 agenda.data_msg, 
                 agenda.data_resposta, 
                 agenda.mensagem, 
                 agenda.resposta, 
                 agenda.cod_convenio, 
                 agenda.finalizado, 
                 agenda.nome_associado, 
                 agenda.nome_empregador, 
                 agenda.nome_convenio,
                 agenda.nome_categoria
            FROM sascard.agenda
            WHERE agenda.finalizado = true";
}
$divisao = $_POST["divisao"];

$statment = $pdo->prepare($query);

$statment->execute();

$result = $statment->fetchAll();

$data = array();

$linhas_filtradas = $statment->rowCount();

foreach ($result as $row){
    $sub_array = array();

    $sub_array["id"]                = $row["id"];
    $sub_array["cod_associado"]     = $row["cod_associado"];
    $sub_array["cod_empregador"]    = htmlspecialchars($row["cod_empregador"]);
    $sub_array["celular"]           = htmlspecialchars($row["celular"]);
    if($row["data_msg"] != null){
        $sub_array["data_msg"]      = date('d/m/Y H:i:s', strtotime($row["data_msg"]));
    }else{
        $sub_array["data_msg"]      = "";
    }
    if($row["data_resposta"] != null){
        $sub_array["data_resposta"] = date('d/m/Y H:i:s', strtotime($row["data_resposta"]));
    }else{
        $sub_array["data_resposta"] = "";
    }
    $sub_array["mensagem"]          = htmlspecialchars($row["mensagem"]);
    $sub_array["resposta"]          = $row["resposta"];
    $sub_array["cod_convenio"]      = $row["cod_convenio"];
    $sub_array["finalizado"]        = $row["finalizado"];
    $sub_array["nome_associado"]    = htmlspecialchars($row["nome_associado"]);
    $sub_array["nome_empregador"]   = htmlspecialchars($row["nome_empregador"]);
    $sub_array["nome_convenio"]     = $row["nome_convenio"];
    $sub_array["nome_categoria"]    = $row["nome_categoria"];

    $sub_array["botao"]             = '<button type="button" name="update" id="'.$row["id"].'" class="btn btn-warning glyphicon glyphicon-edit btn-xs update" data-toggle="tooltip" data-placement="top" title="Alterar"></button>';

    $someArray['data'][] = $sub_array;
}
$pp = json_encode($someArray);
echo json_encode($someArray);