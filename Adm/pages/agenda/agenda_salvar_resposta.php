<?PHP
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', true);
require '../../php/banco.php';
include "../../php/funcoes.php";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
date_default_timezone_set('America/Sao_Paulo');

$_id             = isset($_POST['C_codigo_agenda'])  ? (int)$_POST['C_codigo_agenda'] : 0;
$_resposta       = isset($_POST['C_resposta'])       ? $_POST['C_resposta']           : null;
$_cod_convenio   = isset($_POST['C_convenio'])       ? $_POST['C_convenio']           : 0;
$_finalizado     = isset($_POST['SwitchFinalizado']) ? "1"                            : "0";


$query = "SELECT codigo, 
                 razaosocial
            FROM sascard.convenio
           WHERE codigo = '".$_cod_convenio."' ";

    $statment = $pdo->prepare($query);
    $statment->execute();
    $result = $statment->fetchAll();
    $linha = array();
    foreach ($result as $row){
        $_nome_convenio = $row["razaosocial"];
    }

$data_atual      = new DateTime();
$_data_resp      = $data_atual->format('Y-m-d H:i:s');


function converte_data($date) {
    return substr($date,6,4).'-'.substr($date,3,2).'-'.substr($date,0,2).' 00:00:00';
}
$stmt = new stdClass();

$msg_grava_cad="";
if(isset($_POST["operation"])) {
    if($_POST["operation"] == "Update") {

        $sql = "UPDATE sascard.agenda SET ";
        $sql .= "data_resposta= :data_resposta, ";
        $sql .= "resposta= :resposta, ";
        $sql .= "cod_convenio= :cod_convenio, ";
        $sql .= "nome_convenio= :nome_convenio, ";
        $sql .= "finalizado= :finalizado ";
        $sql .= "WHERE id = :id";
      

        $msg_grava_cad = "atualizado";

    }
    try {

        $stmt = $pdo->prepare($sql);

        
        $stmt->bindParam(':id', $_id, PDO::PARAM_INT);
        $stmt->bindParam(':data_resposta', $_data_resp, PDO::PARAM_STR);
        $stmt->bindParam(':resposta', $_resposta, PDO::PARAM_STR);
        $stmt->bindParam(':cod_convenio', $_cod_convenio, PDO::PARAM_INT);
        $stmt->bindParam(':nome_convenio', $_nome_convenio, PDO::PARAM_STR);
        $stmt->bindParam(':finalizado', $_finalizado, PDO::PARAM_STR);  
      
        $stmt->execute();

        echo $msg_grava_cad;

    } catch (PDOException $erro) {
        if($erro->getCode() === '42501'){
            $msg_grava_cad = "Seu usuario não tem permissão!";
        }else{
            $msg_grava_cad = "Não foi possivel inserir os dados no banco: " . $erro->getMessage();
        }
        echo $msg_grava_cad;
    }
}