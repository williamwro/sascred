<?PHP
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', true);
require 'Adm/php/banco.php';
include "Adm/php/funcoes.php";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
date_default_timezone_set('America/Sao_Paulo');
$_cod_associado  = isset($_POST['cod_associado'])  ? (int)$_POST['cod_associado'] : 0;
$_cod_x          = intval($_cod_associado);
$_empregador     = isset($_POST['cod_empregador']) ? (int)$_POST['cod_empregador'] : 0;
$_celuar         = isset($_POST['celular'])        ? $_POST['celular']        : "";
$_data_msg       = isset($_POST['data_msg'])       ? $_POST['data_msg']       : "";
$_mensagem       = isset($_POST['mensagem'])       ? $_POST['mensagem']       : "";
$_finalizado     = isset($_POST['finalizado'])     ? "0"                      : "1";
$_nome_associado = isset($_POST['nome_associado']) ? $_POST['nome_associado'] : "";
$_nome_empregador= isset($_POST['nome_empregador'])? $_POST['nome_empregador']: "";
$_nome_convenio  = isset($_POST['nome_convenio'])  ? $_POST['nome_convenio']  : "";
$_nome_categoria = isset($_POST['nome_categoria'])  ? $_POST['nome_categoria']  : "";

//$_data_cad       = isset($_POST['C_data_cad']) ? $_POST['C_data_cad'] : "";

$data_atual      = new DateTime();
$_data_cad       = $data_atual->format('Y-m-d H:i:s');
$_data_resp      = $data_atual->format('Y-m-d H:i:s');
$_data_resp      = isset($_POST['data_msg'])      ? $_POST['data_msg']      : $_data_resp;
$_data_resp      = isset($_POST['data_resposta']) ? $_POST['data_resposta'] : $_data_resp;

function converte_data($date) {
    return substr($date,6,4).'-'.substr($date,3,2).'-'.substr($date,0,2).' 00:00:00';
}
$stmt = new stdClass();

$msg_grava_cad="";
if(isset($_POST["operation"])) {
    if($_POST["operation"] == "Update") {

        $sql = "UPDATE sascard.agenda SET ";
        $sql .= "cod_associado = :cod_associado, ";
        $sql .= "cod_empregador = :cod_empregador, ";
        $sql .= "celular = :celular, ";
        $sql .= "data_msg = :data_msg, ";
        $sql .= "mensagem= :mensagem, ";
        $sql .= "finalizado= :finalizado, ";
        $sql .= "nome_associado= :nome_associado, ";
        $sql .= "nome_empregador= :nome_empregador, ";
        $sql .= "nome_convenio= :nome_convenio, ";
        $sql .= "nome_categoria= :nome_categoria ";
        $sql .= "WHERE id = :id";
      

        $msg_grava_cad = "atualizado";

    }elseif($_POST["operation"] == "Add") {

        $sql = "INSERT INTO sascard.agenda( ";
        $sql .= "cod_associado,cod_empregador,celular,data_msg,mensagem,";
        $sql .= "finalizado,nome_associado,nome_empregador,nome_convenio,nome_categoria) VALUES(";
        $sql .= ":cod_associado, ";
        $sql .= ":cod_empregador, ";
        $sql .= ":celular, ";
        $sql .= ":data_msg, ";
        $sql .= ":mensagem, ";
        $sql .= ":finalizado, ";
        $sql .= ":nome_associado, ";
        $sql .= ":nome_empregador, ";
        $sql .= ":nome_convenio, ";
        $sql .= ":nome_categoria)";

        $msg_grava_cad = "cadastrado";

    }
    try {

        $stmt = $pdo->prepare($sql);

        
        $stmt->bindParam(':cod_associado', $_cod_associado, PDO::PARAM_INT);
        $stmt->bindParam(':cod_empregador', $_empregador, PDO::PARAM_INT);
        $stmt->bindParam(':celular', $_celuar, PDO::PARAM_STR);
        $stmt->bindParam(':data_msg', $_data_msg, PDO::PARAM_STR);
        $stmt->bindParam(':mensagem', $_mensagem, PDO::PARAM_STR);
        $stmt->bindParam(':finalizado', $_finalizado, PDO::PARAM_STR);
        $stmt->bindParam(':nome_associado', $_nome_associado, PDO::PARAM_STR);
        $stmt->bindParam(':nome_convenio', $_nome_convenio, PDO::PARAM_STR);
        $stmt->bindParam(':nome_empregador', $_nome_empregador, PDO::PARAM_STR);
        $stmt->bindParam(':nome_categoria', $_nome_categoria, PDO::PARAM_STR);
        if($_POST["operation"] == "Update") {
            $stmt->bindParam(':id', $_cod_x, PDO::PARAM_INT);
            
        }    
      
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