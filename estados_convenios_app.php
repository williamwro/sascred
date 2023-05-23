<?PHP
    header("Content-type: application/json");
    include "Adm/php/banco.php";
    include "Adm/php/funcoes.php";
    $pdo = Banco::conectar_postgres();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$someArray = array();
    $query = $pdo->query('SELECT uf FROM sascard.convenio GROUP BY uf');

    while($row = $query->fetch()) {

        $someArray[] = array_map("utf8_encode",$row);

    }

echo json_encode($someArray);