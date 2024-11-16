<?php
header("Content-Type: text/html; charset=UTF-8");
$dados = json_decode(file_get_contents('php://input'), true);
$codProtocolo = isset($dados['codProtocolo']) ? htmlspecialchars($dados['codProtocolo']) : '0';

include_once 'db_connect.php';
include_once 'model.php';

if (isset($codProtocolo)) {
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

    $anoFatorFebraban = $acordoFebraban->getAnoFAtor();

    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Protocolo de cálculo: <?php echo $codProtocolo ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 1.1em; 
        }
        
        .infos-protocolo {
            display: flex;
            gap: 15px;
            font-size: 1.15em;
        }

        .infos-protocolo > .col-4, .infos-protocolo > .col-8 {
            padding-right: 10px;
            padding-left: 10px;
        }

        .form-control {
            background-color: #f8f9fa;
            border: none;
            padding: 8px;
            font-size: 1em;
        }

        .selecao-contas {
            margin-top: 15px;
        }

        .resumoResultado {
            font-size: 1em;
        }

        .resumoResultado .row {
            padding-top: 4px;
            padding-bottom: 4px;
        }

        .totais {
            font-weight: bold;
            font-size: 1em;
        }

        .observacoes {
            margin-top: 15px;
            font-size: 0.95em;
            color: #555;
        }

       .tabela-resultados {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }
        .tabela-resultados th, .tabela-resultados td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .tabela-resultados th {
            background-color: #f2f2f2;
        }
        .tabela-resultados tr:hover {
            background-color: #f1f1f1;
        }        
    </style>
</head>
<body>
    <h3>Relatório de Cálculo</h3>
    <br>
    <div class="container mt-1" id="acordo-febraban">
        <div class="container">
            <div class="row infos-protocolo">
                <div>Processo: <?php echo $processo; ?></div>
                <div>Nome: <?php echo $nome; ?></div>
            </div>
        </div>
        <br>            
        <div class="container selecao-contas">
            <table class="display tabela-resultados" style="width:100%">
                <thead class="contas-cabecalho">
                    <tr>
                        <th style="text-align:center;">Conta</th>
                        <th style="text-align:center;">Plano</th>
                        <th style="text-align:center;">Dia Base</th>
                        <th style="text-align:center;">Mês Base</th>
                        <th style="text-align:center;">Saldo Base</th>
                        <th style="text-align:center;">Fator Acordo<sup>1</sup></th>
                        <th style="text-align:center;">Valor Acordo</th>
                    </tr>
                </thead>
                <tbody id="bodyContas">
                    <?php foreach($contas as $conta) { ?>
                    <tr>
                        <td style="text-align:left;"><?php echo htmlspecialchars($conta['conta']) ?></td>
                        <td style="text-align:left;"><?php echo htmlspecialchars($conta['descricao_plano']) ?></td>
                        <td style="text-align:center;"><?php echo htmlspecialchars($conta['aniversario']) ?></td>
                        <td style="text-align:center;"><?php echo date('m/Y', strtotime(htmlspecialchars($conta['data_posicao_saldo_base']))) ?></td>
                        <td style="text-align:right;"><?php echo htmlspecialchars(number_format($conta['saldo_base'], 2, ',', '.')) ?></td>
                        <td style="text-align:center;"><?php echo htmlspecialchars($conta['valor_fator']) ?></td>  
                        <td style="text-align:right;"><?php echo htmlspecialchars(number_format($conta['valor_acordo'], 2, ',', '.')) ?></td>              
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <br>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="resumoResultado">   
                        <div id="observacoes"><b>Observações:</b></br><sup>1</sup> Fator do aditivo do Acordo da FEBRABAN atualizado para <?php echo $anoFatorFebraban['ano_fator']; ?>.</div>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8 resumoResultado">
                    <div class="row">
                        <div class="col-6">SubTotal 1: </div>
                        <div class="col-1">R$</div>                
                        <div class="col-5 d"><?php echo $subtotal1; ?></div>
                    </div>
                    <div class="row">
                        <div class="col-6">Redutor (<?php echo $valorRedutor; ?>%):</div>
                        <div class="col-1">R$</div>
                        <div class="col-5 d"><?php echo $redutor; ?></div>
                    </div>
                    <div class="row totais">
                        <div class="col-6">SubTotal 2: </div>
                        <div class="col-1">R$</div>
                        <div class="col-5 d"><?php echo $subtotal2; ?></div>
                    </div>
                    <div class="row">
                        <div class="col-6">Honorários (10%): </div>
                        <div class="col-1">R$</div>
                        <div class="col-5 d"><?php echo $honorarios; ?></div>
                    </div>
                    <div class="row">
                        <div class="col-6">Honorários FEBRAPO (5%): </div>
                        <div class="col-1">R$</div>
                        <div class="col-5 d"><?php echo $honorariosFebrapo; ?></div>
                    </div>
                    <div class="row totais">
                        <div class="col-6">Total: </div>
                        <div class="col-1">R$</div>
                        <div class="col-5 d"><?php echo $total; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>