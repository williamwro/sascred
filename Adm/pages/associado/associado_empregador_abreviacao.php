<?PHP
    header("Content-type: application/json");
    include "../../php/banco.php";
    include "../../php/funcoes.php";
    $pdo = Banco::conectar_postgres();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $someArray = array();
    $i=1;
    if(isset( $_GET["abreviacao"])){
        $abreviacao = $_GET["abreviacao"];
        $sql = $pdo->query("SELECT * FROM sascard.empregador WHERE abreviacao = '".$abreviacao."'");
        while($row = $sql->fetch()) {
            $someArray[$i] = array_map("utf8_encode",$row);
            $i++;
        }
        echo json_encode($someArray);
    }