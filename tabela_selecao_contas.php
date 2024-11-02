<?php
	include_once 'db_connect.php'; // Inclui o script de conexão
	$conn = getConnection();
	// Consulta SQL
	$sql = "SELECT * FROM view_tabela_fatores";
	$data = $conn->query($sql);
	if ($data === false) {
		echo "Erro na consulta: " . $conn->error;
		exit();
	}
	$fatoresFebraban = array();
	if ($data->num_rows > 0) {
		while($row = $data->fetch_assoc()) {
			$fatoresFebraban[] = $row;
		}
	}
	// Fechar a conexão
	$conn->close();
	if (!empty($codIdentificacao)) {
		$numero_contas = $qtdeContas;
	} else {
		$numero_contas = 0;
	}
?>
<script>
	function BtnContas() {
		document.getElementById("bodyContas").style.display = '';
		document.getElementById("bodyContas").style.textAlign = "center";

		var tabelaContas = document.getElementById("tabela-contas");
		var numero = tabelaContas.rows.length;
		var rowContas = tabelaContas.insertRow(-1);
		var contaContas = rowContas.insertCell(0);
		var planoContas = rowContas.insertCell(1);
		var aniversarioContas = rowContas.insertCell(2);
		var mesBaseContas = rowContas.insertCell(3);
		var saldoBaseContas = rowContas.insertCell(4);
		var fatorAcordoContas = rowContas.insertCell(5);
		var valorAcordoContas = rowContas.insertCell(6);
		var excluirContas = rowContas.insertCell(7);

		contaContas.innerHTML = "<input class='form-control form-control-sm rounded centraliza-elemento conta' type='text' id='Conta" + numero + "' name='Conta" + numero + "' value='' required onkeyUp='HabilitaCalculoCheckBox(this.id);'/>";
		planoContas.innerHTML = '<select class="custom-select custom-select-lg form-select form-select-sm plano rounded centraliza-elemento" id="Plano' + numero + '" name="Plano' + numero + '" onchange="defineFatorAcordo(this.id); calculaValorAcordo(this.id); validaDiaBaseLote(); resumoResultado()"><option value="0">Selecione...</option><?php foreach ($fatoresFebraban as $plano) { ?><option value="<?php echo $plano['cod_plano'] .'|' .$plano["valor_fator"] .'|' .$plano['data_posicao_saldo_base'] ?>"><?php echo $plano["descricao_plano"] ?></option><?php } ?></select>';
		aniversarioContas.innerHTML = '<input class="datalist-aniversario form-control form-control-sm rounded centraliza-elemento" type="number" id="Aniversario' + numero + '" name="Aniversario' + numero + '" min="1" max="31" maxlength="1" value="1" required onchange="calculaValorAcordo(this.id); validaDiaBaseLote(); resumoResultado()"/>';
		mesBaseContas.innerHTML = '<input class="form-control form-control-sm rounded datalist-extratosaldo centraliza-elemento" type="text" id="MesBase' + numero + '" name="MesBase' + numero + '" disabled>';
		saldoBaseContas.innerHTML = "<input class='form-control form-control-sm rounded datalist-saldo centraliza-elemento' type='text' id='SaldoBase" + numero + "' name='SaldoBase" + numero + "' onkeyup='calculaValorAcordo(this.id); formataSaldo(this.id); validaDiaBaseLote(); resumoResultado()'>";
        fatorAcordoContas.innerHTML = "<input class='form-control form-control-sm rounded datalist-extratosaldo centraliza-elemento' type='text' id='FatorAcordo" + numero + "' name='FatorAcordo" + numero + "' disabled onchange='valorAcordo(this.id)'>";
		valorAcordoContas.innerHTML = "<input class='form-control form-control-sm rounded datalist-extratosaldo centraliza-elemento' type='text' id='ValorAcordo" + numero + "' name='ValorAcordo" + numero + "' disabled onchange='formataSaldo(this.id)'>";
		excluirContas.innerHTML = "<i class='bi bi-dash-circle' id='BtnExcluiContas" + numero + "' name='BtnExcluiContas" + numero + "' onclick='BtnExcluiLinhaContas(this); ContaCliqueExcluiContas(); ReIndexarTabelaContas(); validaDiaBaseLote(); resumoResultado()' style='cursor: pointer;'></i>";

	}

	function BtnExcluiLinhaContas(linha) {
		var i = linha.parentNode.parentNode.rowIndex - 1;
		document.getElementById("bodyContas").deleteRow(i);
	}

	var cliquesExcluiContas = 0;

	function ContaCliqueExcluiContas() {
		cliquesExcluiContas += 1;
		document.getElementById("cliqueExcluiContas").innerHTML = cliquesExcluiContas;
		document.getElementById("hNumExcluiContas").value = cliquesExcluiContas;
	}

	function ReIndexarTabelaContas() {
		var i, n, data, indice, rentab

		var tabelaContas = document.getElementById("tabela-contas");
		var numeroLinha = tabelaContas.rows.length;

		n = 0
		for (i = 0; i < numeroLinha; i++) {
			conta = document.getElementById("Conta" + i);
			plano = document.getElementById("Plano" + i);
			aniversario = document.getElementById("Aniversario" + i);
            mesBase = document.getElementById("MesBase" + i);
			saldoBase = document.getElementById("SaldoBase" + i);
			fatorAcordo = document.getElementById("FatorAcordo" + i);
			valorAcordo = document.getElementById("ValorAcordo" + i);
			excluir = document.getElementById("BtnExcluiContas" + i);
			
			if (conta == null) {
				n = i;
			} else {
				conta.id = "Conta" + n;
				plano.id = "Plano" + n;
				aniversario.id = "Aniversario" + n;
                mesBase.id = "MesBase" + n;
                saldoBase.id = "SaldoBase" + n;
				fatorAcordo.id = "FatorAcordo" + n;
                valorAcordo.id = "ValorAcordo" + n;
				excluir.id = "BtnExcluiContas" + n;
				
				conta.name = "Conta" + n;
				plano.name = "Plano" + n;
				aniversario.name = "Aniversario" + n;
                mesBase.name = "MesBase" + n;
				saldoBase.name = "SaldoBase" + n;
				fatorAcordo.name = "FatorAcordo" + n;
                valorAcordo.name = "ValorAcordo" + n;
				excluir.name = "BtnExcluiContas" + n;
				
				++n;
			}
		}
	}

	var cliquesContas = 1;

	function ContaCliqueContas() {
		cliquesContas += 1;
		document.getElementById("cliqueContas").innerHTML = cliquesContas;
		document.getElementById("hNumContas").value = cliquesContas;
	}

	function montaLinhas() {
        qtdeLinhas = <?php echo $numero_contas ?>;

        var tabelaContas = document.getElementById("tabela-contas");

        for (let i = 1; i < qtdeLinhas; i++) {

            var rowContas = tabelaContas.insertRow(-1);
			var contaContas = rowContas.insertCell(0);
			var planoContas = rowContas.insertCell(1);
			var aniversarioContas = rowContas.insertCell(2);
            var mesBaseContas = rowContas.insertCell(3);  
			var saldoBaseContas = rowContas.insertCell(4);            
            var fatorAcordoContas = rowContas.insertCell(5);
			var valorAcordoContas = rowContas.insertCell(6);
            var excluirContas = rowContas.insertCell(7);
			
			document.getElementById("bodyContas").style.textAlign = "center";

			contaContas.innerHTML = "<input class='form-control form-control-sm rounded centraliza-elemento conta' type='text' id='Conta" + i + "' name='Conta" + i + "' value='' required />";
			planoContas.innerHTML = '<select class="custom-select custom-select-lg form-select form-select-sm plano rounded centraliza-elemento" id="Plano' + i + '" name="Plano' + i + '" onchange="defineFatorAcordo(this.id); calculaValorAcordo(this.id); validaDiaBaseLote(); resumoResultado()" ><option value="0">Selecione...</option><?php foreach ($fatoresFebraban as $plano) { ?><option value="<?php echo $plano['cod_plano'] .'|' .$plano["valor_fator"] .'|' .$plano['data_posicao_saldo_base'] ?>"><?php echo $plano["descricao_plano"] ?></option><?php } ?></select>';
            aniversarioContas.innerHTML = '<input class="datalist-aniversario form-control form-control-sm rounded centraliza-elemento" type="number" id="Aniversario' + i + '" name="Aniversario' + i + '" min="1" max="31" maxlength="1" value="1" required onchange="calculaValorAcordo(this.id); validaDiaBaseLote(); resumoResultado()"/>';
			mesBaseContas.innerHTML = '<input class="form-control form-control-sm rounded datalist-extratosaldo centraliza-elemento" type="text" id="MesBase' + i + '" name="MesBase' + i + '" disabled>';
			saldoBaseContas.innerHTML = "<input class='form-control form-control-sm rounded datalist-saldo centraliza-elemento' type='text' id='SaldoBase" + i + "' name='SaldoBase" + i + "' onkeyup='calculaValorAcordo(this.id); formataSaldo(this.id); validaDiaBaseLote(); resumoResultado()'>";
			fatorAcordoContas.innerHTML = "<input class='form-control form-control-sm rounded datalist-extratosaldo centraliza-elemento' type='text' id='FatorAcordo" + i + "' name='FatorAcordo" + i + "' disabled onchange='valorAcordo(this.id)'>";
			valorAcordoContas.innerHTML = "<input class='form-control form-control-sm rounded datalist-extratosaldo centraliza-elemento' type='text' id='ValorAcordo" + i + "' name='ValorAcordo" + i + "' disabled onchange='formataSaldo(this.id)'>";
			excluirContas.innerHTML = "<i class='bi bi-dash-circle' id='BtnExcluiContas" + i + "' name='BtnExcluiContas" + i + "' onclick='BtnExcluiLinhaContas(this); ContaCliqueExcluiContas(); ReIndexarTabelaContas(); validaDiaBaseLote(); resumoResultado()' style='cursor: pointer;'></i>";

		}

		contas();
	}


	function contas() {
        const contas = <?php echo json_encode($contas); ?>;
        contas.forEach((conta, index) => {

			var dataSaldoBase = conta.data_posicao_saldo_base;
			var partesData = dataSaldoBase.split('-');
			var dataFormatada = partesData[1] + '/' + partesData[0];


            document.getElementById("Conta" + index).value = conta.conta;
            document.getElementById("Plano" + index).value = conta.cod_plano + "|" + conta.valor_fator + "|" + conta.data_posicao_saldo_base;
            document.getElementById("Aniversario" + index).value = conta.aniversario;
			document.getElementById("MesBase" + index).value = dataFormatada;
			document.getElementById("SaldoBase" + index).value = formataSaldoBR(conta.saldo_base);            
			document.getElementById("FatorAcordo" + index).value = conta.valor_fator.replace(/\./g, ',');
            document.getElementById("ValorAcordo" + index).value = formataSaldoBR(conta.valor_acordo);


        });
	}


	montaLinhas();

