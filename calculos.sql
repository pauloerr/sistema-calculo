-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 02/11/2024 às 21:09
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `calculos`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_atualiza_web_sql` (IN `cod_protocolo_calculo` VARCHAR(13), IN `processo` VARCHAR(50), IN `parte` VARCHAR(50), IN `contas` TEXT, IN `subtotal1` DECIMAL(18,2), IN `redutor` DECIMAL(18,2), IN `cod_redutor` TINYINT, IN `subtotal2` DECIMAL(18,2), IN `honorarios` DECIMAL(18,2), IN `honorarios_febrapo` DECIMAL(18,2), IN `total` DECIMAL(18,2), IN `cod_usuario` INT, IN `usuario` VARCHAR(50), IN `inconformidade_planos` VARCHAR(550), IN `ano_fator` INT)   BEGIN
	DECLARE cod_identificacao_calculo INT;
    DECLARE dados TEXT;
    DECLARE conta VARCHAR(20);
    DECLARE cod_plano TINYINT;
    DECLARE aniversario TINYINT;
    DECLARE saldo_base DECIMAL(18,2);
    DECLARE valor_acordo DECIMAL(18,2);
    DECLARE done INT DEFAULT 0;
    DECLARE inicio INT DEFAULT 1;
    DECLARE fim INT;

    -- Declaração do cursor
    DECLARE cursor_contas CURSOR FOR
    SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(contas, '|', n), '|', -1)
    FROM (SELECT a.N + b.N * 10 + 1 n
          FROM (SELECT 0 N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
                UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
                ,(SELECT 0 N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
                UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
         ORDER BY n) nums
    WHERE n <= CHAR_LENGTH(contas) - CHAR_LENGTH(REPLACE(contas, '|', '')) + 1;

    -- Manipulador para o cursor
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    SELECT cod_identificacao INTO cod_identificacao_calculo FROM calculo WHERE cod_protocolo = cod_protocolo_calculo;
    DELETE FROM contas WHERE cod_identificacao = cod_identificacao_calculo;
    
    UPDATE calculo SET
    		processo = processo
            ,parte = parte
    		,subtotal1 = subtotal1
			,redutor = redutor
			,cod_redutor = cod_redutor
			,subtotal2 = subtotal2
			,honorarios = honorarios
			,honorarios_febrapo = honorarios_febrapo
			,total = total
            ,cod_usuario = cod_usuario
			,usuario = usuario
			,inconformidade_planos = inconformidade_planos
			,ano_fator = ano_fator
			,data_hora_inc = NOW()
		WHERE 
			cod_identificacao = cod_identificacao_calculo;
   
    -- Abertura do cursor
    OPEN cursor_contas;

    cursor_loop: LOOP
        FETCH cursor_contas INTO dados;
        IF done THEN
            LEAVE cursor_loop;
        END IF;

        -- Processamento dos dados das contas
        SET conta = SUBSTRING_INDEX(dados, ',', 1);
        SET cod_plano = SUBSTRING_INDEX(SUBSTRING_INDEX(dados, ',', 2), ',', -1);
        SET aniversario = SUBSTRING_INDEX(SUBSTRING_INDEX(dados, ',', 3), ',', -1);
        SET saldo_base = SUBSTRING_INDEX(SUBSTRING_INDEX(dados, ',', 4), ',', -1);
        SET valor_acordo = SUBSTRING_INDEX(dados, ',', -1);

        -- Inserção na tabela 'contas'
        INSERT INTO contas(cod_identificacao, conta, cod_plano, aniversario, saldo_base, valor_acordo)
        VALUES (cod_identificacao_calculo, conta, cod_plano, aniversario, saldo_base, valor_acordo);
    END LOOP cursor_loop;

    -- Fechamento do cursor
    CLOSE cursor_contas;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insercao_web_sql` (IN `processo` VARCHAR(50), IN `parte` VARCHAR(50), IN `contas` TEXT, IN `subtotal1` TEXT, IN `redutor` TEXT, IN `cod_redutor` TEXT, IN `subtotal2` TEXT, IN `honorarios` TEXT, IN `honorarios_febrapo` TEXT, IN `total` TEXT, IN `cod_usuario` INT, IN `usuario` VARCHAR(50), IN `inconformidade_planos` VARCHAR(550), IN `ano_fator` INT, OUT `resultado` VARCHAR(13))   BEGIN
    -- Declaração de variáveis
    DECLARE cod_identificacao_calculo INT;
    DECLARE cod_protocolo_calculo CHAR(13);
    DECLARE dados TEXT;
    DECLARE conta VARCHAR(20);
    DECLARE cod_plano TINYINT;
    DECLARE aniversario TINYINT;
    DECLARE saldo_base DECIMAL(18,2);
    DECLARE valor_acordo DECIMAL(18,2);
    DECLARE done INT DEFAULT 0;
    DECLARE inicio INT DEFAULT 1;
    DECLARE fim INT;

    -- Declaração do cursor
    DECLARE cursor_contas CURSOR FOR
    SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(contas, '|', n), '|', -1)
    FROM (SELECT a.N + b.N * 10 + 1 n
          FROM (SELECT 0 N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
                UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
                ,(SELECT 0 N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
                UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
         ORDER BY n) nums
    WHERE n <= CHAR_LENGTH(contas) - CHAR_LENGTH(REPLACE(contas, '|', '')) + 1;

    -- Manipulador para o cursor
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    -- Inserção na tabela 'calculo'
    INSERT INTO calculo (processo, parte, subTotal1, redutor, cod_redutor, subtotal2, honorarios, honorarios_febrapo, total, cod_usuario, usuario, inconformidade_planos, ano_fator) 
    VALUES (processo, parte, subTotal1, redutor, cod_redutor, subtotal2, honorarios, honorarios_febrapo, total, cod_usuario, usuario, inconformidade_planos, ano_fator);
    
    -- Obtenção do último ID inserido
    SET cod_identificacao_calculo = LAST_INSERT_ID();
    
    -- Seleção do código de protocolo
    SELECT cod_protocolo INTO cod_protocolo_calculo FROM calculo WHERE cod_identificacao = cod_identificacao_calculo;

    -- Definição do resultado como o código de protocolo
    SET resultado = cod_protocolo_calculo;

    -- Abertura do cursor
    OPEN cursor_contas;

    cursor_loop: LOOP
        FETCH cursor_contas INTO dados;
        IF done THEN
            LEAVE cursor_loop;
        END IF;

        -- Processamento dos dados das contas
        SET conta = SUBSTRING_INDEX(dados, ',', 1);
        SET cod_plano = SUBSTRING_INDEX(SUBSTRING_INDEX(dados, ',', 2), ',', -1);
        SET aniversario = SUBSTRING_INDEX(SUBSTRING_INDEX(dados, ',', 3), ',', -1);
        SET saldo_base = SUBSTRING_INDEX(SUBSTRING_INDEX(dados, ',', 4), ',', -1);
        SET valor_acordo = SUBSTRING_INDEX(dados, ',', -1);

        -- Inserção na tabela 'contas'
        INSERT INTO contas(cod_identificacao, conta, cod_plano, aniversario, saldo_base, valor_acordo)
        VALUES (cod_identificacao_calculo, conta, cod_plano, aniversario, saldo_base, valor_acordo);
    END LOOP cursor_loop;

    -- Fechamento do cursor
    CLOSE cursor_contas;

END$$

--
-- Funções
--
CREATE DEFINER=`root`@`localhost` FUNCTION `gera_protocolo` () RETURNS CHAR(13) CHARSET utf8 COLLATE utf8_general_ci  BEGIN
	DECLARE codigo CHAR(13);
    DECLARE qtd INT;
    
    SET codigo = CONCAT('AF-', LPAD(FLOOR(RAND() * 10000000000), 10, '0'));
    SELECT COUNT(cod_protocolo) INTO qtd FROM calculo WHERE cod_protocolo = codigo;
    
    WHILE (qtd > 0) DO
    	SET codigo = CONCAT('AF-', LPAD(FLOOR(RAND() * 10000000000), 10, '0'));
        SELECT COUNT(cod_protocolo) INTO qtd FROM calculo WHERE cod_protocolo = codigo;
    END WHILE;
    
    RETURN codigo;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `calculo`
--

CREATE TABLE `calculo` (
  `cod_identificacao` bigint(20) NOT NULL,
  `cod_protocolo` char(18) NOT NULL,
  `processo` varchar(20) NOT NULL,
  `parte` varchar(50) NOT NULL,
  `ano_fator` int(11) NOT NULL,
  `subtotal1` decimal(18,2) NOT NULL,
  `redutor` decimal(18,2) NOT NULL,
  `cod_redutor` tinyint(4) NOT NULL,
  `subtotal2` decimal(18,2) NOT NULL,
  `honorarios` decimal(18,2) NOT NULL,
  `honorarios_febrapo` decimal(18,2) NOT NULL,
  `total` decimal(18,2) NOT NULL,
  `inconformidade_planos` varchar(1000) NOT NULL,
  `cod_usuario` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `data_hora_inc` datetime NOT NULL DEFAULT current_timestamp(),
  `apagado` bit(1) NOT NULL DEFAULT b'0',
  `data_hora_apagado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Acionadores `calculo`
--
DELIMITER $$
CREATE TRIGGER `preenche_protocolo` BEFORE INSERT ON `calculo` FOR EACH ROW BEGIN
    SET NEW.cod_protocolo = gera_protocolo();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas`
--

CREATE TABLE `contas` (
  `cod_conta` int(11) NOT NULL,
  `cod_identificacao` int(11) NOT NULL,
  `conta` varchar(50) NOT NULL,
  `cod_plano` tinyint(4) NOT NULL,
  `aniversario` tinyint(4) NOT NULL,
  `saldo_base` decimal(18,2) NOT NULL,
  `valor_acordo` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fatores`
--

CREATE TABLE `fatores` (
  `cod_plano` tinyint(4) NOT NULL,
  `valor_fator` decimal(7,5) NOT NULL,
  `ano_fator` int(11) NOT NULL,
  `ativo` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `fatores`
--

INSERT INTO `fatores` (`cod_plano`, `valor_fator`, `ano_fator`, `ativo`) VALUES
(1, 0.05133, 2024, b'1'),
(2, 4.91886, 2024, b'1'),
(3, 0.03601, 2024, b'1'),
(4, 0.00168, 2024, b'1');

-- --------------------------------------------------------

--
-- Estrutura para tabela `plano`
--

CREATE TABLE `plano` (
  `cod_plano` tinyint(4) NOT NULL,
  `descricao_plano` varchar(10) NOT NULL,
  `data_posicao_saldo_base` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `plano`
--

INSERT INTO `plano` (`cod_plano`, `descricao_plano`, `data_posicao_saldo_base`) VALUES
(1, 'Bresser', '1987-06-01'),
(2, 'Verão', '1989-01-01'),
(3, 'Collor I', '1990-04-01'),
(4, 'Collor II', '1991-01-01');

-- --------------------------------------------------------

--
-- Estrutura para tabela `redutor`
--

CREATE TABLE `redutor` (
  `cod_redutor` tinyint(4) NOT NULL,
  `descricao_redutor` varchar(100) NOT NULL,
  `valor_redutor` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `redutor`
--

INSERT INTO `redutor` (`cod_redutor`, `descricao_redutor`, `valor_redutor`) VALUES
(1, 'Sem desconto', 0),
(2, '8% de desconto', 8),
(3, '14% de desconto', 14),
(4, '19% de desconto', 19);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `cod_usuario` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `view_ano_fator`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `view_ano_fator` (
`ano_fator` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `view_historico_calculado`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `view_historico_calculado` (
`cod_identificacao` bigint(20)
,`cod_protocolo` char(18)
,`usuario` varchar(50)
,`data_hora_inc` datetime
,`apagado` bit(1)
,`data_hora_apagado` datetime
,`processo` varchar(20)
,`subtotal1` decimal(18,2)
,`redutor` decimal(18,2)
,`subtotal2` decimal(18,2)
,`honorarios` decimal(18,2)
,`honorarios_febrapo` decimal(18,2)
,`total` decimal(18,2)
,`inconformidade_planos` varchar(1000)
,`valor_redutor` tinyint(4)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `view_historico_contas`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `view_historico_contas` (
`cod_protocolo` char(18)
,`cod_identificacao` int(11)
,`conta` varchar(50)
,`cod_plano` tinyint(4)
,`descricao_plano` varchar(10)
,`data_posicao_saldo_base` date
,`aniversario` tinyint(4)
,`saldo_base` decimal(18,2)
,`valor_fator` decimal(7,5)
,`valor_acordo` decimal(18,2)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `view_tabela_fatores`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `view_tabela_fatores` (
`cod_plano` tinyint(4)
,`valor_fator` decimal(7,5)
,`ano_fator` int(11)
,`descricao_plano` varchar(10)
,`data_posicao_saldo_base` date
,`ativo` bit(1)
);

-- --------------------------------------------------------

--
-- Estrutura para view `view_ano_fator`
--
DROP TABLE IF EXISTS `view_ano_fator`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_ano_fator`  AS SELECT max(`fatores`.`ano_fator`) AS `ano_fator` FROM `fatores` WHERE `fatores`.`ativo` = 1 ;

-- --------------------------------------------------------

--
-- Estrutura para view `view_historico_calculado`
--
DROP TABLE IF EXISTS `view_historico_calculado`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_historico_calculado`  AS SELECT `calculo`.`cod_identificacao` AS `cod_identificacao`, `calculo`.`cod_protocolo` AS `cod_protocolo`, `calculo`.`usuario` AS `usuario`, `calculo`.`data_hora_inc` AS `data_hora_inc`, `calculo`.`apagado` AS `apagado`, `calculo`.`data_hora_apagado` AS `data_hora_apagado`, `calculo`.`processo` AS `processo`, `calculo`.`subtotal1` AS `subtotal1`, `calculo`.`redutor` AS `redutor`, `calculo`.`subtotal2` AS `subtotal2`, `calculo`.`honorarios` AS `honorarios`, `calculo`.`honorarios_febrapo` AS `honorarios_febrapo`, `calculo`.`total` AS `total`, `calculo`.`inconformidade_planos` AS `inconformidade_planos`, `redutor`.`valor_redutor` AS `valor_redutor` FROM (`calculo` join `redutor` on(`calculo`.`cod_redutor` = `redutor`.`cod_redutor`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `view_historico_contas`
--
DROP TABLE IF EXISTS `view_historico_contas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_historico_contas`  AS SELECT `calculo`.`cod_protocolo` AS `cod_protocolo`, `contas`.`cod_identificacao` AS `cod_identificacao`, `contas`.`conta` AS `conta`, `contas`.`cod_plano` AS `cod_plano`, `plano`.`descricao_plano` AS `descricao_plano`, `plano`.`data_posicao_saldo_base` AS `data_posicao_saldo_base`, `contas`.`aniversario` AS `aniversario`, `contas`.`saldo_base` AS `saldo_base`, `fatores`.`valor_fator` AS `valor_fator`, `contas`.`valor_acordo` AS `valor_acordo` FROM (((`contas` join `plano` on(`contas`.`cod_plano` = `plano`.`cod_plano`)) join `fatores` on(`plano`.`cod_plano` = `fatores`.`cod_plano`)) join `calculo` on(`contas`.`cod_identificacao` = `calculo`.`cod_identificacao` and `fatores`.`ano_fator` = `calculo`.`ano_fator`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `view_tabela_fatores`
--
DROP TABLE IF EXISTS `view_tabela_fatores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_tabela_fatores`  AS SELECT `fatores`.`cod_plano` AS `cod_plano`, `fatores`.`valor_fator` AS `valor_fator`, `fatores`.`ano_fator` AS `ano_fator`, `plano`.`descricao_plano` AS `descricao_plano`, `plano`.`data_posicao_saldo_base` AS `data_posicao_saldo_base`, `fatores`.`ativo` AS `ativo` FROM (`fatores` join `plano` on(`fatores`.`cod_plano` = `plano`.`cod_plano`)) ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `calculo`
--
ALTER TABLE `calculo`
  ADD PRIMARY KEY (`cod_identificacao`);

--
-- Índices de tabela `contas`
--
ALTER TABLE `contas`
  ADD PRIMARY KEY (`cod_conta`);

--
-- Índices de tabela `fatores`
--
ALTER TABLE `fatores`
  ADD PRIMARY KEY (`cod_plano`,`valor_fator`,`ano_fator`);

--
-- Índices de tabela `plano`
--
ALTER TABLE `plano`
  ADD PRIMARY KEY (`cod_plano`);

--
-- Índices de tabela `redutor`
--
ALTER TABLE `redutor`
  ADD PRIMARY KEY (`cod_redutor`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`cod_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `calculo`
--
ALTER TABLE `calculo`
  MODIFY `cod_identificacao` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `contas`
--
ALTER TABLE `contas`
  MODIFY `cod_conta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT de tabela `plano`
--
ALTER TABLE `plano`
  MODIFY `cod_plano` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `redutor`
--
ALTER TABLE `redutor`
  MODIFY `cod_redutor` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `cod_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
