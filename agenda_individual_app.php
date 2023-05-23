<?PHP
include 'Adm/php/banco.php';
include "Adm/php/funcoes.php";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(isset($_POST['cod_associado']) AND isset($_POST['cod_empregador'])){
    $cod_associado = $_POST['cod_associado'];
    $cod_empregador = $_POST['cod_empregador'];
}
//$mes_atual = 'OUT/2017';
//$cod_convenio = 99;

$item  = 0;
$total = 0;
$someArray = array();
$query = "SELECT id, 
                 cod_associado, 
                 cod_empregador, 
                 celular, 
                 data_msg, 
                 data_resposta, 
                 mensagem, 
                 resposta, 
                 cod_convenio, 
                 finalizado, 
                 nome_associado, 
                 nome_empregador, 
                 nome_convenio, 
                 nome_categoria
            FROM sascard.agenda
           WHERE agenda.cod_associado = " . $cod_associado . " AND
                 agenda.cod_empregador = " . $cod_empregador . " AND
                 agenda.finalizado is false";

$statment = $pdo->prepare($query);
$statment->execute();
$result = $statment->fetchAll();
foreach ($result as $row) {
    $sub_array = array();

    $sub_array["id"]              = $row['id'];
    $sub_array["cod_associado"]   = $row['cod_associado'];
    $sub_array["cod_empregador"]  = $row['cod_empregador'];
    $sub_array["celular"]         = $row['celular'];
    $sub_array["data_msg"]        = date('d/m/Y - H:i', strtotime($row["data_msg"]));
    $sub_array["data_resposta"]   = date('d/m/Y - H:i', strtotime($row["data_resposta"]));
    $sub_array["mensagem"]        = $row['mensagem'];
    $sub_array["resposta"]        = $row['resposta'];
    $sub_array["cod_convenio"]    = $row['cod_convenio'];
    $sub_array["finalizado"]      = $row['finalizado'];
    $sub_array["nome_associado"]  = $row['nome_associado'];
    $sub_array["nome_empregador"] = $row['nome_empregador'];
    $sub_array["nome_convenio"]   = $row['nome_convenio'];
    $sub_array["nome_categoria"]  = $row['nome_categoria'];
    
    $someArray[] = array_map("utf8_encode", $sub_array);
}
echo json_encode($someArray);
