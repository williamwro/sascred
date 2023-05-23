<?PHP
date_default_timezone_set('America/Sao_Paulo');
include "Adm/php/funcoes.php";
$nome               = "";
$limite             = "";
$cod_cartao         = "";
$endereco           = "";
$bairro             = "";
$cod_convenio       = "";
$parcelas_conv      = 0;
$razaosocial        = "";
$nome_fantasia      = "";
$parcelas_a_exibir  = 0;
$mes_atual          = "";
$userconv           = "";
$passconv           = "";
if(isset( $_POST['userconv'] )){ $userconv=$_POST['userconv']; }else{ $userconv=""; };
if(isset( $_POST['passconv'] )){ $passconv=$_POST['passconv']; }else{ $passconv=""; };
$cod_convenio       = $_POST['cod_convenio'];
$nomefantasia       = $_POST['nomefantasia'];
$razaosocial        = $_POST['razaosocial'];
$endereco           = $_POST['endereco'];
$bairro             = $_POST['bairro'];
$cnpj               = $_POST['cnpj'];
$cidade             = $_POST['cidade'];
//$parcela_conv       = $_POST['parcela_conv'];
//$valor_pedido       = $_POST['valor_pedido'];
$pede_senha         = $_POST['pede_senha'];
if(isset($_POST['cod_carteira'])){$numero_cartao = $_POST['cod_carteira'];}else{$numero_cartao="";};
$dia = date("d");
$dia = intval($dia);
$m = 1;
if ($dia >= 4) {
    $mes_atual = somames(date("m/Y"), $m + 1);
}else if($dia >= 1 && $dia <= 3){
    $mes_atual = somames(date("m/Y"), $m);
}
if (isset($_POST['cod_carteira'])) {
    $numero_cartao = $_POST['cod_carteira'];
}else{
    $numero_cartao = "";
}
if (isset($_POST['parcelas_permitidas'])){
    if ($_POST['parcelas_permitidas'] == null){
        $parcelas_a_exibir = 1;
    }
    else{
        $parcelas_a_exibir = $_POST['parcelas_permitidas'];
    }
}else{
    if (isset($_POST['parcela_conv'])) {
        if ($_POST['parcela_conv'] == null) {
            $parcelas_a_exibir = 1;
        } else {
            $parcelas_a_exibir = $_POST['parcela_conv'];
        }
    }else{
        $parcelas_a_exibir = 1;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SASCRED - convenios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.3.1/flatly/bootstrap.min.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-sidermenu.css"/>
    <link href="Adm/bower_components/datatables/DataTable/css/dataTables.bootstrap4.min.css" rel="stylesheet"/>
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-fallr-2.0.1.css" />
    <style>
        body { background-color: #f7f7f7; }
        .ajax-loader {
            position: absolute;
            left: 45%;
            top: 40%;
            display: block;
        }
        #divLoading {
            background-color: grey;
            text-align: center;
            display: none;
            width: 99%;
            height: 99%;
            border: 0px solid black;
            position: absolute;
            padding: 2px;
            z-index: 100;
            filter: alpha(opacity=40);
            /* For IE8 and earlier */
            opacity: .8;
        }
        .printableFull {
            display: none;
            margin: -20px;
        }
        /* print styles*/
        @media print {
            .printableFull {
                display: block;
            }
            .table{
                display: none;
            }
            .printableLeft {
                font: 12pt Georgia, "Times New Roman", Times, serif;

            }
            .screen {
                display: none;
            }
            * {
                background:transparent !important;
                color:#000 !important;
                text-shadow:none !important;
                filter:none !important;
                -ms-filter:none !important;
            }

            body {
                margin:0;
                padding:0;
                line-height: 1.4em;
            }
        }
        #infosucesso p {
            padding-top: 40px;
        }

        img {
            max-width: 20pt;
            max-height: 20pt;
        }
        .printimage{
            max-width: 20pt;
            max-height: 20pt;
        }
        /*corrige o visual input select no mozila*/
        .i_bd {
            /* Disable vendor-specific appearance */
            -webkit-appearance: none;
            /* Use triangle background as arrow */
            background-image: url(data:image/svg+xml;base64,PHN2ZyBmaWxsPSIjMDAwMDAwIiBoZWlnaHQ9IjI0IiB2aWV3Qm94PSIwIDAgMjQgMjQiIHdpZHRoPSIyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4gICAgPHBhdGggZD0iTTcgMTBsNSA1IDUtNXoiLz48L3N2Zz4=);
            background-size: 24px 24px;
            background-repeat: no-repeat;
            background-position: center right;
            padding-top: 0;
        }
        #tabela_producao_length select {
            /* Disable vendor-specific appearance */
            -webkit-appearance: none;
            /* Use triangle background as arrow */
            background-image: url(data:image/svg+xml;base64,PHN2ZyBmaWxsPSIjMDAwMDAwIiBoZWlnaHQ9IjI0IiB2aWV3Qm94PSIwIDAgMjQgMjQiIHdpZHRoPSIyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4gICAgPHBhdGggZD0iTTcgMTBsNSA1IDUtNXoiLz48L3N2Zz4=);
            background-size: 24px 24px;
            background-repeat: no-repeat;
            background-position: center right;
            padding-top: 0;
        }
    </style>
