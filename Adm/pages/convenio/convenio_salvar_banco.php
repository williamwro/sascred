<?PHP
require '../../php/banco.php';
include "../../php/funcoes.php";

if(isset($_POST['codigo_convenio_banco'])){
    $_codigo = (int)$_POST['codigo_convenio_banco'];
    $_cod_banco = $_POST['C_Banco'];
    $_conta = $_POST['C_Conta'];
    $_agencia = $_POST['C_Agencia'];
    $_tipo = $_POST['C_Tipo_Conta'];
    $stmt = new stdClass();
    $pdo = Banco::conectar_postgres();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $msg_grava_cad="";
    $_existe_banco="nao";
    $sql = "SELECT * FROM sascard.banco_convenio WHERE cod_convenio = :codigo_convenio";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':codigo_convenio', $_codigo, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach ($result as $row){
        $_existe_banco = "sim";
    }
    try {

        if($_existe_banco=="nao"){

            $sql = "INSERT INTO sascard.banco_convenio(cod_convenio,cod_banco,cod_tipo,conta,agencia) ";
            $sql .= "VALUES(:codigo_convenio, :cod_banco, :cod_tipo, :conta, :agencia)";
            $msg_grava_cad="cadastrado";

            $_codigo = (int)$_codigo;
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':codigo_convenio', $_codigo, PDO::PARAM_INT);
            $stmt->bindParam(':cod_banco', $_cod_banco, PDO::PARAM_INT);
            $stmt->bindParam(':cod_tipo', $_tipo, PDO::PARAM_INT);
            $stmt->bindParam(':conta', $_conta, PDO::PARAM_STR);
            $stmt->bindParam(':agencia', $_agencia, PDO::PARAM_STR);

            $stmt->execute();

            echo $msg_grava_cad;

        }else{

            $sql = "UPDATE sascard.banco_convenio SET ";
            $sql .= "cod_banco = :cod_banco, ";
            $sql .= "cod_tipo = :cod_tipo, ";
            $sql .= "conta = :conta, ";
            $sql .= "agencia = :agencia ";
            $sql .= "WHERE cod_convenio = :codigo_convenio";
            $msg_grava_cad="atualizado";

            $_codigo = (int)$_codigo;
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':codigo_convenio', $_codigo, PDO::PARAM_INT);
            $stmt->bindParam(':cod_banco', $_cod_banco, PDO::PARAM_INT);
            $stmt->bindParam(':cod_tipo', $_tipo, PDO::PARAM_INT);
            $stmt->bindParam(':conta', $_conta, PDO::PARAM_STR);
            $stmt->bindParam(':agencia', $_agencia, PDO::PARAM_STR);

            $stmt->execute();

            echo $msg_grava_cad;

        }

    } catch (PDOException $erro) {
        echo "Não foi possivel inserir os dados no banco: " . $erro->getMessage();
    }
}