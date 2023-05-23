<?php
require '../../php/banco.php';
$divisao= $_POST['divisao'];
$abreviacao= $_POST['abreviacao'];
$status= $_POST['status'];
$valstatus=0;
$std = new stdClass();
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if($status === "Bloqueado" ){
    $valstatus=1;
    $stmt = $pdo->prepare("DELETE FROM sascard.controle WHERE mes =  '".$abreviacao."'");
    $stmt->execute();
}else if($status === "Liberado" ){
    $stmt = $pdo->prepare("INSERT INTO sascard.controle(mes) VALUES('".$abreviacao."')");
    $stmt->execute();
    $valstatus=0;
}
$stmt = $pdo->prepare("UPDATE sascard.meses_conta SET status_cadastro = ".$valstatus." WHERE abreviacao =  '".$abreviacao."'");
$stmt->execute();
$resultado = "atualizado";
$arr = array('resultado' => $resultado);
$someArray = array_map("utf8_encode",$arr);

echo json_encode($someArray);