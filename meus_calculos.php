<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'db_connect.php'; 
$conn = getConnection();

$sql = "SELECT * FROM calculo WHERE 1=1";
$data = $conn->query($sql);
if ($data === false) {
    echo "Erro na consulta: " . $conn->error;
    exit();
}
$calculos = array();
if ($data->num_rows > 0) {
    while ($row = $data->fetch_assoc()) {
        $calculos[] = $row;
    }
}

$conn->close();

?>

<style>
    .pointer-icon {
        cursor: pointer;
    }
</style>
<div class="container">
    <h1 class="mt-4">Meus Cálculos</h1>
    <table id="tabelaCalculos" class="display">
        <thead>
            <tr>
                <th>Protocolo</th>
                <th>Processo</th>
                <th>Parte</th>
                <th>Ano Fator</th>
                <!--<th>SubTotal 1</th>-->
                <th>Redutor</th>
                <th>SubTotal 2</th>
                <th>Honorários</th>
                <th>Honorários FEBRAPO</th>
                <th>Total</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($calculos as $calculo) {
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($calculo['cod_protocolo']) ?></td>
                    <td><?php echo htmlspecialchars($calculo['processo']) ?></td>
                    <td><?php echo htmlspecialchars($calculo['parte']) ?></td>
                    <td><?php echo htmlspecialchars($calculo['ano_fator']) ?></td>
                    <!--<td><?php //echo htmlspecialchars(number_format($calculo['subtotal1'],2,",",".")) ?></td>-->
                    <td><?php echo htmlspecialchars(number_format($calculo['redutor'],2,",",".")) ?></td>
                    <td><?php echo htmlspecialchars(number_format($calculo['subtotal2'],2,",",".")) ?></td>
                    <td><?php echo htmlspecialchars(number_format($calculo['honorarios'],2,",",".")) ?></td>
                    <td><?php echo htmlspecialchars(number_format($calculo['honorarios_febrapo'],2,",",".")) ?></td>
                    <td><?php echo htmlspecialchars(number_format($calculo['total'],2,",",".")) ?></td>
                    <td><i class="bi bi-eye pointer-icon" onclick="carregarCalculo('<?php echo htmlspecialchars($calculo['cod_protocolo']); ?>')"></i></a>&nbsp;&nbsp;&nbsp;<i class="bi bi-trash3 pointer-icon" onclick="excluirCalculo('<?php echo htmlspecialchars($calculo['cod_identificacao']);?>','<?php echo htmlspecialchars($calculo['cod_protocolo']); ?>')"></i></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function carregarCalculo(codProtocolo) {
        $.ajax({
            url: 'novo_calculo.php',
            type: 'GET',
            data: { cod_protocolo: codProtocolo },
            success: function(response) {
                $('#conteudo').html(response);
            },
            error: function() {
                alert('Erro ao carregar os dados do cálculo.');
            }
        });
    }

    function excluirCalculo(codIdentificacao,codProtocolo) {
        if (confirm("Tem certeza que deseja excluir o registro de protocolo " + codProtocolo + "?")) {
            $.ajax({
                url: 'model.php',
                type: 'POST',
                data: {
                    'action': 'excluirCalculo',
                    'codIdentificacao': codIdentificacao
                },
                success: function (response) {
                    window.location.href = window.location.pathname + "?pagina=meusCalculos";
                },
                error: function(response) {
                    console.error(response)
                    alert('Erro ao tentar excluir registro.');
                }
            })
        }
    }

$(document).ready(function() {
    $('#tabelaCalculos').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "language": {
            "url": "pt_br.json"
        }
    });
});
</script>
