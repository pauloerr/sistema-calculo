$(document).ready(function() {
    // Carrega a página de "Novo Cálculo" ao iniciar
    $("#conteudo").load("novo_calculo.php");

    // Função para carregar "Novo Cálculo"
    $("#link-novo-calculo").click(function(e) {
        e.preventDefault();
        $("#conteudo").load("novo_calculo.php");
    });

    // Função para carregar "Relatórios"
    $("#link-meus-calculos").click(function(e) {
        e.preventDefault();
        $("#conteudo").load("meus_calculos.php");
    });
});

