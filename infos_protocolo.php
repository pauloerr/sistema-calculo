<div class="d-flex justify-content-between align-items-center col-12">
    <p id="protocoloAcordoFebraban" class="mb-0">
        <b>Protocolo: </b><span id="cod-protocoloAcordoFebraban"><?php echo !empty($codProtocolo) ? htmlspecialchars($codProtocolo) ." - Parâmetros de Cálculo recuperados com sucesso" : "Parâmetros de Cálculo ainda não salvos"; ?></span>
        <input type="hidden" id="protocoloAcordoFebrabanHidden" value="<?php echo !empty($codProtocolo) ? htmlspecialchars($codProtocolo) : ''; ?>">
    </p>
    <div class="d-flex gap-2">
        <button type="button" id="btnImprimirCalcular" onclick="ExecutaRotinaImpressao();" class="btn btn-primary btn-calcular" <?php echo !empty($codProtocolo) ? '' : 'disabled' ?>>
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
        <input type="text" class="form-control" id="processo" name="valor" value="<?php echo !empty($processo) ? htmlspecialchars($processo) : ''; ?>">
    </div>
    <div class="col-8 infos-protocolo-protocolo">
        <label for="nomeParte" class="form-label">Nome:</label>
        <input type="text" class="form-control" id="nomeParte" name="valor" value="<?php echo !empty($nome) ? htmlspecialchars($nome) : ''; ?>">
    </div>
</div>