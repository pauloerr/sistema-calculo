$(document).ready(function() {
    let urlParams = new URLSearchParams(window.location.search);

    // Obtém o valor do parâmetro "pagina"
    let pagina = urlParams.get('pagina');    
    // Remove a query string da URL sem recarregar a página
    window.history.replaceState(null, '', window.location.pathname);
    switch (pagina) {
        case 'meusCalculos':
            $("#conteudo").load("meus_calculos.php?t=" + new Date().getTime());
            break;
        case 'sobre':
            $("#conteudo").load("sobre.php?t=" + new Date().getTime());
            break;
        default:
             $("#conteudo").load("novo_calculo.php?t=" + new Date().getTime());
    }

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

    // Função para carregar "Sobre"
    $("#link-sobre").click(function(e) {
        e.preventDefault();
        $("#conteudo").load("sobre.php");
    });
});

