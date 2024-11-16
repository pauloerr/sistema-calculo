<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'db_connect.php';
include_once 'model.php';

$tipoCalculo = "Novo";
if (isset($_GET['cod_protocolo'])) {
    $tipoCalculo = "Editar";
    $codProtocolo = $_GET['cod_protocolo'];

    $conn = getConnection();
    $acordoFebraban = new AcordoFebraban($conn);
    $calculo = $acordoFebraban->getCalculo($codProtocolo);
    $codIdentificacao = htmlspecialchars($calculo[0]['cod_identificacao']);
    $processo = htmlspecialchars($calculo[0]['processo']);
    $nome = htmlspecialchars($calculo[0]['parte']);
    $subtotal1 = htmlspecialchars(number_format($calculo[0]['subtotal1'], 2, ',', '.'));;
    $redutor = htmlspecialchars(number_format($calculo[0]['redutor'], 2, ',', '.'));
    $subtotal2 = htmlspecialchars(number_format($calculo[0]['subtotal2'], 2, ',', '.'));
    $honorarios = htmlspecialchars(number_format($calculo[0]['honorarios'], 2, ',', '.'));
    $honorariosFebrapo = htmlspecialchars(number_format($calculo[0]['honorarios_febrapo'], 2, ',', '.'));
    $total = htmlspecialchars(number_format($calculo[0]['total'], 2, ',', '.'));
    $codRedutor = htmlspecialchars($calculo[0]['cod_redutor']);
    $contas = $acordoFebraban->getContasCalculo($codIdentificacao);
    $qtdeContas = count($contas);
    $redutores = $acordoFebraban->getRedutor($codRedutor);
    $valorRedutor = htmlspecialchars($redutores[0]['valor_redutor']);
    $conn->close();
}
?>
<script src="acordo-febraban-modal-tratar.js"></script>
<div class="container mt-1" id="acordo-febraban">
    <h1 class="mt-4"><?php echo $tipoCalculo ?> Cálculo</h1>
    <div class="erros">
        <p id="mensagemDeErro"></p>
    </div>

    <div class="container infos-protocolo">
        <?php require 'infos_protocolo.php'; ?>
    </div>
    
    <div class="container selecao-contas">
        <?php require 'tabela_selecao_contas.php'; ?>
    </div>
    
    <div class="container resultado-calculo">
        <?php require 'resultado-calculo.php'; ?>
    </div>
</div>

