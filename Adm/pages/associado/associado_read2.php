<?PHP
ini_set('display_errors', true);
error_reporting(E_ALL);
include "../../php/banco.php";
include "../../php/funcoes.php";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$someArray = array();
if($_POST['id_situacao'] != "0" ){
    $tipo_sql = " AND id_situacao=".$_POST['id_situacao'];
}else{
    $tipo_sql = "";
}
$divisao = $_POST["divisao"];
$query = "SELECT associado.codigo,associado.nome,associado.endereco,associado.numero,associado.nascimento, 
                 associado.salario,associado.limite,associado.complemento,associado.id,empregador.nome AS empregador, 
                 empregador.id AS id_empregador,associado.cep,associado.telres,associado.telcom,associado.cel, 
                 associado.bairro,associado.rg,associado.cpf,funcao.nome AS funcao,empregador.abreviacao, 
                 empregador.divisao,situacao_associado.nome as nome_situacao
            FROM sascard.empregador 
      RIGHT JOIN (sascard.funcao 
      RIGHT JOIN (sascard.associado 
      RIGHT JOIN sascard.situacao_associado
              ON situacao_associado.codigo = associado.id_situacao) 
              ON funcao.id = associado.funcao) 
              ON empregador.id = associado.empregador 
           WHERE empregador.divisao = ".$divisao.$tipo_sql."
        ORDER BY associado.nome";
$statment = $pdo->prepare($query);

$statment->execute();

$result = $statment->fetchAll();

$data = array();

$linhas_filtradas = $statment->rowCount();

foreach ($result as $row){
    $sub_array = array();

    $sub_array["id"]            = $row["id"];
    $sub_array["codigo"]        = $row["codigo"];
    $sub_array["nome"]          = $row["nome"];
    $sub_array["endereco"]      = $row["endereco"];
    $sub_array["numero"]        = $row["numero"];
    if($row["nascimento"] != null){
        $sub_array["nascimento"] = date('d/m/Y', strtotime($row["nascimento"]));
    }else{
        $sub_array["nascimento"] = "";
    }
    $sub_array["salario"]       = $row["salario"];
    $sub_array["limite"]        = $row["limite"];
    $sub_array["empregador"]    = $row["empregador"];
    $sub_array["id_empregador"] = $row["id_empregador"];
    $sub_array["cep"]           = $row["cep"];
    $sub_array["cpf"]           = $row["cpf"];
    $sub_array["telres"]        = $row["telres"];
    $sub_array["telcom"]        = $row["telcom"];
    $sub_array["cel"]           = $row["cel"];
    $sub_array["complemento"]   = $row["complemento"];
    $sub_array["nome_situacao"] = $row["nome_situacao"];
    $sub_array["bairro"]        = $row["bairro"];
    $sub_array["abreviacao"]    = $row["abreviacao"];
    $sub_array["botao"]         = '<button type="button" name="update" id="'.$row["codigo"].'&'.$row["id_empregador"].'" class="btn btn-warning glyphicon glyphicon-edit btn-xs update" data-toggle="tooltip" data-placement="top" title="Alterar"></button>';
    $sub_array["botaosenha"]    = '<button type="button" name="btnsenha" id="'.$row["codigo"].'&'.$row["id_empregador"].'" class="btn btn-facebook glyphicon glyphicon-credit-card btn-xs btnsenha" data-toggle="tooltip" data-placement="top" title="Senha do cartão"></button>';
    $sub_array["botaoexcluir"]  = '<button type="button" name="btnexcluir" id="'.$row["codigo"].'&'.$row["id_empregador"].'" class="btn btn-danger glyphicon glyphicon-trash btn-xs btnexcluir" data-toggle="tooltip" data-placement="top" title="Excluir" disabled></button>';
    $sub_array["id_empregador"] = $row["id_empregador"];
    $someArray['data'][] = $sub_array;
}
$pp = json_encode($someArray);
echo json_encode($someArray);