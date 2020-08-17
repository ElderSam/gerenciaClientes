-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 17-Ago-2020 às 03:03
-- Versão do servidor: 10.4.11-MariaDB
-- versão do PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bd_system`
--
CREATE DATABASE IF NOT EXISTS `bd_system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bd_system`;

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_clientes_delete` (IN `pid` INT(11))  BEGIN
  
    DECLARE vidCliente INT;
    
  SELECT vidCliente INTO vidCliente
    FROM clientes
    WHERE id = pid;
    
    DELETE FROM clientes WHERE id = pid;

    SELECT vidCliente INTO vidCliente
        FROM clientes
        WHERE id = pid;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_clientes_save` (IN `pnome` VARCHAR(100), IN `pdtNasc` DATE, IN `pCPF` VARCHAR(11), IN `pRG` VARCHAR(9), IN `ptelefone` VARCHAR(11))  BEGIN
  
    DECLARE vidCliente INT;
    
  INSERT INTO clientes (nome, dtNasc, CPF, RG, telefone)
    VALUES(pnome, pdtNasc, pCPF, pRG, ptelefone);
    
    SET vidCliente = LAST_INSERT_ID();
    
    SELECT * FROM clientes WHERE id = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_clientes_update` (IN `pid` INT(11), IN `pnome` VARCHAR(100), IN `pdtNasc` DATE, IN `pCPF` VARCHAR(11), IN `pRG` VARCHAR(9), IN `ptelefone` VARCHAR(11))  BEGIN
  
    DECLARE vidCliente INT;
    
    SELECT id INTO vidCliente
    FROM clientes
    WHERE id = pid;

    UPDATE clientes
    SET
        nome = pnome,
        dtNasc = pdtNasc,
        CPF = pCPF,
        RG = pRG,
        telefone = ptelefone
        
    
    WHERE id = vidCliente;
    
    SELECT * FROM clientes WHERE id = pid;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_enderecos_delete` (IN `pid` INT(11))  BEGIN
  
    DECLARE vidEndereco INT;
    
  SELECT vidEndereco INTO vidEndereco
    FROM enderecos
    WHERE id = pid;
    
    DELETE FROM enderecos WHERE id = pid;

    SELECT vidEndereco INTO vidEndereco
        FROM enderecos
        WHERE id = pid;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_enderecos_save` (IN `pidCliente` VARCHAR(11), IN `pCEP` VARCHAR(8), IN `plogradouro` VARCHAR(100), IN `pnumero` INT(5), IN `pbairro` VARCHAR(30), IN `pcomplemento` VARCHAR(30), IN `pidMunicipio` INT(6))  BEGIN
  
    DECLARE vidEndereco INT;
    
  INSERT INTO enderecos (idCliente, CEP, logradouro, numero, bairro, complemento, idMunicipio)
    VALUES(pidCliente, pCEP, plogradouro, pnumero, pbairro, pcomplemento, pidMunicipio);
    
    SET vidEndereco = LAST_INSERT_ID();
    
    SELECT * FROM enderecos WHERE id = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_enderecos_update` (IN `pid` INT(11), IN `pidCliente` VARCHAR(11), IN `pCEP` VARCHAR(8), IN `plogradouro` VARCHAR(100), IN `pnumero` INT(5), IN `pbairro` VARCHAR(30), IN `pcomplemento` VARCHAR(30), IN `pidMunicipio` INT(6))  BEGIN
  
    DECLARE vidEndereco INT;
    
    SELECT id INTO vidEndereco
    FROM enderecos
    WHERE id = pid;

    UPDATE enderecos
    SET
        idCliente = pidCliente,
        CEP = pCEP,
        logradouro = plogradouro,
        numero = pnumero,
        bairro = pbairro,
        complemento = pcomplemento,
        idMunicipio = pidMunicipio
        
    
    WHERE id = vidEndereco;
    
    SELECT * FROM enderecos WHERE id = pid;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_estados_delete` (IN `pid` INT(11))  BEGIN
  
    DECLARE vidEstado INT;
    
  SELECT vidEstado INTO vidEstado
    FROM estados
    WHERE id = pid;
    
    DELETE FROM estados WHERE id = pid;

    SELECT vidEstado INTO vidEstado
        FROM estados
        WHERE id = pid;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_estados_save` (IN `pdescEstado` VARCHAR(30), IN `psigla` VARCHAR(2))  BEGIN
  
    DECLARE vidEstado INT;
    
  INSERT INTO estados (descEstado, sigla)
    VALUES(pdescEstado, psigla);
    
    SET vidEstado = LAST_INSERT_ID();
    
    SELECT * FROM estados WHERE id = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_estados_update` (IN `pid` INT(11), IN `pdescEstado` VARCHAR(30), IN `psigla` VARCHAR(2))  BEGIN
  
    DECLARE vidEstado INT;
    
    SELECT id INTO vidEstado
    FROM estados
    WHERE id = pid;

    UPDATE estados
    SET
        descEstado = pdescEstado,
        sigla = psigla
        
    
    WHERE id = vidEstado;
    
    SELECT * FROM estados WHERE id = pid;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_municipios_delete` (IN `pid` INT(11))  BEGIN
  
    DECLARE vidMunicipio INT;
    
  SELECT vidMunicipio INTO vidMunicipio
    FROM municipios
    WHERE id = pid;
    
    DELETE FROM municipios WHERE id = pid;

    SELECT vidMunicipio INTO vidMunicipio
        FROM municipios
        WHERE id = pid;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_municipios_save` (IN `pdescMunicipio` VARCHAR(30), IN `pidEstado` INT(2))  BEGIN
  
    DECLARE vidMunicipio INT;
    
  INSERT INTO municipios (descMunicipio, idEstado)
    VALUES(pdescMunicipio, pidEstado);
    
    SET vidMunicipio = LAST_INSERT_ID();
    
    SELECT * FROM municipios WHERE id = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_municipios_update` (IN `pid` INT(11), IN `pdescMunicipio` VARCHAR(30), IN `pidEstado` INT(2))  BEGIN
  
    DECLARE vidMunicipio INT;
    
    SELECT id INTO vidMunicipio
    FROM municipios
    WHERE id = pid;

    UPDATE municipios
    SET
        descMunicipio = pdescMunicipio,
        idEstado = pidEstado
        
    
    WHERE id = vidMunicipio;
    
    SELECT * FROM municipios WHERE id = pid;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_usuarios_delete` (IN `pid` INT(11))  BEGIN
  
    DECLARE vidUsuario INT;
    
  SELECT vidUsuario INTO vidUsuario
    FROM usuarios
    WHERE id = pid;
    
    DELETE FROM usuarios WHERE id = pid;

    SELECT vidUsuario INTO vidUsuario
        FROM usuarios
        WHERE id = pid;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_usuarios_save` (IN `pnome` VARCHAR(100), IN `pemail` VARCHAR(30), IN `plogin` VARCHAR(15), IN `psenha` VARCHAR(20))  BEGIN
  
    DECLARE vidUsuario INT;
    
  INSERT INTO usuarios (nome, email, login, senha)
    VALUES(pnome, pemail, plogin, psenha);
    
    SET vidUsuario = LAST_INSERT_ID();
    
    SELECT * FROM usuarios WHERE id = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_usuarios_update` (IN `pid` INT(11), IN `pnome` VARCHAR(100), IN `pemail` VARCHAR(30), IN `plogin` VARCHAR(15), IN `psenha` VARCHAR(20))  BEGIN
  
    DECLARE vidUsuario INT;
    
    SELECT id INTO vidUsuario
    FROM usuarios
    WHERE id = pid;

    UPDATE usuarios
    SET
        nome = pnome,
        email = pemail,
        login = plogin,
        senha = psenha
        
    
    WHERE id = vidUsuario;
    
    SELECT * FROM usuarios WHERE id = pid;
    
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `dtNasc` date NOT NULL COMMENT 'data de nascimento',
  `CPF` varchar(11) NOT NULL,
  `RG` varchar(9) NOT NULL,
  `telefone` varchar(11) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `dtNasc`, `CPF`, `RG`, `telefone`, `dtCadastro`) VALUES
