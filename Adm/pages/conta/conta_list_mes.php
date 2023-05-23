<?PHP
include "../../php/banco.php";
include "../../php/funcoes.php";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$someArray = array();
$sub_arrayli = array();
$tem_cadastro_conta = false;
$ultimo_codigo = '';
$mes = '';
$controle_loop = 0;
if(isset($_POST["mes"])) {
    $std = new stdClass();
    $mes = $_POST["mes"];
    $matricula = $_POST["matricula"];
    $codempregador = $_POST["codempregador"];
    if ($mes == 'todos') {
        //$sqlmes = "conta.mes NOT IN(SELECT mes FROM sascard.controle) AND "; //NAO MOSTRAS OS MESES BLOQUEADOS
        $sqlmes = "";
    } else {
        $sqlmes = "conta.mes = '" . $mes . "' AND ";
    }
    /*BUSCA limite associado*/
    $sql_lim_saldo = "SELECT codigo,limite,empregador FROM sascard.associado WHERE codigo = '" . $matricula . "' AND empregador = " . $codempregador . ";";
    $statments = $pdo->prepare($sql_lim_saldo);
    $statments->execute();
    $results = $statments->fetchAll();
    foreach ($results as $row) {
        $sub_arrayli["limite"] = $row['limite'];
        $someArray["limite"] = array_map("utf8_encode",$sub_arrayli);
    }
    /*BUSCA REGISTROS*/
    $sql = "SELECT DISTINCT conta.associado, 
                   conta.valor, 
                   empregador.abreviacao, 
                   conta.lancamento, 
                   conta.data, 
                   conta.mes, 
                   conta.parcela, 
                   empregador.id, 
                   empregador.nome, 
                   usuarios.username, 
                   associado.nome AS nome_associado, 
                   convenio.razaosocial, 
                   convenio.nomefantasia,
                   conta.hora,
                   situacao_conta.descri as situacao,
                   associado.limite,
                   conta.exclui,
                   controle.mes as mes_controle
              FROM sascard.convenio RIGHT JOIN 
                   (sascard.associado RIGHT JOIN 
                   (sascard.usuarios RIGHT JOIN 
                   (sascard.empregador RIGHT JOIN 
                   (sascard.situacao_conta RIGHT JOIN
                   (sascard.controle RIGHT JOIN 
                   sascard.conta ON
                   conta.mes = controle.mes) ON
                   conta.id_situacao = situacao_conta.id_situacao OR conta.id_situacao ISNULL) ON
                   empregador.id = conta.empregador) ON 
                   usuarios.codigo = conta.Funcionario) ON 
                   associado.codigo = conta.associado AND associado.empregador = conta.empregador) ON 
                   convenio.codigo = conta.convenio
            WHERE " . $sqlmes . " associado.codigo = '" . $matricula . "' 
              AND empregador.id = " . $codempregador . ";";

    $statment = $pdo->prepare($sql);
    $statment->execute();
    $result = $statment->fetchAll();
    foreach ($result as $row) {

        $sub_array = array();

        $sub_array["registro"]        = $row['lancamento'];
        $sub_array["matricula"]       = $row['associado'];
        $sub_array["associado"]       = $row['nome_associado'];
        $sub_array["valor"]           = $row['valor'];
        $sub_array["data"]            = date('d/m/Y', strtotime($row['data']));
        $sub_array["hora"]            = substr($row['hora'], -8);
        $sub_array["mes"]             = $row['mes'];
        if ($row['parcela'] == null) {
            $sub_array["parcela"]     = '';
        } else {
            $sub_array["parcela"]     = $row['parcela'];
        }
        $sub_array["id_empregador"]   = $row['id'];
        $sub_array["nome_empregador"] = $row['nome'];
        $sub_array["razaosocial"]     = $row['razaosocial'];
        $sub_array["nomefantasia"]    = $row['nomefantasia'];
        $sub_array["funcionario"]     = $row['username'];
        $sub_array["situacao"]        = $row['situacao'];
        $sub_array["excluir"]         = $row['exclui'];

        $sub_array["mes_controle"] = $row['mes_controle'];

        $sub_array["botaoalterar"]    = '<button type="button" name="btnalterarList" id="' . $row["lancamento"] . '" class="btn btn-facebook btn-xs btnalterarList">Alterar</button>';
        $sub_array["botaoexcluir"]    = '<button type="button" name="btnexcluirList" id="' . $row["lancamento"] . '" class="btn btn-danger btn-xs btnexcluirList">Excluir</button>';

        $someArray["data"][] = array_map("utf8_encode", $sub_array);

    }
}


