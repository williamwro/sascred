$(document).ready(function(){
    var caminho = $("#caminho").val();
    var matricula = $("#matricula").val();
    var nome = $("#nome").val();
    var empregador = $("#empregador").val();
    var divisao = sessionStorage.getItem("divisao");
    var divisao_nome = sessionStorage.getItem("divisao_nome");
    var usuario_global = sessionStorage.getItem("usuario_global");
    var usuario_cod = sessionStorage.getItem("usuario_cod");
    $('.navbar-nav li a').on('click', function(){
        if(!$( this ).hasClass('dropdown-toggle')){
            $('.navbar-collapse').collapse('hide');
        }
    });
    $('#pagina_conteudo').load('pages/home.html');
    if (usuario_global){
        $('#link_dashboard').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/home.html',null,function () {
            });
        });
        $('#link_extrato_associado').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/extrato/extrato.html',null,function () {
            });
        });
        $('#link_convenio_manutencao').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/convenio/convenio_read.html',null,function () {
            });
        });
        $('#link_convenio_relatorio').click(function(){
            $.ajaxSetup({
                cache:true
            });
            //$('#pagina_conteudo').load('pages/convenio/convenio_read.html',null,function () {
            //});
            $.redirect('pages/convenio/gerador_pdf_convenios.php', "POST", "_blank");
        });
        $('#link_agenda').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/agenda/agenda_read.html',null,function () {
            });
        });
        $('#link_producao').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/producao/producao_read.html',null,function () {
            });
        });
        $('#link_transferencia').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/conta/transferencia.html',null,function () {
            });
        });
        $('#link_manutencao').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/conta/manutencao.html',null,function () {
            });
        });
        $('#link_cadastrocartoes').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/cartoes/cartoes_cadastro.html',{divisao_nome: divisao_nome},function () {
            });
        });
        $('#link_manutencaocartao').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/cartoes/cartoes_read.html',null,function () {
            });
        });
        $('#link_conv_totais').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/producao/producao_read_totais.html',null,function () {
            });
        });
        $('#link_total_meses').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/producao/producao_read_totalmes.html',null,function () {
            });
        });
        $('#link_arqdesc').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/arquivos/gerar_arquivos.html',null,function () {
            });
        });
        $('#link_usuario_manutencao').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/usuarios/usuarios.html',null,function () {
            });
        });
        $('#link_retorno').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/arquivos/retorno.html',null,function () {
            });
        });
        $('#link_divisao').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/divisao/divisao.html',null,function () {
            });
        });
        $('#link_empregador').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/empregador/empregador.html',null,function () {
            });
        });
        $('#link_categoria').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/categoria_convenio/categoria.html',null,function () {
            });
        });
        $('#link_funcao').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/funcao/funcao.html',null,function () {
            });
        });
        $('#link_associado').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/associado/associado_read.html',null,function () {
            });
        });
        $('#link_cadcartoes').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/cartoes/gerar_arquivo_cartoes.html',null,function () {
            });
        });
        $('#link_bloquear_mes').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/manutencao/bloquear_mes.html',null,function () {
            });
        });
        $('#link_recibos').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/recibos/recibos.html',null,function () {
            });
        });
        $('#link_cartoesbloqueados').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/cartoes/cartoes_bloqueados.html',null,function () {
            });
        });
        $('#link_cobranca').click(function(){
            $.ajaxSetup({
                cache:true
            });
            $('#pagina_conteudo').load('pages/cobranca/cobranca.html',null,function () {
            });
        });
        
    }else{

        $.redirect('../index.html');

    }


});