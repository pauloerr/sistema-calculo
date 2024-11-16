<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once 'db_connect.php';

class AcordoFebraban {

    private $conn;
    private $codUsuario;
    private $usuario;

    public function __construct($mysqli) {
        $this->conn = $mysqli;
        $this->codUsuario = $_SESSION['user_id'] ?? null;
        $this->usuario = $_SESSION['user_name'] ?? null;
    }

    public function sendDadosAcordoFebraban($processo,$nomeParte,$contas,$subTotal1,$redutor,$codRedutor,$subTotal2,$honorarios,$honorariosFebrapo,$total,$inconformidade,$anoFator){
        try{
            $resultado = "";
            $sql = "CALL sp_insercao_web_sql(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @resultado)";
            $statement = $this->conn->prepare($sql);
            $statement->bind_param("ssssssssssssss", $processo, $nomeParte, $contas, $subTotal1, $redutor, $codRedutor, $subTotal2, $honorarios, $honorariosFebrapo, $total, $this->codUsuario, $this->usuario, $inconformidade, $anoFator);
            $statement->execute();

            $result = $this->conn->query("SELECT @resultado AS resultado");
            $row = $result->fetch_assoc();
            $resultado = $row['resultado'];
            $statement->close();

            return $resultado;
        } catch (Exception $error) {
            echo "Erro: " . $error->getMessage();
            return 'Nâo foi possível salvar. Verifique os parâmetros informados e tente novamente.';
        }
    }

    public function sendAtualizaDadosAcordoFebraban($codProtocolo,$processo,$nomeParte,$contas,$subTotal1,$redutor,$codRedutor,$subTotal2,$honorarios,$honorariosFebrapo,$total,$inconformidade,$anoFator){
        try{
            $sql = "CALL sp_atualiza_web_sql(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $statement = $this->conn->prepare($sql);
            $statement->bind_param("sssssssssssssss", $codProtocolo, $processo, $nomeParte, $contas, $subTotal1, $redutor, $codRedutor, $subTotal2, $honorarios, $honorariosFebrapo, $total, $this->codUsuario, $this->usuario, $inconformidade, $anoFator);
            $statement->execute();
            $statement->close();
            return $codProtocolo;
        } catch (Exception $error) {
            echo "Erro: " . $error->getMessage();
            return 'Não foi possível atualizar os dados do protocolo. Verifique os parâmetros informados e tente novamente.';
        }
    }

    public function getCalculos($codUsuario,$codProtocolo = null,$dataInicial = null,$dataFinal = null){
        try{
            $query = "SELECT * FROM calculo WHERE 1=1";
            if (!empty($dataInicial)) {
                $query .= " AND [data_hora_inc] >= ?";
            }
            if (!empty($dataFinal)) {
                $query .= " AND [data_hora_inc] <= ?";
            }
            if (!empty($codProtocolo)) {
                $query .= " AND [cod_protocolo] = ?";
            }
            $stmt = $this->conn->prepare($query);
            $params = [];
            $types = '';

            if (!empty($dataInicial)) {
                $params[] = $dataInicial;
                $types .= 's';
            }
            if (!empty($dataFinal)) {
                $params[] = $dataFinal;
                $types .= 's';
            }
            if (!empty($codProtocolo)) {
                $params[] = $codProtocolo;
                $types .= 's';
            }
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $resultados = [];
            while ($row = $result->fetch_assoc()) {
                $resultados[] = $row;
            }
            $stmt->close();
            return $resultados;

        } catch (Exception $error) {
            echo "Erro ao buscar cálculos: " . $error->getMessage();
            return [];            
        }
    }

    public function getCalculo($codProtocolo){
        try{
            $query = "SELECT * FROM calculo WHERE cod_protocolo = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $codProtocolo);
            $stmt->execute();
            $result = $stmt->get_result();
            $resultados = [];
            while ($row = $result->fetch_assoc()) {
                $resultados[] = $row;
            }
            $stmt->close();
            return $resultados;

        } catch (Exception $error) {
            echo "Erro ao buscar cálculos: " . $error->getMessage();
            return [];            
        }
    }    

    public function getContasCalculo($codIdentificacao){
        try{
            $query = "SELECT * FROM view_historico_contas WHERE cod_identificacao = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $codIdentificacao);
            $stmt->execute();
            $result = $stmt->get_result();
           $resultados = [];
            while ($row = $result->fetch_assoc()) {
                $resultados[] = $row;
            }
            $stmt->close();
            return $resultados;

        } catch (Exception $error) {
            echo "Erro ao buscar cálculos: " . $error->getMessage();
            return [];            
        }
    }    
    
    public function getRedutor($codRedutor){
        try{
            $query = "SELECT * FROM redutor WHERE cod_redutor = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $codRedutor);
            $stmt->execute();
            $result = $stmt->get_result();
            $resultados = [];
            while ($row = $result->fetch_assoc()) {
                $resultados[] = $row;
            }
            $stmt->close();
            return $resultados;

        } catch (Exception $error) {
            echo "Erro ao buscar cálculos: " . $error->getMessage();
            return [];            
        }
    }     

    public function deleteCalculo($codIdentificacao){
        try{
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $queryCalculo = "DELETE FROM calculo WHERE cod_identificacao = ?";
            $stmtCalculo = $this->conn->prepare($queryCalculo);
            $stmtCalculo->bind_param("i", $codIdentificacao);
            $stmtCalculo->execute();
            $stmtCalculo->close();

            $queryContas = "DELETE FROM contas WHERE cod_identificacao = ?";
            $stmtContas = $this->conn->prepare($queryContas);
            $stmtContas->bind_param("i", $codIdentificacao);
            $stmtContas->execute();
            $stmtContas->close();

            echo "Registros excluídos com sucesso das tabelas 'calculo' e 'contas'!";
        } catch (mysqli_sql_exception $e) {
            echo "Erro ao excluir o registro: " . $e->getMessage();
        } finally {
            $this->conn->close();
        }
    }

    public function getAnoFator(){
        try {
            $query = "SELECT * FROM view_ano_fator";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $resultados = array();
            if ($result->num_rows > 0) {
                $resultados = $result->fetch_assoc();
            }            
            $stmt->close();
            return $resultados;
        } catch (Exception $error) {
            echo "Erro ao buscar o fator: " . $error->getMessage();
            return [];            
        }

    }
}

if (isset($_POST['action'])) {
    $conn = getConnection();
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
                                                        $inconformidade,
                                                        $anoFator
                                                        );
        echo json_encode($atualiza_dados_acordo_febraban);
    }    

    if ($action === 'excluirCalculo' && isset($_POST['codIdentificacao'])) {
        $model->deleteCalculo($_POST['codIdentificacao']);
    }
}
?>