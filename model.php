<?php
// Ativar exibição de erros
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
            // Prepare a chamada ao procedimento armazenado
            $sql = "CALL sp_insercao_web_sql(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @resultado)";
            $statement = $this->conn->prepare($sql);

            // Bind de parâmetros
            $statement->bind_param("ssssssssssssss", $processo, $nomeParte, $contas, $subTotal1, $redutor, $codRedutor, $subTotal2, $honorarios, $honorariosFebrapo, $total, $this->codUsuario, $this->usuario, $inconformidade, $anoFator);

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

    public function sendAtualizaDadosAcordoFebraban($codProtocolo,$processo,$nomeParte,$contas,$subTotal1,$redutor,$codRedutor,$subTotal2,$honorarios,$honorariosFebrapo,$total,$inconformidade,$anoFator){
        try{
            // Prepare a chamada ao procedimento armazenado
            $sql = "CALL sp_atualiza_web_sql(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $statement = $this->conn->prepare($sql);

            // Bind de parâmetros
            $statement->bind_param("sssssssssssssss", $codProtocolo, $processo, $nomeParte, $contas, $subTotal1, $redutor, $codRedutor, $subTotal2, $honorarios, $honorariosFebrapo, $total, $this->codUsuario, $this->usuario, $inconformidade, $anoFator);

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

    public function getCalculos($codUsuario,$codProtocolo = null,$dataInicial = null,$dataFinal = null){
        try{
            // Montar a query base
            $query = "SELECT * FROM calculo WHERE 1=1";

            // Adicionar filtros opcionais
            if (!empty($dataInicial)) {
                $query .= " AND [data_hora_inc] >= ?";
            }
            if (!empty($dataFinal)) {
                $query .= " AND [data_hora_inc] <= ?";
            }
            if (!empty($codProtocolo)) {
                $query .= " AND [cod_protocolo] = ?";
            }

            // Preparar a query com mysqli
            $stmt = $this->conn->prepare($query);

            // Associar os parâmetros de forma condicional
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

            // Associar parâmetros, se houver
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            // Executar a query
            $stmt->execute();

            // Obter o resultado
            $result = $stmt->get_result();

            // Armazenar os resultados em um array associativo
            $resultados = [];
            while ($row = $result->fetch_assoc()) {
                $resultados[] = $row;
            }

            // Fechar a instrução e retornar os resultados
            $stmt->close();
            return $resultados;

        } catch (Exception $error) {
            echo "Erro ao buscar cálculos: " . $error->getMessage();
            return [];            
        }
    }

    public function getCalculo($codProtocolo){
        try{
           // Montar a query base sem colchetes
            $query = "SELECT * FROM calculo WHERE cod_protocolo = ?"; // Usando um parâmetro preparado

            // Preparar a query com mysqli
            $stmt = $this->conn->prepare($query);

            // Associar o parâmetro
            $stmt->bind_param('s', $codProtocolo); // Supondo que cod_protocolo é do tipo string

            // Executar a query
            $stmt->execute();

            // Obter o resultado
            $result = $stmt->get_result();

            // Armazenar os resultados em um array associativo
            $resultados = [];
            while ($row = $result->fetch_assoc()) {
                $resultados[] = $row;
            }

            // Fechar a instrução e retornar os resultados
            $stmt->close();
            return $resultados;

        } catch (Exception $error) {
            echo "Erro ao buscar cálculos: " . $error->getMessage();
            return [];            
        }
    }    

    public function getContasCalculo($codIdentificacao){
        try{
           // Montar a query base sem colchetes
            $query = "SELECT * FROM view_historico_contas WHERE cod_identificacao = ?"; // Usando um parâmetro preparado

            // Preparar a query com mysqli
            $stmt = $this->conn->prepare($query);

            // Associar o parâmetro
            $stmt->bind_param('s', $codIdentificacao); // Supondo que cod_protocolo é do tipo string

            // Executar a query
            $stmt->execute();

            // Obter o resultado
            $result = $stmt->get_result();

            // Armazenar os resultados em um array associativo
            $resultados = [];
            while ($row = $result->fetch_assoc()) {
                $resultados[] = $row;
            }

            // Fechar a instrução e retornar os resultados
            $stmt->close();
            return $resultados;

        } catch (Exception $error) {
            echo "Erro ao buscar cálculos: " . $error->getMessage();
            return [];            
        }
    }    
    
    public function getRedutor($codRedutor){
        try{
           // Montar a query base sem colchetes
            $query = "SELECT * FROM redutor WHERE cod_redutor = ?"; // Usando um parâmetro preparado

            // Preparar a query com mysqli
            $stmt = $this->conn->prepare($query);

            // Associar o parâmetro
            $stmt->bind_param('s', $codRedutor); // Supondo que cod_protocolo é do tipo string

            // Executar a query
            $stmt->execute();

            // Obter o resultado
            $result = $stmt->get_result();

            // Armazenar os resultados em um array associativo
            $resultados = [];
            while ($row = $result->fetch_assoc()) {
                $resultados[] = $row;
            }

            // Fechar a instrução e retornar os resultados
            $stmt->close();
            return $resultados;

        } catch (Exception $error) {
            echo "Erro ao buscar cálculos: " . $error->getMessage();
            return [];            
        }
    }     

    public function deleteCalculo($codIdentificacao){
        try{
            // Configura o modo de exceção para o mysqli
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            // Query para excluir o registro na tabela 'calculo'
            $queryCalculo = "DELETE FROM calculo WHERE cod_identificacao = ?";
            $stmtCalculo = $this->conn->prepare($queryCalculo);
            $stmtCalculo->bind_param("i", $codIdentificacao);
            $stmtCalculo->execute();
            $stmtCalculo->close();

            // Query para excluir o registro na tabela 'contas'
            $queryContas = "DELETE FROM contas WHERE cod_identificacao = ?";
            $stmtContas = $this->conn->prepare($queryContas);
            $stmtContas->bind_param("i", $codIdentificacao);
            $stmtContas->execute();
            $stmtContas->close();

            echo "Registros excluídos com sucesso das tabelas 'calculo' e 'contas'!";
        } catch (mysqli_sql_exception $e) {
            // Em caso de erro, exibe uma mensagem e o erro específico
            echo "Erro ao excluir o registro: " . $e->getMessage();
        } finally {
            // Fecha a conexão, caso ainda esteja aberta
            $this->conn->close();
        }
    }

    public function getAnoFator(){
        try {
            $query = "SELECT * FROM view_ano_fator";

            $stmt = $this->conn->prepare($query);

            // Executar a query
            $stmt->execute();

            // Obter o resultado
            $result = $stmt->get_result();

            $resultados = array();
            if ($result->num_rows > 0) {
                // Obter apenas a primeira linha
                $resultados = $result->fetch_assoc();
            }            

            // Fechar a instrução e retornar os resultados
            $stmt->close();
            return $resultados;

        } catch (Exception $error) {
            echo "Erro ao buscar o fator: " . $error->getMessage();
            return [];            
        }

    }
}

if (isset($_POST['action'])) {
    // Obtém a conexão
    $conn = getConnection(); // A conexão deve ser obtida aqui    
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