</script>

<div style="display: none;">Total de campos de contas: <a id="cliqueContas" name="cliqueContas">0</a></div>
<div style="display: none;">Total de campos de contas excluídos: <a id="cliqueExcluiContas" name="cliqueExcluiContas">0</a></div>
<input type="hidden" id="hNumContas" name="hNumContas" value="" />
<input type="hidden" id="hNumExcluiContas" name="hNumExcluiContas" value="" />

<table id="tabela-contas" class="display" style="width:100%">
	<thead id="headContas" class="contas-cabecalho">
		<tr>
			<th>Conta</th>
            <th>Plano</th>
            <th>Dia Base</th>
			<th>Mês Base</th>
			<th>Saldo Base</th>
			<th>Fator Acordo<sup>1</sup></th>
			<th>Valor Acordo</th>
			<th><i class="bi bi-plus-circle" id="BtnCliqueContas" onclick="BtnContas(); ContaCliqueContas(); ReIndexarTabelaContas()" style="cursor: pointer;"></i></th>
		</tr>
	</thead>
	<tbody id="bodyContas">
		<tr>
			<td style="text-align:center;">
				<input class="form-control form-control-sm rounded centraliza-elemento conta" type="text" id="Conta0" name="Conta0" value="">
			</td>
			<td style="text-align:center;">
				<select class='custom-select custom-select-lg form-select form-select-sm plano rounded centraliza-elemento' id="Plano0" name="Plano0" onchange='defineFatorAcordo(this.id); calculaValorAcordo(this.id); validaDiaBaseLote(); resumoResultado()'>
					<option value="0">Selecione...</option><?php foreach ($fatoresFebraban as $plano) { ?><option value="<?php echo $plano['cod_plano'] .'|' .$plano['valor_fator'] .'|' .$plano['data_posicao_saldo_base']  ?>"><?php echo $plano['descricao_plano'] ?></option><?php } ?>
				</select>
			</td>
			<td style="text-align:center;">
				<input class="datalist-aniversario form-control form-control-sm rounded centraliza-elemento" type="number" id="Aniversario0" name="Aniversario0" min="1" max="31" maxlength="1" value="1" onchange="calculaValorAcordo(this.id); validaDiaBaseLote(); resumoResultado()">
			</td>
			<td style="text-align:center;">
				<input class="form-control form-control-sm rounded datalist-extratosaldo centraliza-elemento" type="text" id="MesBase0" name="MesBase0" disabled>
			</td>
            <td style="text-align:center;">
				<input class="form-control form-control-sm rounded datalist-saldo centraliza-elemento" type="text" id="SaldoBase0" name="SaldoBase0" onkeyup='calculaValorAcordo(this.id); formataSaldo(this.id); validaDiaBaseLote(); resumoResultado()'>
			</td>
			<td style="text-align:center;">
				<input class="form-control form-control-sm rounded datalist-extratosaldo centraliza-elemento" type="text" id="FatorAcordo0" name="FatorAcordo0" onchange="valorAcordo(this.id)" disabled>
			</td>  
			<td style="text-align:center;">
				<input class="form-control form-control-sm rounded datalist-extratosaldo centraliza-elemento" type="text" id="ValorAcordo0" name="ValorAcordo0" onchange='formataSaldo(this.id)' disabled>
			</td>              
			<td>
				<i class='bi bi-dash-circle invisible' id='BtnExcluiContas0' name="BtnExcluiContas0" onclick='BtnExcluiLinhaContas(this); ContaCliqueExcluiContas(); ReIndexarTabelaContas(); validaDiaBaseLote(); resumoResultado()' style='cursor: pointer;'></i>
			</td>
		</tr>
	</tbody>
