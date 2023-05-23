<?PHP
require '../../php/banco.php';
include "../../php/funcoes.php";
if(isset($_POST['cod_associado'])){
    $cod_associado      = $_POST['cod_associado'];

    $stmt = new stdClass();
    $pdo = Banco::conectar_postgres();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $msg_grava_cad="";

    try {
        $sql = "DELETE FROM sascard.associado WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':id', $cod_associado, PDO::PARAM_STR);

        $stmt->execute();

        $msg = 'excluido';
        $arr = array('Resultado'=>$msg);
        $someArray = array_map("utf8_encode",$arr);
        echo json_encode($someArray);

    } catch (PDOException $erro) {
        echo "Não foi possivel inserir os dados no banco: " . $erro->getMessage();
    }
}