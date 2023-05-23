<?PHP
header("Content-type: application/json");
include "../../php/banco.php";
include "../../php/funcoes.php";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
function replace_unicode_escape_sequence($match) {
    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
}
function unicode_decode($str) {
    return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $str);
}
$divisao = $_POST["divisao"];

$query = "SELECT associado.codigo, 
                 associado.nome, 
                 associado.endereco, 
                 associado.numero, 
                 associado.nascimento,
                 associado.salario,
                 associado.limite,
                 associado.empregador AS id_empregador, 
                 associado.cep, 
                 associado.telres, 
                 associado.telcom, 
                 associado.cel, 
                 associado.bairro, 
                 associado.complemento,
                 associado.cidade,
                 associado.id_situacao,
				 empregador.divisao,
                 empregador.nome AS empregador, 
                 empregador.abreviacao
            FROM sascard.empregador RIGHT JOIN sascard.associado ON empregador.Id = associado.empregador 
           WHERE empregador.divisao = ".$divisao;

$someArray = array();

$statment = $pdo->prepare($query);
$statment->execute();
$result = $statment->fetchAll();

$linhas_filtradas = $statment->rowCount();

foreach ($result as $row){

    $sub_array = array();

    $sub_array["matricula"]       = $row["codigo"];
    $sub_array["nome"]            = utf8_encode($row["nome"]);
    $sub_array["endereco"]        = utf8_encode($row["endereco"]);
    $sub_array["numero"]          = $row["numero"];
    $sub_array["bairro"]          = utf8_encode($row["bairro"]);
    $sub_array["nascimento"]      = date('d/m/Y', strtotime($row["nascimento"]));
    $sub_array["salario"]         = (float)str_replace('.',',',$row["salario"]);
    $sub_array["limite"]          = (float)str_replace('.',',',$row["limite"]);
    $sub_array["empregador"]      = $row["empregador"];
    $sub_array["codempregador"]   = (int)$row["id_empregador"];
    $sub_array["cep"]             = $row["cep"];
    $sub_array["telres"]          = $row["telres"];
    $sub_array["telcom"]          = $row["telcom"];
    $sub_array["cel"]             = $row["cel"];
    $sub_array["complemento"]     = utf8_encode($row["complemento"]);
    $sub_array["cidade"]          = $row["cidade"];
    $sub_array["id_situacao"]     = (int)$row["id_situacao"];
    $sub_array["abreviacao"]      = $row["abreviacao"];

    $someArray["data"][] = array_map("utf8_encode",$sub_array);
}
echo json_encode($someArray);