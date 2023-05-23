<?php
header("Content-type: application/json");
require '../../php/banco.php';
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$msg='';
$contador = 0;
$sqlregs = "";
$totalpost = 0;
if (isset($_POST['registro'])){
    if($_POST['registro'] != "")
        $stmt = new stdClass();

    $totalpost = count($_POST['registro']);

    foreach($_POST['registro'] as $i => $arr) {

        if ($totalpost > $i+1){
            $sqlregs .= $arr['registro'] . ",";
        }else{
            $sqlregs .= $arr['registro'];
        }
    }
    $sql = "DELETE FROM sascard.conta WHERE lancamento IN(".$sqlregs.")";

    $stmt = $pdo->prepare($sql);
    //$stmt->bindParam(':lancamento', $_lancamento, PDO::PARAM_INT);

    $stmt->execute();

    $msg = 'excluido';

    $arr = array('Resultado'=>$msg);
    $someArray = array_map("utf8_encode",$arr);

}else{
    $msg = 'nao excluido';
    $arr = array('lancamento' =>'','Resultado'=>$msg);
    $someArray = array_map("utf8_encode",$arr);
}
echo json_encode($someArray);





