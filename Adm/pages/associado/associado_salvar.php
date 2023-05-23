<?PHP
error_reporting(E_ALL ^ E_NOTICE);
require '../../php/banco.php';
include "../../php/funcoes.php";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$_matricula         = isset($_POST['C_matricula']) ? $_POST['C_matricula'] : "";
$_matriculax        = isset($_POST['C_matricula_original']) ? $_POST['C_matricula_original'] : "";
$_nome              = isset($_POST['C_nome']) ? strtoupper(htmlspecialchars($_POST['C_nome'])) : "";
$_endereco          = isset($_POST['C_endereco']) ? strtoupper(htmlspecialchars($_POST['C_endereco'])) : "";
$_numero            = isset($_POST['C_numero']) ? $_POST['C_numero'] : "";
$_nascimento        = isset($_POST['C_nascimento']) ? converte_data($_POST['C_nascimento']) : null;
if(isset($_POST['C_salario'])){
    if($_POST['C_salario'] != ''){
        $_salario = $_POST['C_salario'];
        $_salario = str_replace(".","",$_salario);
        $_salario = str_replace(",",".",$_salario);
    }else{
        $_salario = 0;
    }
}else{
    $_salario = 0;
}
if(isset($_POST['C_limite'])){
    if($_POST['C_limite'] != ''){
        $_limite = $_POST['C_limite'];
        $_limite = str_replace(".","",$_limite);
        $_limite = str_replace(",",".",$_limite);
    }else{
        $_limite = 0;
    }
}else{
    $_limite = 0;
}
$_empregador_novo        = isset($_POST['C_empregador']) ? (int)$_POST['C_empregador'] : 0;
$_empregador_original   = isset($_POST['C_empregador_original']) ? (int)$_POST['C_empregador_original'] : 0;
if($_empregador_novo <> $_empregador_original){
    $_empregador = $_empregador_novo;
}else{
    $_empregador = $_empregador_original;
}
$_cep               = isset($_POST['C_cep']) ? str_replace(".", "", $_POST['C_cep']) : "";
$_telres            = isset($_POST['C_telres']) ? $_POST['C_telres'] : "";
$_telcom            = isset($_POST['C_telcom']) ? $_POST['C_telcom'] : "";
$_cel               = isset($_POST['C_cel']) ? $_POST['C_cel'] : "";
$_bairro            = isset($_POST['C_bairro']) ? strtoupper(htmlspecialchars($_POST['C_bairro'])) : "";
$_complemento       = isset($_POST['C_complemento']) ? strtoupper(htmlspecialchars($_POST['C_complemento'])) : "";
$_cidade            = isset($_POST['C_cidade']) ? strtoupper(htmlspecialchars($_POST['C_cidade'])) : "";
$_uf                = isset($_POST['C_uf']) ? $_POST['C_uf'] : "";
$_tipo_novo         = isset($_POST['C_tipo']) ? (int)$_POST['C_tipo'] : (int)1; //1-EFETIVO,2-CONTRATADO
$_tipo_original     = isset($_POST['C_tipo_original']) ? (int)$_POST['C_tipo_original'] : (int)1; //1-EFETIVO,2-CONTRATADO
if($_tipo_novo <> $_tipo_original){
    $_tipo = $_tipo_novo;
}else{
    $_tipo = $_tipo_original;
}
$_rg                = isset($_POST['C_rg']) ? $_POST['C_rg'] : "";
$_cpf               = isset($_POST['C_cpf']) ? str_replace(".", "", $_POST['C_cpf']) : "";
$_cpf               = str_replace("-", "", $_cpf);
$_funcao_novo       = isset($_POST['C_funcao']) ? (int)$_POST['C_funcao'] : (int)0;
$_funcao_original   = isset($_POST['C_funcao_original']) ? (int)$_POST['C_funcao_original'] : (int)0;
if($_funcao_novo <> $_funcao_original){
    $_funcao = $_funcao_novo;
}else{
    $_funcao = $_funcao_original;
}
$_obs               = isset($_POST['C_obs']) ? strtoupper($_POST['C_obs']) : "";
$_id_situacao_novo       = isset($_POST['C_situacao']) ? (int)$_POST['C_situacao'] : (int)1;//1-ATIVO,2-EXONERADO,3-FALECIDO
$_id_situacao_original   = isset($_POST['C_situacao_original']) ? (int)$_POST['C_situacao_original'] : (int)1;//1-ATIVO,2-EXONERADO,3-FALECIDO
if($_id_situacao_novo <> $_id_situacao_original){
    $_id_situacao = $_id_situacao_novo;
}else{
    $_id_situacao = $_id_situacao_original;
}
$_email             = isset($_POST['C_Email']) ? strtolower($_POST['C_Email']) : "";
$_data_filiacao     = isset($_POST['C_datacadastro']) ? converte_data($_POST['C_datacadastro']) : null;
if (isset($_POST['C_datadesfiliacao'])){
    if ($_POST['C_datadesfiliacao'] != "") {
        $_data_desfiliacao = converte_data($_POST['C_datadesfiliacao']);
    }else{
        $_data_desfiliacao = null;
    }
}else{
    $_data_desfiliacao = null;
}
$_filiado = isset($_POST['C_filiado']) ? (int)1 : (int)0;
$_celwatzap = isset($_POST['SwitchCelular']) ? (int)1 : (int)0;
if(isset($_POST['C_parcelas_permitidas'])){
    if($_POST['C_parcelas_permitidas'] == ""){
        $_parcelas_permitidas = 0;
    }else{
        $_parcelas_permitidas = (int)$_POST['C_parcelas_permitidas'];
    }
}else{
    $_parcelas_permitidas = 0;
}
function converte_data($date) {
    return substr($date,6,4).'-'.substr($date,3,2).'-'.substr($date,0,2).' 00:00:00';
}
$stmt = new stdClass();

