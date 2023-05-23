<?PHP
header("Content-type: application/json");
include "../../php/banco.php";
$pdo = Banco::conectar_postgres();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$someArray = array();
$std = new stdClass();
$i=1;
$sql = $pdo->query("SELECT * FROM sascard.conta_tipo ORDER BY id");
while($row = $sql->fetch()) {
    $someArray[$i] = array_map("utf8_encode",$row);
    $i++;
}
$xx = json_encode($someArray);
echo json_encode($someArray);
