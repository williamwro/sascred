<?PHP
include "../../php/banco.php";
include "../../php/funcoes.php";

$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$tem_cadastro_conta = false;
if(isset($_POST["cod_associado"])){
    $std = new stdClass();
    $cod_associado = $_POST["cod_associado"];
    $empregador = $_POST["empregador"];

    $sql = "SELECT conta.associado, conta.valor, empregador.abreviacao, conta.lancamento, conta.data
            FROM sascard.empregador INNER JOIN sascard.conta ON empregador.Id = conta.empregador
            INNER JOIN sascard.associado ON associado.codigo = conta.associado 
            WHERE conta.associado = '".$cod_associado."' AND empregador.id = ".$empregador.";";
    $statment = $pdo->prepare($sql);
    $statment->execute();
    $result = $statment->fetchAll();
    $tem_cadastro_conta = count($result);
    if ($tem_cadastro_conta > 0){
        $tem_cadastro_conta = true;
    }
    $query = "SELECT associado.codigo, associado.nome,associado.endereco,associado.numero,associado.nascimento,
                     associado.salario,associado.limite,associado.numero,empregador.nome AS empregador,associado.empregador AS id_empregador,
                     associado.cep,associado.telres,associado.telcom,associado.cel, associado.bairro, associado.complemento,
                     associado.cidade,associado.uf,associado.rg,associado.cpf,associado.parcelas_permitidas,associado.tipo,
                     associado.email,associado.data_filiacao,associado.data_desfiliacao,associado.id_situacao,associado.obs,associado.filiado,associado.celwatzap,funcao.nome AS funcao,funcao.id AS id_funcao,empregador.abreviacao,empregador.divisao
                FROM sascard.empregador RIGHT JOIN (sascard.funcao RIGHT JOIN sascard.associado ON funcao.id = associado.funcao) ON empregador.id = associado.empregador 
               WHERE associado.codigo = '".$cod_associado."' AND empregador.id = ".$empregador.";";
    $statment = $pdo->prepare($query);

    $statment->execute();
    $result = $statment->fetchAll();
    $salario='';
    $linha = array();
    
    foreach ($result as $row){
        $std->codigo          = $row["codigo"];
        $std->nome            = htmlspecialchars($row["nome"]);
        $std->endereco        = $row["endereco"];
        $std->numero          = $row["numero"];
        $std->nascimento      = date('d/m/Y', strtotime($row["nascimento"]));
        if (!empty($row["salario"]) && $row["salario"] <> null){ 
            $std->salario     = (float)str_replace('.',',',$row["salario"]);
        }
        if (!empty($row["limite"]) && $row["limite"] <> null){ 
            $std->limite      = (float)str_replace('.',',',$row["limite"]);
        }
        $std->empregador      = (int)$row["id_empregador"];
        $std->cep             = $row["cep"];
        $std->telres          = $row["telres"];
        $std->telcom          = $row["telcom"];
        $std->cel             = $row["cel"];
        $std->bairro          = $row["bairro"];
        $std->complemento     = $row["complemento"];
        $std->cidade          = $row["cidade"];
        $std->uf              = $row["uf"];
        $std->rg              = $row["rg"];
        $std->cpf             = $row["cpf"];
        $std->funcao          = $row['funcao'];
        $std->codfuncao          = (int)$row['id_funcao'];
        if($row['filiado'] == true){
            $std->filiado = true;//checked
        }else{
            $std->filiado = false;//Unchecked
        }
        if($row['celwatzap'] == true){
            $std->celwatzap = true;//checked
        }else{
            $std->celwatzap = false;//Unchecked
        }
        $std->obs             =  $row["obs"];
        $std->id_situacao     = (int)$row["id_situacao"];
        $std->data_filiacao   = date('d/m/Y', strtotime($row["data_filiacao"]));
        if ($row["data_desfiliacao"] != null){
            $std->data_desfiliacao = date('d/m/Y', strtotime($row["data_desfiliacao"]));
        }else{
            $std->data_desfiliacao = null;
        }
        $std->email           = $row["email"];
        $std->tipo            = (int)$row["tipo"];
        $std->parcelas_permitidas = (int)$row["parcelas_permitidas"];
        $std->tem_cadastro_conta = $tem_cadastro_conta;
    }
    echo json_encode($std);}