(1, 'João Pedro', '1984-03-26', '34578960070', '547690011', '1935416708', '2020-08-14 20:55:06'),
(2, 'Maria da Silva', '1997-12-27', '56948634990', '524593440', '1967349088', '2020-08-16 15:36:22'),
(3, 'Cliente Teste', '1990-03-28', '67034023490', '670934561', '19999990000', '2020-08-16 15:37:52');

-- --------------------------------------------------------

--
-- Estrutura da tabela `enderecos`
--

CREATE TABLE `enderecos` (
  `id` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `CEP` varchar(8) NOT NULL,
  `logradouro` varchar(100) NOT NULL,
  `numero` int(5) NOT NULL,
  `bairro` varchar(30) NOT NULL,
  `complemento` varchar(30) NOT NULL,
  `idMunicipio` int(6) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `enderecos`
--

INSERT INTO `enderecos` (`id`, `idCliente`, `CEP`, `logradouro`, `numero`, `bairro`, `complemento`, `idMunicipio`, `dtCadastro`) VALUES
(1, 1, '13608512', 'Rua Guilherme Brant', 389, 'Jd. Santa Mônica', 'casa', 2, '2020-08-14 21:00:44'),
(2, 3, '13400980', 'Avenida São Caetano', 580, 'Centro', 'fábrica', 3, '2020-08-16 20:03:49');

-- --------------------------------------------------------

--
-- Estrutura da tabela `estados`
--

CREATE TABLE `estados` (
  `id` int(2) NOT NULL,
  `descEstado` varchar(30) NOT NULL,
  `sigla` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `estados`
--

INSERT INTO `estados` (`id`, `descEstado`, `sigla`) VALUES
(1, 'São Paulo', 'SP');

-- --------------------------------------------------------

--
-- Estrutura da tabela `municipios`
--

CREATE TABLE `municipios` (
  `id` int(6) NOT NULL,
  `descMunicipio` varchar(30) NOT NULL,
  `idEstado` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `municipios`
--

INSERT INTO `municipios` (`id`, `descMunicipio`, `idEstado`) VALUES
(1, 'São Paulo', 1),
(2, 'Campinas', 1),
(3, 'Santos', 1),
(7, 'Rio Claro', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(30) NOT NULL,
  `login` varchar(15) NOT NULL,
  `senha` varchar(20) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `login`, `senha`, `dtCadastro`) VALUES
(1, 'Administrador', 'admin@gmail.com', 'admin', 'teste2020', '2020-08-14 20:52:32'),
(2, 'João Henrique', 'teste@gmail.com', 'joao', 'joao1794', '2020-08-16 13:17:29'),
(3, 'João Henrique', 'joao@gmail.com', 'joao79', 'changed56', '2020-08-16 13:44:46'),
(4, 'Elder', 'teste@gmail.com', 'joao', '', '2020-08-16 13:47:47'),
(5, 'Elder', 'teste@gmail.com', 'joao', '', '2020-08-16 13:48:44'),
(7, 'newTest', 'new@outlook.com', 'new56460', '5grebyw', '2020-08-16 14:44:24'),
(9, 'newTest', 'new@outlook.com', 'new56460', '5grebyw', '2020-08-16 15:35:10');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `enderecos`
--
ALTER TABLE `enderecos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_endereco_municipio` (`idMunicipio`),
  ADD KEY `fk_endereco_cliente` (`idCliente`) USING BTREE;

--
-- Índices para tabela `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `municipios`
--
ALTER TABLE `municipios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cidade_estado` (`idEstado`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `enderecos`
--
ALTER TABLE `enderecos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `estados`
--
ALTER TABLE `estados`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `municipios`
--
ALTER TABLE `municipios`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `enderecos`
--
ALTER TABLE `enderecos`
  ADD CONSTRAINT `fk_endereco_cliente` FOREIGN KEY (`idCliente`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `fk_endereco_municipio` FOREIGN KEY (`idMunicipio`) REFERENCES `municipios` (`id`);

--
-- Limitadores para a tabela `municipios`
--
ALTER TABLE `municipios`
  ADD CONSTRAINT `fk_cidade_estado` FOREIGN KEY (`idEstado`) REFERENCES `estados` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
