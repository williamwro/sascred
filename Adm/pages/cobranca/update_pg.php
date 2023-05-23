<?php
require '../../php/banco.php';
$stmtx = new stdClass();
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sqlx = "";
$msg_grava_cad="";
$id = $_POST['id'];
$controle = $_POST['controle'];

$sqlx = "UPDATE sascard.cobranca SET pago = :controle WHERE id = :id";

try {
    $stmtx = $pdo->prepare($sqlx);
    $stmtx->bindParam(':id', $id, PDO::PARAM_INT);
    $stmtx->bindParam(':controle', $controle, PDO::PARAM_STR);
    $stmtx->execute();
    $msggrava = "atualizado";
    $arr = array('resultado'=>$msggrava);

} catch (PDOException $erro) {
    //echo "Não foi possivel inserir os dados no banco: " . $erro->getMessage();
    $msggrava = "nao atualizado";
    $arr = array('resultado'=>$msggrava);
}
$someArray = array_map("utf8_encode",$arr);
echo json_encode($someArray);