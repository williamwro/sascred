<?PHP
require '../../php/banco.php';
$_nome = isset($_POST['C_nome']) ? $_POST['C_nome'] : "";
$_divisao = isset($_POST['C_divisao']) ? $_POST['C_divisao'] : "";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$msg_grava_cad = "nao repitido";
try{
    $select = $pdo ->query("SELECT id,nome,responsavel,telefone,abreviacao,divisao 
                                      FROM sascard.empregador 
                                     WHERE nome = '".$_nome."' AND divisao = ".$_divisao);
    $select->execute();

    foreach ($select as $row) {
        $msg_grava_cad = "repitido";
    }
    echo $msg_grava_cad;
} catch (PDOException $erro) {
    echo "Não foi possivel inserir os dados no banco: " . $erro->getMessage();
}
