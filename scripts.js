$(document).ready(function() {
    let urlParams = new URLSearchParams(window.location.search);

    let pagina = urlParams.get('pagina');    

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

    $("#link-novo-calculo").click(function(e) {
        e.preventDefault();
        $("#conteudo").load("novo_calculo.php");
    });

    $("#link-meus-calculos").click(function(e) {
        e.preventDefault();
        $("#conteudo").load("meus_calculos.php");
    });

    $("#link-sobre").click(function(e) {
        e.preventDefault();
        $("#conteudo").load("sobre.php");
    });
});