</head>
<div id="divLoading">
    <img src='Spinnerloading.svg' class="ajax-loader"/>
</div>
<body>
<nav class="navbar navbar-dark bg-dark">
    <span class="navbar-brand">SASCRED</span>
</nav>
<div class="container-fluid">
    <section class="blok">
        <div class="blok-body">
            <div class="row">
                <div class="w-25 p-2" id="sidebar">
                    <ul class="menu-siderbar nav tab-menu flex-column">
                        <li class="nav-item active">
                            <a href="#" class="nav-link">Menu</a>
                        </li>
                        <li class="nav-item">
                            <a href="#principal" class="nav-link" id="link_principal" data-toggle="tab"><i class="menu-icon fa fa-home"></i>Pagina inicial</a>
                        </li>
                        <li class="nav-item">
                            <a href="#relatorio" class="nav-link" id="link_relatorio" data-toggle="tab"><i class="menu-icon fa fa-list"></i>Relatório</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" id="sair_sistema"><i class="menu-icon fa fa-sign-out"></i>Sair</a>
                        </li>
                    </ul>
                </div>

                <div class="w-75 p-2 tab-content" id="main">
                    <div class="tab-pane well active in active" id="principal">
                        <div class="container">
                            <!-- Main content -->
                            <div class="well well-sm rounded" style="background: #ecf0f1;padding: 4px;margin-left: -20px;margin-right: -20px;">
                                <section class="content">
                                    <table class="table" style="height: 100%">
                                        <form name="busca_associado" id="busca_associado" method="post" action="#">
                                            <tr>
                                                <td colspan="5" style="width: 60px;border: 0;padding-bottom: 0px;">
                                                    <div class="well well-sm rounded" style="background: #cecece;padding: 4px;">
                                                        <h4 class="display-6 text-center"><span id="titulo_convenio"><?PHP echo $nomefantasia;?></span></h4>
                                                        <h6 class="text-center font-weight-light"><span id="titulo_endereco"><?PHP echo $endereco." - ".$bairro.", CNPJ :".$cnpj.", ".$cidade;?></span></h6>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 60px;border: 0;padding-bottom: 0px;">
                                                    Cartão
                                                </td>
                                                <td style="width: 280px;border: 0;padding-bottom: 0px;">
                                                    <input type="text" class="form-control" style="font-weight: bold; font-size: 17px; text-align: center; font-family: 'Arial Black' " name="cod_carteira" id="cod_carteira"/>
                                                </td>
                                                <td style="border: 0;padding-bottom: 0px;">
                                                    <button type="submit" id="btnLocaliza" class="btn btn-primary">Localizar</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 60px;border: 0;padding-bottom: 0;">
                                                    Titular
                                                </td>
                                                <td colspan="5" style="width: 680px;border: 0;padding-bottom: 0;">
                                                    <span id="nome_associado_exibir" class="border rounded" style="border: 1px solid #ced4da; padding-left: 12px; padding-right: 12px; padding-top: 4px;padding-bottom: 6px; display: inline-block; background-color: #fff; width: 100%;height: 35px"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 60px;border: 0;padding-bottom: 0px;">
                                                    Cartão
                                                </td>
                                                <td style="width: 40px;height: 60px;border: 0;padding-bottom: 0px;">
                                                    <span id="cartao_exibir" class="border rounded"  style="border: 1px solid #ced4da; padding-left: 12px; padding-right: 12px; padding-top: 4px;padding-bottom: 6px; display: inline-block; background-color: #fff; width: 100%; height: 35px"></span>
                                                </td>
                                                <td style="width: 50px;border: 0;padding-bottom: 0;padding-bottom: 0px;"></td>
                                                <td colspan="2" rowspan="2" style="width: 70px;border: 0;margin: 0px;padding-bottom: 0px;">
                                                    <div class="alert alert-success" style="height: 75px;width: 150px;padding-top: 5px;padding-left: 15px;margin: 0;padding-bottom: 15px;" role="alert">
                                                        <h4 class="alert-heading" style="margin-bottom: 0;">SALDO</h4>
                                                        <hr style="margin-bottom: 5px; margin-top: 5px;">
                                                        <span id="txtSaldo"><p style="margin-bottom: 0;"></p></span>
                                                    </div>
                                                </td>
                                                <td style="width: 250px;border: 0;padding-bottom: 0px;"></td>
                                            </tr>
                                            <tr>
                                                <td style="width: 60px;border: 0;padding-bottom: 0px;padding-top: 0px;">
                                                    Mês
                                                </td>
                                                <td  style="width: 100px;border: 0;padding-bottom: 0px;padding-top: 0px;">
                                                    <span id="mes_pedido" class="border rounded"  style="border: 1px solid #ced4da; padding-left: 12px; padding-right: 12px; padding-top: 4px;padding-bottom: 6px; display: inline-block; background-color: #fff; width: 100%; height: 35px"><?PHP echo $mes_atual;?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 60px;border: 0;padding-bottom: 0px;">
                                                    Total
                                                </td>
                                                <td style="width: 40px;height: 60px;border: 0;padding-bottom: 0px;">
                                                    <input type="text"   id="valor_pedido"        name="valor_pedido"        class="form-control" maxlength="8" />
                                                    <!-- <input type="text"   id="valor_pedido2"       name="valor_pedido"        class="campo" size="10" maxlength="9" style="display: none;" /> -->
                                                    <input type="hidden" id="cod_convenio"        name="cod_convenio"        value="<?PHP echo $cod_convenio;?>"/>
                                                    <input type="hidden" id="txtSaldoCard"        name="txtSaldoCard"        value="<?PHP if(isset($_POST['txtSaldo'])){ echo $_POST['txtSaldo'];}?>"/>
                                                    <input type="hidden" id="matricula"           name="matricula"           value="<?PHP if(isset($_POST['matricula'])){  echo $_POST['matricula'];}?>"/>
                                                    <input type="hidden" id="e_p"                 name="e_p"                 value="<?PHP if(isset($_POST['empregador'])){ echo $_POST['empregador'];}?>"/>
                                                    <input type="hidden" id="razaosocial"         name="razaosocial"         value="<?PHP echo $razaosocial;?>"/>
                                                    <input type="hidden" id="nomefantasia"        name="nomefantasia"        value="<?PHP echo $nomefantasia;?>"/>
                                                    <input type="hidden" id="cnpj"                name="cnpj"                value="<?PHP echo $cnpj;?>"/>
                                                    <input type="hidden" id="nome"                name="nome"                value="<?PHP if(isset($_POST['nome'])){ echo $_POST['nome'];}?>"/>
                                                    <input type="hidden" id="m_p"                 name="m_p"                 value="<?PHP echo $mes_atual;?>"/>
                                                    <input type="hidden" id="userconv"            name="userconv"            value="<?PHP echo $userconv;?>"/>
                                                    <input type="hidden" id="passconv"            name="passconv"            value="<?PHP echo $passconv;?>"/>
                                                    <input type="hidden" id="parcelas_a_exibir"   name="parcelas_a_exibir"   value="<?PHP echo $parcelas_a_exibir;?>"/>
                                                    <input type="hidden" id="parcelas_permitidas" name="parcelas_permitidas" value="<?PHP echo $parcelas_conv;?>"/>
                                                    <input type="hidden" id="endereco"            name="endereco"            value="<?PHP echo $endereco;?>"/>
                                                    <input type="hidden" id="bairro"              name="bairro"              value="<?PHP echo $bairro;?>"/>
                                                    <input type="hidden" id="cidade"              name="cidade"              value="<?PHP echo $cidade;?>"/>
                                                    <input type="hidden" id="pede_senha"          name="pede_senha"          value="<?PHP echo $pede_senha;?>"/>
                                                    <input type="hidden" id="val_parcela"         name="val_parcela"         value=""/>
                                                </td>
                                                <td style="width: 50px;border: 0;padding-bottom: 0px;"></td>
                                                <td colspan="2" rowspan="3" style="width: 70px;border: 0;padding-bottom: 0px;">
                                                    <div class="alert alert-warning" id="msg_parcela" style="display: none; height: 75px;width: 230px; padding-top: 4px;padding-left: 15px;border: 0;padding-bottom: 15px;" role="alert">
                                                        <h4 class="alert-heading">VALOR PARCELA</h4>
                                                        <hr style="margin-bottom: 5px; margin-top: 5px;">
                                                        <span id="valor_parcela"><p style="margin-bottom: 0px;"></p></span>
                                                    </div>
                                                </td>
                                                <td style="width: 50px;border: 0;padding-bottom: 0px;"></td>
                                            </tr>
                                            <tr>
                                                <td style="width: 60px;border: 0;padding-top: 0px;">
                                                    Parcela
                                                </td>
                                                <td  style="width: 100px;border: 0;padding-top: 0px;">
                                                    <label for="nparcelas" class="sr-only"></label>
                                                    <select class="form-control i_bd" id="nparcelas" name="nparcelas" onchange="mudarparcela()">
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 100px;border: 0;padding-top: 0px;">
                                                    Senha
                                                </td>
                                                <td  style="width: 100px;border: 0;padding-top: 0px;">
                                                    <input type="password" class="form-control" id="pass" name="pass">
                                                </td>
                                                <td style="border: 0;padding-top: 0px;">
                                                    <button id="btnconfirmar" class="btn btn-primary">Gravar</button>
                                                </td>
                                            </tr>
                                        </form>
                                    </table>
                                </section>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane well fade" id="relatorio">
                        <div class="container">
                            <!-- Main content -->
                            <div class="well well-sm rounded" style="background: #ecf0f1;padding: 4px;margin-left: -20px;margin-right: -20px;">
                                <section class="content">
                                    <nav class="navbar navbar-expand-lg navbar-light bg-light">
                                        <div class="navbar-header">
                                            <a class="navbar-brand" href="#">Período</a>
                                        </div>
                                        <form id="listagem" name="listagem" class="form-inline" role="search">
                                            <div class="form-group" style="padding-right: 15px;">
                                                <label for="C_mes" class="sr-only">MÊS:</label>
                                                <select name="C_mes" id="C_mes" class="form-control"></select>
                                            </div>
                                            <div class="form-group">
                                                <input type="button" name="gerarpdf" id="gerarpdf" class="form-control btn btn-primary" value="Relatório"/>
                                            </div>
                                        </form>
                                    </nav>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table id="tabela_producao" class="table-striped table-hover compact" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>REGISTRO</th>
                                                    <th>NOME</th>
                                                    <th>DIA</th>
                                                    <th>HORA</th>
                                                    <th>VALOR</th>
                                                    <th>PARCELA</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane well fade" id="comprovante">
                        <div id="infosucesso">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h2><span class="badge badge-success">TRANSAÇÃO EFETUADA COM SUCESSO!</span></h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center" style="color: #0000CC">
                                    <span id="nome_gravado"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center" style="color: #0000CC; padding: 2px">
                                    <span id="valorpedido"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center" style="padding: 4px" >
                                    <button class="btn btn-primary" type="button" id="reexibir">CLICK  AQUI  PARA  REIMPRIMIR</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center" style="padding: 4px">
                                    <input class="btn btn-primary" type="button" id="botaoretornar" value="CLICK  AQUI  PARA  RETORNAR">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center" style="padding: 4px">
                                    <input class="btn btn-primary" type="button" id="sairsistema" value="CLICK  AQUI  PARA  SAIR  DO  SISTEMA">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="printableFull" style="width: 100%">
                        <div class="printableLeft" style="width: 45%;font-size: 12pt;"></div>
                        <div class="printableRight" style="width: 55%"></div>
                    </div>
                    <div class="print" style="font-size: 10pt; text-align: left !important;">
                        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="myModal">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <!-- ************************* INICIO PRIMEIRA VIA ********************************* -->
                                        <table class="table" id="via1" style="margin-bottom: -4px;
                                                                              margin-top: -3px;
                                                                              border-top-style: none;">
                                            <tbody>
                                            <tr>
                                                <td style=" width: 30px;
                                                            padding-bottom: 5px;
                                                            padding-top: 5px;
                                                            border-bottom: 1px solid #bdbdbd;
                                                            padding-right: 4px;">
                                                    <img src="logomini.png" alt="" />
                                                </td>
                                                <td style="font-size: 14px;
                                                           font-weight: bold;
                                                           vertical-align: bottom;
                                                           width: 79px;
                                                           padding: 0;
                                                           padding-bottom: 3px;
                                                           border-bottom: 1px solid #bdbdbd;">SINDSERVA</td>
                                                <td style="font-size: 9px;
                                                           vertical-align: bottom;
                                                           padding: 4px 6px 6px;
                                                           margin-top: 0;
                                                           text-align: right;
                                                           border-bottom: 1px solid #bdbdbd;">VIA DO CONVENIO</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div class="row" style="margin-top: 5px;">
                                            <div class="col-md-8" style="padding-right: 0;font-weight: bold;font-size: 12pt;">
                                                <span id="nomefantasia_cupon"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <span id="razaosocial_cupon"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8" style="padding-left: 14px;padding-right: 14px;font-weight: bold;font-size: 12pt;">
                                                <span id="cnpj_cupon"></span>
                                            </div>
                                        </div>
                                        <div class="row" style="padding-left: 10pt;">
                                            <div class="col-md-8 border-bottom" style="padding-left: 0;padding-right: 0;font-weight: bold;font-size: 12pt;">
                                                <span id="endereco_cupon"></span>
                                            </div>
                                        </div>
                                        <div class="row" style="padding-left: 10pt;">
                                            <div class="col-md-8 border-bottom" style="padding-left: 0;padding-right: 0; text-align: left;">
                                                <span id="cidade_cupon"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8" style="font-weight: bold;font-size: 12pt;">Titular:
                                                <span id="nome_cupon">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                Matricula:<span id="matricula_cupon"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                Data:<span id="datacad_cupon"></span>
                                            </div>
                                            <div class="col-md-4">
                                                Hora:<span id="hora_cupon"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                Nº Cartão:******<span id="codcarteira_cupon"></span>
                                                <span id="registrolan_cupon"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table" id="tabela_parcelas"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8"></div>
                                        </div>
                                        <br><br>
                                        <div class="row d-flex justify-content-left">
                                            <div class="col-md-8" style="border-bottom: 0.3pt solid #000;width: 160px;margin-left: 10pt"></div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="col-md-8" style="font-weight: bold;font-size: 10pt;">assinatura legivel</div>
                                        </div>
                                        <br><br>
                                        <!-- ************************* INICIO SEGUNDA VIA ********************************* -->
                                        <table class="table" id="via2" style="margin-bottom: -4px;
                                                                    margin-top: -3px;
                                                                    border-top-style: none;">
                                            <tbody>
                                            <tr>
                                                <td style=" width: 30px;
                                                            padding-bottom: 5px;
                                                            padding-top: 5px;
                                                            border-bottom: 1px solid #bdbdbd;
                                                            padding-right: 4px;">
                                                    <img src="logomini.png" />
                                                </td>
                                                <td style="font-size: 14px;
                                                           font-weight: bold;
                                                           vertical-align: bottom;
                                                           width: 79px;
                                                           padding: 0;
                                                           padding-bottom: 3px;
                                                           border-bottom: 1px solid #bdbdbd;">SINDSERVA</td>
                                                <td style="font-size: 9px;
                                                           vertical-align: bottom;
                                                           padding: 4px 6px 6px;
                                                           margin-top: 0;
                                                           text-align: right;
                                                           border-bottom: 1px solid #bdbdbd;">VIA DO ASSOCIADO</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div class="row" style="margin-top: 5px;">
                                            <div class="col-md-8" style="padding-right: 0;font-weight: bold;font-size: 12pt;">
                                                <span id="nomefantasia2_cupon"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <span id="razaosocial2_cupon"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8" style="padding-left: 10px;">
                                                <span id="cnpj2_cupon" style="padding-left: 4px;padding-right: 14px;font-weight: bold;font-size: 12pt;"></span>
                                            </div>
                                        </div>
                                        <div class="row" style="padding-left: 10pt;">
                                            <div class="col-md-8 border-bottom" style="padding-left: 0;padding-right: 0;font-weight: bold;font-size: 12pt;">
                                                <span id="endereco2_cupon"></span>
                                            </div>
                                        </div>
                                        <div class="row" style="padding-left: 10pt;">
                                            <div class="col-md-8 border-bottom" style="padding-left: 0;padding-right: 0; text-align: left;">
                                                <span id="cidade2_cupon"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8" style="font-weight: bold;font-size: 12pt;">Titular:
                                                <span id="nome2_cupon">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8" style="font-size: 10pt;">
                                                Matricula:<span id="matricula2_cupon"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4" style="font-size: 10pt;">
                                                Data:<span id="datacad2_cupon"></span>
                                            </div>
                                            <div class="col-md-4" style="font-size: 10pt;">
                                                Hora:<span id="hora2_cupon"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" style="font-size: 10pt;">
                                                Nº Cartão:******<span id="codcarteira2_cupon" ></span>
                                                <span id="registrolan2_cupon" style="font-size: 10pt;"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <span class="table" id="tabela_parcelas2"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8"></div>
                                        </div>
                                        <br>
                                        <br>
                                        <br>
                                        <div class="row d-flex justify-content-left">
                                            <div class="col-md-8" style="border-bottom: 0.3pt solid #000;width: 160px;margin-left: 10pt"></div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="col-md-8" style="font-weight: bold;font-size: 10pt;">assinatura legivel</div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" id="botaoimprir" class="btn btn-primary">Imprimir</button>
                                        <button type="button" id="botaofechar" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                    </div><!-- /.screen -->
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="js/jquery-3.4.1.js"></script>
<script type="text/javascript" src="js/jquery-fallr-2.0.1.js"></script>
<script type="text/javascript" src="js/maskmoney.js"></script>
<script type="text/javascript" src="js/jquery.redirect.js"></script>
<script type="text/javascript" src="js/sweetalert2.all.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/i18n/defaults-*.min.js"></script> -->
<!-- <script type="text/javascript" src="real-time-database.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.15.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.15.1/firebase-database.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.15.1/firebase-firestore.js"></script>
<!-- : Add SDKs for Firebase products that you want to use
 https://firebase.google.com/docs/web/setup#config-web-app -->
<script type="text/javascript" src="app.js"></script>
<script src="js/printThis.js"></script>
<script type="text/javascript" src="js/requisicao_introduction.js"></script>
</body>
</html>