$msg_grava_cad="";
if(isset($_POST["operation"])) {
    if($_POST["operation"] == "Update") {

        $sql = "UPDATE sascard.associado SET ";
        $sql .= "nome = :nome, ";
        $sql .= "endereco = :endereco, ";
        $sql .= "numero = :numero, ";
        $sql .= "nascimento = :nascimento, ";
        $sql .= "salario = :salario, ";
        $sql .= "limite = :limite, ";
        $sql .= "empregador = :empregador, ";
        $sql .= "cep = :cep, ";
        $sql .= "telres = :telres, ";
        $sql .= "telcom = :telcom, ";
        $sql .= "cel = :cel, ";
        $sql .= "bairro = :bairro, ";
        $sql .= "complemento = :complemento, ";
        $sql .= "cidade = :cidade, ";
        $sql .= "uf = :uf, ";
        $sql .= "rg = :rg, ";
        $sql .= "cpf = :cpf, ";
        $sql .= "funcao = :funcao, ";
        $sql .= "filiado = :filiado, ";
        $sql .= "celwatzap = :celwatzap, ";
        $sql .= "obs = :obs, ";
        $sql .= "id_situacao = :id_situacao, ";
        $sql .= "data_filiacao = :data_filiacao, ";
        $sql .= "data_desfiliacao = :data_desfiliacao, ";
        $sql .= "email = :email, ";
        $sql .= "tipo = :tipo, ";
        $sql .= "parcelas_permitidas = :parcelas_permitidas ";
        $sql .= "WHERE codigo = '" . $_matriculax ."' ";
        $sql .= "AND empregador = " . $_empregador_original ."";

        $msg_grava_cad = "atualizado";

    }elseif($_POST["operation"] == "Add") {

        $sql = "INSERT INTO sascard.associado( ";
        $sql .= "codigo,nome,endereco,numero,nascimento,salario,limite,empregador,bairro,cidade,uf,cep,telres,telcom,cel,complemento, ";
        $sql .= "rg,cpf,funcao,filiado,obs,id_situacao,data_filiacao,data_desfiliacao,email,tipo,parcelas_permitidas,celwatzap) VALUES( ";
        $sql .= ":codigo, ";
        $sql .= ":nome, ";
        $sql .= ":endereco, ";
        $sql .= ":numero, ";
        $sql .= ":nascimento, ";
        $sql .= ":salario, ";
        $sql .= ":limite, ";
        $sql .= ":empregador, ";
        $sql .= ":bairro, ";
        $sql .= ":cidade, ";
        $sql .= ":uf, ";
        $sql .= ":cep, ";
        $sql .= ":telres, ";
        $sql .= ":telcom, ";
        $sql .= ":cel, ";
        $sql .= ":complemento, ";
        $sql .= ":rg, ";
        $sql .= ":cpf, ";
        $sql .= ":funcao, ";
        $sql .= ":filiado, ";
        $sql .= ":obs, ";
        $sql .= ":id_situacao, ";
        $sql .= ":data_filiacao, ";
        $sql .= ":data_desfiliacao, ";
        $sql .= ":email, ";
        $sql .= ":tipo, ";
        $sql .= ":parcelas_permitidas, ";
        $sql .= ":celwatzap)";

        $msg_grava_cad = "cadastrado";

    }
    try {

        $stmt = $pdo->prepare($sql);

        if($_POST['operation'] == 'Add') {
            $stmt->bindParam(':codigo', $_matricula, PDO::PARAM_STR);
        }
        $stmt->bindParam(':nome', $_nome, PDO::PARAM_STR);
        $stmt->bindParam(':endereco', $_endereco, PDO::PARAM_STR);
        $stmt->bindParam(':bairro', $_bairro, PDO::PARAM_STR);
        $stmt->bindParam(':numero', $_numero, PDO::PARAM_STR);
        $stmt->bindParam(':nascimento', $_nascimento, PDO::PARAM_STR);
        $stmt->bindParam(':salario', $_salario, PDO::PARAM_STR);
        $stmt->bindParam(':limite', $_limite, PDO::PARAM_STR);
        $stmt->bindParam(':empregador', $_empregador, PDO::PARAM_INT);
        $stmt->bindParam(':cidade', $_cidade, PDO::PARAM_STR);
        $stmt->bindParam(':uf', $_uf, PDO::PARAM_STR);
        $stmt->bindParam(':cep', $_cep, PDO::PARAM_STR);
        $stmt->bindParam(':telres', $_telres, PDO::PARAM_STR);
        $stmt->bindParam(':telcom', $_telcom, PDO::PARAM_STR);
        $stmt->bindParam(':cel', $_cel, PDO::PARAM_STR);
        $stmt->bindParam(':bairro', $_bairro, PDO::PARAM_STR);
        $stmt->bindParam(':complemento', $_complemento, PDO::PARAM_STR);
        $stmt->bindParam(':rg', $_rg, PDO::PARAM_STR);
        $stmt->bindParam(':cpf', $_cpf, PDO::PARAM_STR);
        $stmt->bindParam(':funcao', $_funcao, PDO::PARAM_INT);
        $stmt->bindParam(':filiado', $_filiado, PDO::PARAM_INT);
        $stmt->bindParam(':obs', $_obs, PDO::PARAM_STR);
        $stmt->bindParam(':id_situacao', $_id_situacao, PDO::PARAM_INT);
        $stmt->bindParam(':data_filiacao', $_data_filiacao, PDO::PARAM_STR);
        $stmt->bindParam(':data_desfiliacao', $_data_desfiliacao, PDO::PARAM_STR);
        $stmt->bindParam(':email', $_email, PDO::PARAM_STR);
        $stmt->bindParam(':tipo', $_tipo, PDO::PARAM_INT);
        $stmt->bindParam(':parcelas_permitidas', $_parcelas_permitidas, PDO::PARAM_INT);
        $stmt->bindParam(':celwatzap', $_celwatzap, PDO::PARAM_INT);

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