</table>

<script>


	function verificaPlanos(){
		//Conta número de linhas da tabela de contas
		let numLinhas = $('#tabela-contas tr').length;
		let arrayCodigoPlano;
		let stringCodigosPlanos = '';
		let arrayCodigosPlanos = '';
		//console.log(numLinhas);
		for (let i = 0; i < (numLinhas - 1); i++) {
			let idPlano = 'Plano' + i;
			let codPlano = document.getElementById(idPlano).value;
			let arrayCodigoPlano = codPlano.split("|");
			stringCodigosPlanos = stringCodigosPlanos + '|' + arrayCodigoPlano[0];
		}
		//Elimina o primeiro caractere '|'
		stringCodigosPlanos = stringCodigosPlanos.substring(1);
		let mensagem = '';
		if (stringCodigosPlanos.indexOf("3") == -1 && ( stringCodigosPlanos.indexOf("1") >= 0 || stringCodigosPlanos.indexOf("2") >= 0 || stringCodigosPlanos.indexOf("4") >= 0 ) ) {
			//console.log('planos bresser, verão e/ou collor2');
			for (let i = 0; i < (numLinhas - 1); i++) {
			 	calculaValorAcordo('linha' + i);
			}
		} else if (stringCodigosPlanos.indexOf("3") >= 0 && ( stringCodigosPlanos.indexOf("1") == -1 && stringCodigosPlanos.indexOf("2") == -1 && stringCodigosPlanos.indexOf("4") == -1 ) ) {
			//console.log('plano collor 1');
			for (let i = 0; i < (numLinhas - 1); i++) {
				calculaValorAcordo('linha' + i);
			}
		} else {
			//console.log('collor 1 com outro plano');
			mensagem = 'Conforme entendimento firmado pelo Superior Tribunal de Justiça (STJ), por meio dos Recursos Especiais (repetitivos) nº 1.107.201 e nº 1.147.595, não há qualquer pagamento a ser efetuado para o Plano Collor I (1990) para ações que contemplam também outros planos.';
			for (let i = 0; i < (numLinhas - 1); i++) {
				let idPlano = 'Plano' + i;
				let codPlano = document.getElementById(idPlano).value;
				let arrayCodigoPlano = codPlano.split("|");
				if (arrayCodigoPlano[0] == '3') {
					let idValorAcordo = 'ValorAcordo' + i;
					document.getElementById(idValorAcordo).value = '0,00';
				}
			}
		}
		return mensagem;
	}

	function validaDiaBaseLote() {
		let mensagem = '';
		let bresserVerao = '';
		let collor2 = '';
		//Conta número de linhas da tabela de contas
		let numLinhas = $('#tabela-contas tr').length;
		for (let i = 0; i < (numLinhas - 1); i++) {
			numero = i;
			//Pega valor Dia Base
			let idDiaBase = 'Aniversario' + numero;
			let diaBase = document.getElementById(idDiaBase).value;
			//Pega o código do plano
			let idPlano = 'Plano' + numero;
			let stringPlano = document.getElementById(idPlano).value;
			let arrayPlano = stringPlano.split("|");
			let codPlano = Number(arrayPlano[0]);
			//Valida os dias conforme o código do plano
			if ((codPlano == 1 || codPlano == 2) && (diaBase > 15)) {
				$('#' + idDiaBase).css({'color':'red','font-weight':'bold'});
				//mensagem de dia invalido para os planos bresser e verao
				bresserVerao = '<li id="inconformidade1">Equivale a zero o valor base de contas com aniversário na segunda quinzena para os planos Bresser e Verão.</li>';
			} else if ((codPlano == 4) && (diaBase == 1 || diaBase == 2)) {
				$('#' + idDiaBase).css({'color':'red','font-weight':'bold'});
				collor2 =  '<li id="inconformidade2">Equivale a zero o valor base de contas com aniversário nos dias 1º e 2 para o plano Collor II.</li>';
			} else {
				$('#' + idDiaBase).css({'color':'#48586C','font-weight':'normal'});
			}
			mensagem = bresserVerao + collor2;
		}
		//console.log('verifica planos');
		let comparaPlanos = verificaPlanos();
		if (comparaPlanos != ''){
			comparaPlanos = '<li id="inconformidade3">' + comparaPlanos + '</li>'
		}
		document.getElementById('inconformidadePlanos').innerHTML = mensagem + comparaPlanos;
	}

	function resumoResultado() {
		let numLinhas = $('#tabela-contas tr').length;
		let subTotal1 = '0';

		// Criar um teste de condição para verificar se existe apenas planos Collor I.
		//Conta número de linhas da tabela de contas
		let apenasCollorI = 1;
		let saldoBasePlano = 0;
		let saldoBaseConta;
		for (let i = 0; i < (numLinhas - 1); i++) {
			//Totaliza o valor do saldo base de todas a contas
			saldoBaseConta = document.getElementById('SaldoBase' + i).value;
			//console.log(' saldo base da conta eh:' + saldoBaseConta + '|');
			if (saldoBaseConta == ''){
				saldoBaseConta = 0;
				//console.log(' saldo base da conta eh:' + saldoBaseConta + '|');
			} else {
				saldoBaseConta = saldoBaseConta.replace(".", "");
				saldoBaseConta = saldoBaseConta.replace(",", ".");
				saldoBaseConta = Number(saldoBaseConta);
			}
			saldoBasePlano = saldoBasePlano + saldoBaseConta;
			//Pega o código do plano
			let idPlano = 'Plano' + i;
			let stringPlano = document.getElementById(idPlano).value;
			let arrayPlano = stringPlano.split("|");
			let codPlano = Number(arrayPlano[0]);
			//Valida os dias conforme o código do plano
			if (codPlano != 3) {
				apenasCollorI = 0
			}
		}		
		//console.log('o valor do saldo base plano eh:' + saldoBasePlano) ;
		if (saldoBasePlano == 0) {
			saldoBasePlano = '0';
		}
		if (apenasCollorI == 1 && saldoBasePlano < 84817.64) {

			if (saldoBasePlano < 30000) {
				console.log('saldo base menor que 30 mil ' + saldoBasePlano);
				subTotal1 = 1000;
			} else if (saldoBasePlano >= 30000 && saldoBasePlano < 50000) {
				console.log('saldo base maior que 30 mil e menor que 50 mil ' + saldoBasePlano);
				subTotal1 = 2000;
			} else if (saldoBasePlano >= 50000 && saldoBasePlano < 84817.64) {
				console.log('saldo base maior que 50 mil e menor que 84817,64 mil ' + saldoBasePlano);
				subTotal1 = 3000;
			} 		

		} else {		
			for (let i = 0; i < (numLinhas - 1); i++) {
				let valorConta = $('#ValorAcordo' + i).val();
				subTotal1 = Number(subTotal1) + Number(formataSaldoUS(valorConta));
			}
		}

		//document.getElementById('subTotal1').value = Number(subTotal1);
		subTotal1 = subTotal1.toFixed(2);
		document.getElementById('subTotal1').innerHTML = formataSaldoBR(subTotal1.toString());

		//Redutor
		//Conta número de linhas da tabela de contas
		let arrayCodigoPlano;
		let stringCodigosPlanos = '';
		let arrayCodigosPlanos = '';
		//console.log(numLinhas);
		for (let i = 0; i < (numLinhas - 1); i++) {
			let idPlano = 'Plano' + i;
			let codPlano = document.getElementById(idPlano).value;
			let arrayCodigoPlano = codPlano.split("|");
			stringCodigosPlanos = stringCodigosPlanos + '|' + arrayCodigoPlano[0];
		}
		//Elimina o primeiro caractere '|'
		stringCodigosPlanos = stringCodigosPlanos.substring(1);
		let mensagem = '';
		let redutor;
		let redutorPerc;
		let valorReduzido = 0;
		if ((stringCodigosPlanos.indexOf("3") == -1) || (stringCodigosPlanos.indexOf("3") >= 0 && (stringCodigosPlanos.indexOf("1") >= 0 || stringCodigosPlanos.indexOf("2") >= 0 || stringCodigosPlanos.indexOf("4") >= 0))) {
			if (subTotal1 > 0 && subTotal1 <= 5000) {
				redutor = 0;
				redutorPerc = 'Redutor (0%):'
			} else if (subTotal1 > 5000 && subTotal1 <= 10000) {
				redutor = 8;
				redutorPerc = 'Redutor (8%):'
			} else if (subTotal1 > 10000 && subTotal1 <= 20000) {
				redutor = 14;
				redutorPerc = 'Redutor (14%):'
			} else if (subTotal1 > 20000) {
				redutor = 19;
				redutorPerc = 'Redutor (19%):'
			} else {
				redutor = 0;
				redutorPerc = 'Redutor (0%):'			
			}
			valorReduzido = subTotal1 * (redutor / 100);
			valorReduzido = valorReduzido.toFixed(2);
			document.getElementById('redutor').innerHTML = formataSaldoBR(valorReduzido.toString());
			document.getElementById('redutor %').innerHTML = redutorPerc;	
		} else {
			valorReduzido = 0;
			valorReduzido = valorReduzido.toFixed(2);
			redutorPerc = 'Redutor (0%)';
			document.getElementById('redutor').innerHTML = formataSaldoBR(valorReduzido.toString());
			document.getElementById('redutor %').innerHTML = redutorPerc;				
		}	

		//SubTotal 2
		let subTotal2 = 0;
		subTotal2 = Number(subTotal1) - Number(valorReduzido);
		subTotal2 = subTotal2.toFixed(2);
		document.getElementById('subTotal2').innerHTML = formataSaldoBR(subTotal2.toString());	
		$('#subTotal2').val(subTotal2);

		//Honorários 10%
		let honorarios = 0;
		honorarios = Number(subTotal2) * 0.1;
		honorarios = honorarios.toFixed(2);
		document.getElementById('honorarios').innerHTML = formataSaldoBR(honorarios.toString());	

		//Honorários 5% - FEBRAPO
		let honorariosFebrapo = 0;
		honorariosFebrapo = Number(subTotal2) * 0.05;
		honorariosFebrapo = honorariosFebrapo.toFixed(2);
		document.getElementById('honorariosFebrapo').innerHTML = formataSaldoBR(honorariosFebrapo.toString());	

		//Valor Total
		let valorTotal = 0;
		valorTotal = Number(subTotal2) + Number(honorarios) + Number(honorariosFebrapo);
		valorTotal = valorTotal.toFixed(2);
		document.getElementById('total').innerHTML = formataSaldoBR(valorTotal.toString());	
		
	}

	function defineFatorAcordo(codId){
		//Define variável para identificar a linha que requisitou a chamada
		//let numero = codId.slice(-1);
		let numero = codId.replace(/[^\d]/g, "");
		//Atribui o valor do fator do plano para o campo Fator Acordo		
		let idFatorAcordo = 'FatorAcordo' + numero;
		let idPlano = 'Plano' + numero;
		let fatorPlano = document.getElementById(idPlano).value;
		let arrayFatorPlano = fatorPlano.split("|");
		document.getElementById(idFatorAcordo).value = Number(arrayFatorPlano[1]).toLocaleString("pt-BR", {minimumFractionDigits: 5});
		//Atribui o valor do mês/ano do plano para o campo Mês Base
		let idMesBase = 'MesBase' + numero;
		let mesAno = arrayFatorPlano[2].split("-");
		document.getElementById(idMesBase).value = mesAno[1] + "/" + mesAno[0];
	}
	function calculaValorAcordo(codId){
		//Define variável para identificar a linha que requisitou a chamada
		//let numero = codId.slice(-1);
		let numero = codId.replace(/[^\d]/g, "");
		//Pega valor Saldo Base
		let idSaldoBase = 'SaldoBase' + numero;
		let saldoBase = document.getElementById(idSaldoBase).value;
		//Pega valor Dia Base
		let idDiaBase = 'Aniversario' + numero;
		let diaBase = document.getElementById(idDiaBase).value;
		//Pega valor do Fator Acordo
		let idPlano = 'Plano' + numero;	
		let fatorPlano = document.getElementById(idPlano).value;
		let arrayFatorPlano = fatorPlano.split("|");
		//Verifica dia base conforme o código do plano
		//Verifica dia base para os planos Bresser e Verão
		if ((arrayFatorPlano[0] == 1 || arrayFatorPlano[0] == 2) && (diaBase > 15)) {
			saldoBase = '0,00';
		//Verifica dia base para o Collor II
		} else if ((arrayFatorPlano[0] == 4) && (diaBase == 1 || diaBase == 2)) {
			saldoBase = '0,00';
		} 
		//Atribui o valor calculado par ao campo Valor Acordo
		let idValorAcordo = 'ValorAcordo' + numero;
		let valorCalculado = (Number(formataSaldoUS(saldoBase)) * Number(arrayFatorPlano[1])).toFixed(2);
		
		// Criar um teste de condição para verificar se existe apenas planos Collor I. 
		// Se existir apenas contas com plano Collor I, totalizar o saldo base de todos as contas e conferir o valor
		// final se está no enquadramento abaixo. Se o valor tot$contas al for maior que 84817,64, manter o valor calculado anteriormente
		// se estiver dentro de alguma condição abaixo, zerar o valor calculado e na função resumoResultado() criar uma
		// verificação do valor total do saldo base e atribuir o valor para o subtotal1 conforme a tabela abaixo.

		//Conta número de linhas da tabela de contas
		let numLinhas = $('#tabela-contas tr').length;
		let apenasCollorI = 1;
		let saldoBasePlano = 0;
		let saldoBaseConta;
		for (let i = 0; i < (numLinhas - 1); i++) {
			//Totaliza o valor do saldo base de todas a contas
			saldoBaseConta = document.getElementById('SaldoBase' + i).value;
			//console.log(' saldo base da conta eh:' + saldoBaseConta + '|');
			if (saldoBaseConta == ''){
				saldoBaseConta = 0;
				//console.log(' saldo base da conta eh:' + saldoBaseConta + '|');
			} else {
				saldoBaseConta = saldoBaseConta.replace(".", "");
				saldoBaseConta = saldoBaseConta.replace(",", ".");
				saldoBaseConta = Number(saldoBaseConta);
			}
			saldoBasePlano = saldoBasePlano + saldoBaseConta;
			//Pega o código do plano
			let idPlano = 'Plano' + i;
			let stringPlano = document.getElementById(idPlano).value;
			let arrayPlano = stringPlano.split("|");
			let codPlano = Number(arrayPlano[0]);
			//Valida os dias conforme o código do plano
			if (codPlano != 3) {
				apenasCollorI = 0
			}
		}		
		//console.log('o valor do saldo base plano eh:' + saldoBasePlano) ;
		if (saldoBasePlano == 0) {
			saldoBasePlano = '0';
		}
		if (apenasCollorI == 1 && saldoBasePlano < 84817.64) {
			//console.log('Existem apenas contas do plano Collor I');
			//console.log('Total ' + saldoBasePlano + ' menor que 84817,64');
			valorCalculado = '0.00';
		}

		//if (arrayFatorPlano[0] == 3) {
		// 	console.log('plano collor 1');
		// 	if (Number(formataSaldoUS(saldoBase)) < 30000) {
		// 		console.log('saldo base menor que 30 mil ' + Number(formataSaldoUS(saldoBase)));
		// 		valorCalculado = '100000';
		// 	} else if (Number(formataSaldoUS(saldoBase)) >= 30000 && Number(formataSaldoUS(saldoBase)) < 50000) {
		// 		console.log('saldo base maior que 30 mil e menor que 50 mil ' + Number(formataSaldoUS(saldoBase)));
		// 		valorCalculado = '200000';
		// 	} else if (Number(formataSaldoUS(saldoBase)) >= 50000 && Number(formataSaldoUS(saldoBase)) < 84817.64) {
		// 		console.log('saldo base maior que 50 mil e menor que 84817,64 mil ' + Number(formataSaldoUS(saldoBase)));
		// 		valorCalculado = '300000';
		// 	} else {
		// 		console.log('saldo base maior que 84817,64 mil ' + Number(formataSaldoUS(saldoBase)));
		// 		valorCalculado = (Number(formataSaldoUS(saldoBase)) * Number(arrayFatorPlano[1])).toFixed(2);
		// 	}
		// }
		
		//if (valorCalculado > 0) {
			document.getElementById(idValorAcordo).value = formataSaldoBR(valorCalculado);
		//}echo $numero_contas;
	}
	function formataSaldo(id) {
		valor = document.getElementById(id).value;
		//console.log('formataSaldo - valor do campo:' + valor);
		//Remove qualquer caractere não numérico
		valor = valor.replace(/[^\d]/g, "");
		//console.log(valor);
		//Separa o numero em dois
		valorInteiro = valor.slice(0, -2);
		//console.log(valorInteiro);
		valorDecimal = valor.slice(-2);
		//console.log(valorDecimal);
		//Adiciona os separadores de milhares
		valorInteiro = valorInteiro.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
		//Junta os número e adiciona o separador decimal
		valor = valorInteiro + valorDecimal;
		valor = valor.replace(/(\d{2})$/, ",$1");
		//Atualiza o valor do campo de entrada com a moeda formatada
		document.getElementById(id).value = valor;
	}	
	function formataSaldoBR(valor) {
		//Remove qualquer caractere não numérico
		valor = valor.replace(/[^\d]/g, "");
		//console.log(valor);
		//Separa o numero em dois
		valorInteiro = valor.slice(0, -2);
		//console.log(valorInteiro);
		valorDecimal = valor.slice(-2);
		//console.log(valorDecimal);
		//Adiciona os separadores de milhares
		valorInteiro = valorInteiro.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
		//Junta os número e adiciona o separador decimal
		valor = valorInteiro + valorDecimal;
		valor = valor.replace(/(\d{2})$/, ",$1");
		//Atualiza o valor do campo de entrada com a moeda formatada
		return valor;
	}		
	function formataSaldoUS(valor) {
		//Remove qualquer caractere não numérico
		valor = valor.replace(/[^\d]/g, "");
		//console.log(valor);
		//Separa o numero em dois
		valorInteiro = valor.slice(0, -2);
		//console.log(valorInteiro);
		valorDecimal = valor.slice(-2);
		//console.log(valorDecimal);
		//Adiciona os separadores de milhares
		valorInteiro = valorInteiro.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1");
		//Junta os número e adiciona o separador decimal
		valor = valorInteiro + valorDecimal;
		valor = valor.replace(/(\d{2})$/, ".$1");
		//Atualiza o valor do campo de entrada com a moeda formatada
		return valor;
	}	
</script>