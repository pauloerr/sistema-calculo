function SalvarCalculoAcordoFebraban() {
    console.log('SalvarCalculoAcordoFebraban');
    codProtocolo = $('#protocoloAcordoFebrabanHidden').val();
    if (!codProtocolo) {
        console.log('Cálculo novo não salvo.');
        novoCalculo = 0;
    } else {
        console.log('Cálculo já salvo, atualizar dados.');
        novoCalculo = 1;
    }
    usuario = 'Usuario Padrão';
    //Informações do Sequencial
    processo = $('#processo').val();
    nomeParte = $('#nomeParte').val();
    console.log('#### INFORMAÇÕES DO SEQUENCIAL ####')
    console.log('Processo: ' + processo);
    console.log('Nome da parte: ' + nomeParte);
    console.log('Usuário: ' + usuario);
    //Verificação de contas
    var quantidadeContas = $('#bodyContas').children().length;
    contas = '';
    for (i = 0; i <= quantidadeContas - 1; i++) {
        conta = $('#Conta' + i).val();
        if (conta) {
            novaConta = conta;
        }
        stringPlano = $('#Plano' + i).val();
        if (stringPlano && stringPlano != 0) {
            let arrayPlano = stringPlano.split('|');
            let plano = arrayPlano[0];
            novaConta = novaConta + ',' + plano;
        }
        aniversario = $('#Aniversario' + i).val();
        if (aniversario) {
            novaConta = novaConta + ',' + aniversario;
        } 
        saldoBase = $('#SaldoBase' + i).val();
        if (saldoBase  && saldoBase != 0) {
            saldoBase = saldoBase.replaceAll('.', '');
            saldoBase = saldoBase.replace(',', '.');
            novaConta = novaConta + ',' + saldoBase;
        } 
        valorAcordo = $('#ValorAcordo' + i).val();
        if (valorAcordo && valorAcordo != 0) {
            valorAcordo = valorAcordo.replaceAll('.', '');
            valorAcordo = valorAcordo.replace(',', '.');
            novaConta = novaConta + ',' + valorAcordo;
        } 
        contas = contas + '|' + novaConta;
    }
    contas = contas.slice(1);   
    console.log('Contas: ' + contas);

    //Resultado do Cálculo
    //Sub-total 1
    let subTotal1 = formataMoeda($('#subTotal1').text());
    let redutor = formataMoeda($('#redutor').text());
    let subTotal2 = formataMoeda($('#subTotal2').text());
    let honorarios = formataMoeda($('#honorarios').text());
    let honorariosFebrapo = formataMoeda($('#honorariosFebrapo').text());
    let total = formataMoeda($('#total').text());
    console.log('Sub-Total 1: ' + subTotal1);
    console.log('Redutor: ' + redutor);
    console.log('Sub-Total 2: ' + subTotal2);
    console.log('Honorários: ' + honorarios);
    console.log('Honorários Febrapo: ' + honorariosFebrapo);
    console.log('Total: ' + total);

    //Código do redutor
    let codRedutor = 1;
    if (subTotal1 > 0 && subTotal1 <= 5000) {
        codRedutor = 1;
    } else if (subTotal1 > 5000 && subTotal1 <= 10000) {
        codRedutor = 2;
    } else if (subTotal1 > 10000 && subTotal1 <= 20000) {
        codRedutor = 3;
    } else if (subTotal1 > 20000) {
        codRedutor = 4;
    }

    //Inconformidade dos Planos
    console.log('inconformidade planos: ' + $('#observacoes').html() + $('#inconformidadePlanos').html());
    let inconformidade = $('#observacoes').html() + $('#inconformidadePlanos').html();

    let anoFator = $('#anoFator').val();
    console.log('Ano Fator: ' + anoFator);

    if (novoCalculo == 0) {
        console.log('Grava novo Cálculo');
        $.ajax({
            type: "POST",
            url: "model.php",
            data: {
                'action': 'salvaDadosAcordoFebrabanSQL',
                'processo': processo,
                'nomeParte': nomeParte,
                'contas': contas,
                'subTotal1': subTotal1,
                'redutor': redutor,
                'codRedutor': codRedutor,
                'subTotal2': subTotal2,
                'honorarios': honorarios,
                'honorariosFebrapo': honorariosFebrapo,
                'total': total,
                'usuario': usuario,
                'inconformidade': inconformidade,
                'anoFator': anoFator
            },
            dataType: 'json',
            beforeSend: function() {
            //     $('#cod-protocoloAcordoFebraban').html('Gravando cálculo. Aguarde...');
            //     $('#protocoloAcordoFebrabanHidden').val('');                  
            },
            success: function(retorno) {
                console.log(retorno);
                $('#cod-protocoloAcordoFebraban').html(retorno + ' - Parâmetros de cálculo salvos com sucesso!');
                $('#protocoloAcordoFebrabanHidden').val(retorno);
            },
            error: function(retorno) {
                $('#cod-protocoloAcordoFebraban').html(retorno);
                $('#protocoloAcordoFebrabanHidden').val(retorno);
            }
        });
    } else {
        console.log('Atualiza cálculo existente');
        $.ajax({
            type: "POST",
            url: "model.php",
            data: {
                'action': 'atualizaDadosAcordoFebrabanSQL',
                'codProtocolo': codProtocolo,
                'processo': processo,
                'nomeParte': nomeParte,
                'contas': contas,
                'subTotal1': subTotal1,
                'redutor': redutor,
                'codRedutor': codRedutor,
                'subTotal2': subTotal2,
                'honorarios': honorarios,
                'honorariosFebrapo': honorariosFebrapo,
                'total': total,
                'usuario': usuario,
                'inconformidade': inconformidade,
                'anoFator': anoFator
            },
            dataType: 'json',
            beforeSend: function() {
                // $('#cod-protocoloAcordoFebraban').html('Atualizando cálculo. Aguarde...');
                // $('#protocoloAcordoFebrabanHidden').val('');                
            },
            success: function(retorno) {
                $('#cod-protocoloAcordoFebraban').html(retorno + ' - Parâmetros de cálculo atualizados com sucesso!');
                $('#protocoloAcordoFebrabanHidden').val(retorno);
            },
            error: function(retorno) {
                $('#cod-protocoloAcordoFebraban').html(retorno);
                $('#protocoloAcordoFebrabanHidden').val(retorno);
            }
        });
    }

    //Habilita botão de impressão
    console.log('remove disabled');
    $('#btnImprimirCalcular').removeAttr("disabled");

}

function formataMoeda(valor) {
    valor = valor.replaceAll('.', '');
    valor = valor.replace(',', '.');   
    return valor;
}
