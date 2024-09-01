<div class="d-flex justify-content-between align-items-center col-12">
    <p id="protocoloAcordoFebraban" class="mb-0">
        <b>Protocolo: </b><span id="cod-protocoloAcordoFebraban">Parâmetros de Cálculo ainda não salvos</span>
        <input type="hidden" id="protocoloAcordoFebrabanHidden" value="">
    </p>
    <div class="d-flex gap-2">
        <button type="button" id="btnImprimirCalcular" onclick="ExecutaRotinaImpressao();" class="btn btn-primary btn-calcular" disabled>
            <i class="bi bi-printer"></i> Imprimir Cálculo
        </button>
        <button type="button" id="btnSalvarParametros" onclick="SalvarCalculoAcordoFebraban();" class="btn btn-success btn-exibir-calculos">
            <i class="bi bi-save"></i> Salvar Cálculo
        </button>
    </div>
</div>

<div class="row infos-protocolo">
    <div class="col-4 infos-protocolo-protocolo">
        <label for="processo" class="form-label">Processo:</label>
        <input type="text" class="form-control" id="processo" name="valor">
    </div>
    <div class="col-8 infos-protocolo-protocolo">
        <label for="nomeParte" class="form-label">Nome:</label>
        <input type="text" class="form-control" id="nomeParte" name="valor">
    </div>
</div>