/*SOMA DAS CATEGORIAS*/
$sql_categorias = "SELECT DISTINCT Sum(conta.valor) AS total, tipoconvenio.nome
                      FROM sascard.tipoconvenio RIGHT JOIN 
                          (sascard.convenio RIGHT JOIN 
                          (sascard.associado RIGHT JOIN 
                          (sascard.empregador RIGHT JOIN 
                          (sascard.situacao_conta RIGHT JOIN 
                          sascard.conta ON 
                          situacao_conta.id_situacao = conta.id_situacao OR conta.id_situacao ISNULL) ON 
                          empregador.id = conta.empregador) ON 
                          associado.codigo = conta.associado AND associado.empregador = conta.empregador) ON 
                          convenio.codigo = conta.convenio) ON 
                          tipoconvenio.codigo = convenio.tipo
                    WHERE " . $sqlmes . " conta.associado = '" . $matricula . "' AND conta.empregador = " . $codempregador . "
                 GROUP BY convenio.tipo, conta.associado, tipoconvenio.nome;";

$statmentx = $pdo->prepare($sql_categorias);
$statmentx->execute();
$resultx = $statmentx->fetchAll();
$sub_array2 = array();
$sub_array2["Farmacia"]   = 0;
$sub_array2["Compras"]    = 0;
$sub_array2["Unimed"]     = 0;
$sub_array2["Financeira"] = 0;
foreach ($resultx as $rowx) {
    //$sub_array = array();
    if($rowx['nome'] == "FARMACIA"){
        $sub_array2["Farmacia"] = $rowx['total'];
    }
    if($rowx['nome'] == "COMPRAS"){
        $sub_array2["Compras"] = $rowx['total'];
    }
    if($rowx['nome'] == "UNIMED"){
        $sub_array2["Unimed"] = $rowx['total'];
    }
    if($rowx['nome'] == "FINANCEIRA"){
        $sub_array2["Financeira"] = $rowx['total'];
    }
}
$someArray["categorias"] = array_map("utf8_encode",$sub_array2);
if($mes !== 'todos') {
    $mes_posterior = somames_gravar($mes);
    /*BUSCA NAO DEScontaDOS CND = 47, FNC = 48, END = 49 ,DND = 68 DO MES POSTERIOR*/
    $sql_ND = "SELECT DISTINCT conta.valor, convenio.codigo
                     FROM sascard.tipoconvenio RIGHT JOIN 
                          (sascard.convenio RIGHT JOIN 
                          (sascard.associado RIGHT JOIN 
                          (sascard.empregador RIGHT JOIN 
                          (sascard.situacao_conta RIGHT JOIN 
                          sascard.conta ON 
                          situacao_conta.id_situacao = conta.id_situacao OR conta.id_situacao ISNULL) ON 
                          empregador.id = conta.empregador) ON 
                          associado.codigo = conta.associado AND associado.empregador = conta.empregador) ON 
                          convenio.codigo = conta.convenio) ON 
                          tipoconvenio.codigo = convenio.tipo
                    WHERE conta.mes = '" . $mes_posterior . "' AND conta.associado = '" . $matricula . "' AND conta.empregador = " . $codempregador . ";";

    $statmentnd = $pdo->prepare($sql_ND);
    $statmentnd->execute();
    $resultnd = $statmentnd->fetchAll();
    $sub_arraynd = array();

    $FND = 0;
    $CND = 0;
    $ENDES = 0;
    $DND = 0;
    $sub_arraynd["FND"] = 0;
    $sub_arraynd["CND"] = 0;
    $sub_arraynd["ENDES"] = 0;
    $sub_arraynd["DND"] = 0;
    foreach ($resultnd as $rownd) {
        //$sub_array = array();
        if ($rownd['codigo'] == 47) {/*CND*/
            $CND = $CND + $rownd['valor'];
        }
        if ($rownd['codigo'] == 48) {/*FND*/
            $FND = $FND + $rownd['valor'];
        }
        if ($rownd['codigo'] == 49) {/*END*/
            $ENDES = $ENDES + $rownd['valor'];
        }
        if ($rownd['codigo'] == 68) {/*DND*/
            $DND = $DND + $rownd['valor'];
        }

    }
    $sub_arraynd["CND"] = $CND;
    $sub_arraynd["FND"] = $FND;
    $sub_arraynd["ENDES"] = $ENDES;
    $sub_arraynd["DND"] = $DND;

    $someArray["naodescontado"] = array_map("utf8_encode", $sub_arraynd);
}

echo json_encode($someArray);