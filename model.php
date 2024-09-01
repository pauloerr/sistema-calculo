<?php

include 'db_connect.php';

class AcordoFebraban {

    private $conn;

    public function __construct($mysqli) {
        $this->conn = $mysqli;
    }

    public function sendDadosAcordoFebraban($processo,$nomeParte,$contas,$subTotal1,$redutor,$codRedutor,$subTotal2,$honorarios,$honorariosFebrapo,$total,$usuario,$inconformidade,$anoFator){
        try{
            $resultado = "";
            // Prepare a chamada ao procedimento armazenado
            $sql = "CALL sp_insercao_web_sql(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @resultado)";
            $statement = $this->conn->prepare($sql);

            // Bind de parâmetros
            $statement->bind_param("sssssssssssss", $processo, $nomeParte, $contas, $subTotal1, $redutor, $codRedutor, $subTotal2, $honorarios, $honorariosFebrapo, $total, $usuario, $inconformidade, $anoFator);

            // Executar o procedimento armazenado
            $statement->execute();

            // Obter o resultado
            $result = $this->conn->query("SELECT @resultado AS resultado");
            $row = $result->fetch_assoc();
            $resultado = $row['resultado'];

            // Fechar a conexão
            $statement->close();

            // Retornar o resultado
            return $resultado;
        } catch (Exception $error) {
            echo "Erro: " . $error->getMessage();
            return 'Nâo foi possível salvar. Verifique os parâmetros informados e tente novamente.';
        }
    }

    public function sendAtualizaDadosAcordoFebraban($codProtocolo,$processo,$nomeParte,$contas,$subTotal1,$redutor,$codRedutor,$subTotal2,$honorarios,$honorariosFebrapo,$total,$usuario,$inconformidade,$anoFator){
        try{
            // Prepare a chamada ao procedimento armazenado
            $sql = "CALL sp_atualiza_web_sql(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $statement = $this->conn->prepare($sql);

            // Bind de parâmetros
            $statement->bind_param("ssssssssssssss", $codProtocolo, $processo, $nomeParte, $contas, $subTotal1, $redutor, $codRedutor, $subTotal2, $honorarios, $honorariosFebrapo, $total, $usuario, $inconformidade, $anoFator);

            // Executar o procedimento armazenado
            $statement->execute();

            // Fechar a conexão
            $statement->close();
            
            // Retorna o código deo protocolo
            return $codProtocolo;
        } catch (Exception $error) {
            echo "Erro: " . $error->getMessage();
            return 'Não foi possível atualizar os dados do protocolo. Verifique os parâmetros informados e tente novamente.';
        }
    }
}

if (isset($_POST['action'])) {
    $model = new AcordoFebraban($conn);
    $action = $_POST['action'];

    if ($action === 'salvaDadosAcordoFebrabanSQL') {
        $processo = filter_input(INPUT_POST, 'processo');
        $nomeParte = filter_input(INPUT_POST, 'nomeParte');
        $contas = filter_input(INPUT_POST, 'contas');
        $subTotal1 = filter_input(INPUT_POST, 'subTotal1');
        $redutor = filter_input(INPUT_POST, 'redutor');
        $codRedutor = filter_input(INPUT_POST, 'codRedutor');
        $subTotal2 = filter_input(INPUT_POST, 'subTotal2');
        $honorarios = filter_input(INPUT_POST, 'honorarios');
        $honorariosFebrapo = filter_input(INPUT_POST, 'honorariosFebrapo');
        $total = filter_input(INPUT_POST, 'total');
        $usuario = filter_input(INPUT_POST, 'usuario');
        $inconformidade = filter_input(INPUT_POST, 'inconformidade');    
        $anoFator = filter_input(INPUT_POST, 'anoFator');
        $salva_dados_acordo_febraban = $model->sendDadosAcordoFebraban(
                                                        $processo,
                                                        $nomeParte,
                                                        $contas,
                                                        $subTotal1,
                                                        $redutor,
                                                        $codRedutor,
                                                        $subTotal2,
                                                        $honorarios,
                                                        $honorariosFebrapo,
                                                        $total,
                                                        $usuario,
                                                        $inconformidade,
                                                        $anoFator
                                                        );
        echo json_encode($salva_dados_acordo_febraban);
    }

    if ($action === 'atualizaDadosAcordoFebrabanSQL') {
        $codProtocolo = filter_input(INPUT_POST, 'codProtocolo');
        $processo = filter_input(INPUT_POST, 'processo');
        $nomeParte = filter_input(INPUT_POST, 'nomeParte');
        $contas = filter_input(INPUT_POST, 'contas');
        $subTotal1 = filter_input(INPUT_POST, 'subTotal1');
        $redutor = filter_input(INPUT_POST, 'redutor');
        $codRedutor = filter_input(INPUT_POST, 'codRedutor');
        $subTotal2 = filter_input(INPUT_POST, 'subTotal2');
        $honorarios = filter_input(INPUT_POST, 'honorarios');
        $honorariosFebrapo = filter_input(INPUT_POST, 'honorariosFebrapo');
        $total = filter_input(INPUT_POST, 'total');
        $usuario = filter_input(INPUT_POST, 'usuario');
        $inconformidade = filter_input(INPUT_POST, 'inconformidade');
        $anoFator = filter_input(INPUT_POST, 'anoFator');
        $atualiza_dados_acordo_febraban = $model->sendAtualizaDadosAcordoFebraban(
                                                        $codProtocolo,
                                                        $processo,
                                                        $nomeParte,
                                                        $contas,
                                                        $subTotal1,
                                                        $redutor,
                                                        $codRedutor,
                                                        $subTotal2,
                                                        $honorarios,
                                                        $honorariosFebrapo,
                                                        $total,
                                                        $usuario,
                                                        $inconformidade,
                                                        $anoFator
                                                        );
        echo json_encode($atualiza_dados_acordo_febraban);
    }    
}

?>