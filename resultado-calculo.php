<?php
include_once 'db_connect.php';
$conn = getConnection();

$sql = "SELECT * FROM view_ano_fator";
$data = $conn->query($sql);
if ($data === false) {
    echo "Erro na consulta: " . $conn->error;
    exit();
}
$anoFatorFebraban = array();
if ($data->num_rows > 0) {
    $anoFatorFebraban = $data->fetch_assoc();
}
$conn->close();

?>

<style>
.resumoResultado {
    font-size: 14px;
    font-weight: normal;
}

.totais    {
    font-weight: bold;
    border-bottom: 1px solid #48586C;
    border-top: 1px solid #48586C;    
}

.d {
    text-align: right;
}
</style>

<div class="container">
    <div class="row">
        <div class="col-8">
            <div class="resumoResultado">   
                <div id="observacoes"><b>Observações:</b></br><sup>1</sup> Fator do aditivo do Acordo da FEBRABAN atualizado para <?php echo $anoFatorFebraban['ano_fator']; ?>.</div>
                <input type="hidden" id="anoFator" value="<?php echo $anoFatorFebraban['ano_fator']; ?>">
                <div id="inconformidadePlanos"></div>  
            </div>
        </div>
        <div class="col-4 resumoResultado">
            <div class="row">
                <div class="col-6">SubTotal 1: </div>
                <div class="col-1">R$</div>                
                <div class="col-5 d" id="subTotal1" value='<?php echo !empty($subtotal1) ? $subtotal1 : '0,00'; ?>'><?php echo !empty($subtotal1) ? $subtotal1 : '0,00'; ?></div>
            </div>
            <div class="row">
                <div class="col-6" id="redutor %">Redutor (<?php echo !empty($valorRedutor) ? $valorRedutor : '0'?>%):</div>
                <div class="col-1">R$</div>
                <div class="col-5 d" id="redutor" value='<?php echo !empty($redutor) ? $redutor : '0,00'; ?>'><?php echo !empty($redutor) ? $redutor : '0,00'; ?></div>
            </div>
            <div class="row totais">
                <div class="col-6">SubTotal 2: </div>
                <div class="col-1">R$</div>
                <div class="col-5 d" id="subTotal2" value='<?php echo !empty($subtotal2) ? $subtotal2 : '0,00'; ?>'><?php echo !empty($subtotal2) ? $subtotal2 : '0,00'; ?></div>
            </div>
            <div class="row">
                <div class="col-6">Honorários (10%): </div>
                <div class="col-1">R$</div>
                <div class="col-5 d" id="honorarios" value='<?php echo !empty($honorarios) ? $honorarios : '0,00'; ?>'><?php echo !empty($honorarios) ? $honorarios : '0,00'; ?></div>
            </div>
            <div class="row">
                <div class="col-6">Honorários FEBRAPO (5%): </div>
                <div class="col-1">R$</div>
                <div class="col-5 d" id="honorariosFebrapo" value='<?php echo !empty($honorariosFebrapo) ? $honorariosFebrapo : '0,00'; ?>'><?php echo !empty($honorariosFebrapo) ? $honorariosFebrapo : '0,00'; ?></div>
            </div>
            <div class="row totais">
                <div class="col-6">Total: </div>
                <div class="col-1">R$</div>
                <div class="col-5 d" id="total" value='<?php echo !empty($total) ? $total : '0,00'; ?>'><?php echo !empty($total) ? $total : '0,00'; ?></div>
            </div>
        </div>
    </div>
</div>
