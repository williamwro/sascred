<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
header("Content-type: application/json");
include "Adm/php/banco.php";
$pdo = Banco::conectar_postgres();
//$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$cod_cartao="";
$senha="";
if(isset($_POST['cartao'])) {
    $cod_cartao = $_POST['cartao'];
}else{
    $cod_cartao = "";
}
if(isset($_POST['senha'])) {
    $senha = $_POST['senha'];
}else{
    $senha = "";
}
$codigo_associado = '';
$limite              = '';                          //  1 = liberado
$parcelas_permitidas = 0;                           //  3 = cancelado
$std = new stdClass();                              //  4 = produção
$contador=0;                                        //  5 = segunda via
                                                    //  6 = senha errada
$row_assoc = $pdo->query("SELECT associado.codigo, associado.nome, 
                                 associado.empregador, associado.limite, 
                                 associado.salario, associado.parcelas_permitidas, 
                                 associado.cpf,associado.email,
                                 associado.cel,associado.cep,
                                 associado.endereco,associado.numero,
                                 associado.bairro,associado.cidade,
                                 associado.uf,associado.celwatzap,
                                 c_cartaoassociado.cod_situacaocartao, 
                                 c_cartaoassociado.cod_verificacao, 
                                 associado.email, associado.cel, associado.cpf,
                                 empregador.nome as nome_empregador 
                            FROM sascard.associado 
                      INNER JOIN sascard.c_cartaoassociado 
                              ON associado.codigo = c_cartaoassociado.cod_associado AND associado.empregador = c_cartaoassociado.empregador
                      INNER JOIN sascard.empregador
					  		  ON associado.empregador = empregador.id          
                           WHERE c_cartaoassociado.cod_verificacao='".$cod_cartao."'")->fetch();
if ($row_assoc) {
    $contador = 1;
    if ($row_assoc['cod_situacaocartao'] == "1" or $row_assoc['cod_situacaocartao'] == "5" or $row_assoc['cod_situacaocartao'] == "4") {
        $std->nome = $row_assoc['nome'];
        $std->cod_cart = $row_assoc['cod_verificacao'];
        $std->matricula = $row_assoc['codigo'];
        $std->empregador = $row_assoc['empregador'];
        $empregador = $row_assoc['empregador'];
        $std->parcelas_permitidas = $row_assoc["parcelas_permitidas"];
        $std->limite = number_format(($row_assoc["limite"]), 2, '.', '');
		$std->email = $row_assoc["email"];
		$std->cpf = $row_assoc["cpf"];
		$std->cel = $row_assoc["cel"];
        $std->email = $row_assoc["email"];
        $std->endereco = $row_assoc["endereco"];
        $std->numero = $row_assoc["numero"];
        $std->bairro = $row_assoc["bairro"];
        $std->cep = $row_assoc["cep"];
        $std->cidade = $row_assoc["cidade"];
        $std->uf = $row_assoc["uf"];
        $std->celwatzap = $row_assoc["celwatzap"];
        $codigo_associado = $row_assoc["codigo"];
        $std->nome_empregador = $row_assoc["nome_empregador"];
        // ****************************************************
        $row_assoc = $pdo->query("SELECT * FROM sascard.c_senhaassociado WHERE cod_associado='" . $codigo_associado . "' AND senha='" . $senha . "' AND id_empregador=" . $empregador)->fetch();
        if ($row_assoc) {
            $std->situacao = 1; //1 = liberado
        } else {
            $std->situacao = 6; //6 = senha errada
        }
        $std->senha = $senha;
    } else {
        $std->situacao = 0; //0 = bloqueado,
        $std->nome = $row_assoc['mome'];
        $std->cod_cart = $row_assoc['cod_verificacao'];
        $std->matricula = $row_assoc['codigo'];
        $std->empregador = $row_assoc['empregador'];
        $std->parcelas_permitidas = $row_assoc["parcelas_permitidas"];
        $std->limite = "";
		$std->email = $row_assoc["email"];
		$std->cpf = $row_assoc["cpf"];
		$std->cel = $row_assoc["cel"];
        $std->email = $row_assoc["email"];
        $std->endereco = $row_assoc["endereco"];
        $std->numero = $row_assoc["numero"];
        $std->bairro = $row_assoc["bairro"];
        $std->cep = $row_assoc["cep"];
        $std->cidade = $row_assoc["cidade"];
        $std->uf = $row_assoc["uf"];
        $std->celwatzap = $row_assoc["celwatzap"];
        $std->nome_empregador = $row_assoc["nome_empregador"];
    }
    if ($contador == 0) {
        $std->situacao = 2; //2 = nao encontrado,
        $std->nome = '';
        $std->cod_cart = '';
        $std->matricula = '';
        $std->empregador = 0;
        $std->parcelas_permitidas = 0;
        $std->limite = '';
		$std->email = '';
		$std->cpf = '';
    	$std->cel = '';
        $std->cel = '';
        $std->email = '';
        $std->endereco = '';
        $std->numero = '';
        $std->bairro = '';
        $std->cep = '';
        $std->cidade = '';
        $std->uf = '';
        $std->celwatzap = '';
        $std->nome_empregador = '';
    }
}else{
	//logConsole('codigo', $cod_cartao, true);
    $std->situacao = 3;
    $std->dados = $cod_cartao;
}
echo json_encode($std);