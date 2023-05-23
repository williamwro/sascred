<?PHP
header("Content-type: application/json");
include "../../php/banco.php";
include "../../php/funcoes.php";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$someArray = array();
$query = 'SELECT codigo, razaosocial, nomefantasia, endereco, bairro, telefone, data_cadastro, cidade, cnpj, email, contato, registro, cpf, cel,contrato FROM sascard.convenio';
$statment = $pdo->prepare($query);

$statment->execute();

$result = $statment->fetchAll();

$data = array();

$linhas_filtradas = $statment->rowCount();

foreach ($result as $row){
    $sub_array = array();

    $sub_array["codigo"]        = $row["codigo"];
    $sub_array["razaosocial"]   = htmlspecialchars($row["razaosocial"]);
    $sub_array["nomefantasia"]  = htmlspecialchars($row["nomefantasia"]);
    $sub_array["endereco"]      = htmlspecialchars($row["endereco"]);
    $sub_array["bairro"]        = htmlspecialchars($row["bairro"]);
    $sub_array["telefone"]      = $row["telefone"];
    $sub_array["data_cadastro"] = $row["data_cadastro"];
    $sub_array["cidade"]        = htmlspecialchars($row["cidade"]);
    $sub_array["cnpj"]          = $row["cnpj"];
    $sub_array["email"]         = $row["email"];
    $sub_array["contato"]       = htmlspecialchars($row["contato"]);
    $sub_array["registro"]      = $row["registro"];
    $sub_array["cpf"]           = $row["cpf"];
    $sub_array["cel"]           = $row["cel"];
    $sub_array["botaobanco"]    = '<button type="button" name="btnbanco" id="'.$row["codigo"].'&-'.$row["razaosocial"].'" class="btn btn-success glyphicon glyphicon-home btn-xs btnbanco" data-toggle="tooltip" data-placement="top" title="Banco"></button>';
    $sub_array["contrato"]      = $row["contrato"];
    $sub_array["botaover"]      = '<button type="button" name="btnvisualiza" id="'.$row["codigo"].'" class="btn btn-primary glyphicon glyphicon-eye-open btn-xs btnvisualiza" data-toggle="tooltip" data-placement="top" title="Visualizar"></button>';
    $sub_array["botao"]         = '<button type="button" name="update" id="'.$row["codigo"].'" class="btn btn-warning glyphicon glyphicon-edit btn-xs update" data-toggle="tooltip" data-placement="top" title="Alterar"></button>';
    $sub_array["botaosenha"]    = '<button type="button" name="btnsenha" id="'.$row["codigo"].'" class="btn btn-facebook glyphicon glyphicon-credit-card btn-xs btnsenha" data-toggle="tooltip" data-placement="top" title="Senha do cartão"></button>';
    $sub_array["botaocontrato"] = '<button type="button" name="btncontrato" id="'.$row["codigo"].'" class="btn btn-info glyphicon glyphicon-list-alt btn-xs btncontrato" data-toggle="tooltip" data-placement="top" title="Contrato"></button>';
    if($row["contrato"]){
        $sub_array["botaocontrato2"] = '<button type="button" name="btncontrato2" id="'.$row["codigo"].'" class="btn btn-success glyphicon glyphicon-send btn-xs btncontrato2" data-toggle="tooltip" data-placement="top" title="Enviar contrato"></button>';
    }else {
        $sub_array["botaocontrato2"] = '<button type="button" name="btncontrato2" id="' . $row["codigo"] . '" class="btn btn-danger glyphicon glyphicon-send btn-xs btncontrato2" data-toggle="tooltip" data-placement="top" title="Enviar contrato"></button>';
    }
    $someArray["data"][] = $sub_array;

}

echo json_encode($someArray);
