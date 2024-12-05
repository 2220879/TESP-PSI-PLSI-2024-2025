/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `produtosginasio`
CREATE DATABASE produtosginasio;
USE produtosginasio;
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` int DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', '1', 1733229615);

-- --------------------------------------------------------

--
-- Estrutura da tabela `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `type` smallint NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `rule_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('admin', 1, NULL, NULL, NULL, 1733229614, 1733229614),
('cliente', 1, NULL, NULL, NULL, 1733229615, 1733229615),
('createAvaliacao', 2, 'Create Avaliacao', NULL, NULL, 1733229614, 1733229614),
('createCategoria', 2, 'Create a categoria', NULL, NULL, 1733229614, 1733229614),
('createCompra', 2, 'Create a compra', NULL, NULL, 1733229614, 1733229614),
('createCupao', 2, 'Create a cupao', NULL, NULL, 1733229614, 1733229614),
('createFavorito', 2, 'Create Favorito', NULL, NULL, 1733229614, 1733229614),
('createFornecedor', 2, 'Create a fornecedor', NULL, NULL, 1733229614, 1733229614),
('createGenero', 2, 'Create a Genero', NULL, NULL, 1733229614, 1733229614),
('createImagem', 2, 'Create a Imagem', NULL, NULL, 1733229614, 1733229614),
('createIva', 2, 'Create a Iva', NULL, NULL, 1733229614, 1733229614),
('createLinhaCarrinhoCompra', 2, 'Create LinhaCarrinhoCompra', NULL, NULL, 1733229614, 1733229614),
('createLinhaCompra', 2, 'Create a linha compra', NULL, NULL, 1733229614, 1733229614),
('createMarca', 2, 'Create a marca', NULL, NULL, 1733229614, 1733229614),
('createMetodoEntrega', 2, 'Create a Metodo Entrega', NULL, NULL, 1733229614, 1733229614),
('createMetodoPagamento', 2, 'Create a Metodo Pagamento', NULL, NULL, 1733229614, 1733229614),
('createProduto', 2, 'Create a produto', NULL, NULL, 1733229614, 1733229614),
('createProfile', 2, 'Create a profile', NULL, NULL, 1733229614, 1733229614),
('createTamanho', 2, 'Create a tamanho', NULL, NULL, 1733229614, 1733229614),
('createUser', 2, 'Create a user', NULL, NULL, 1733229614, 1733229614),
('deleteAvaliacao', 2, 'Delete Avaliacao', NULL, NULL, 1733229614, 1733229614),
('deleteCategoria', 2, 'delete Categoria', NULL, NULL, 1733229614, 1733229614),
('deleteCompra', 2, 'delete compra', NULL, NULL, 1733229614, 1733229614),
('deleteCupao', 2, 'delete cupao', NULL, NULL, 1733229614, 1733229614),
('deleteFavorito', 2, 'Delete Favorito', NULL, NULL, 1733229614, 1733229614),
('deleteFornecedor', 2, 'delete fornecedor', NULL, NULL, 1733229614, 1733229614),
('deleteGenero', 2, 'delete Genero', NULL, NULL, 1733229614, 1733229614),
('deleteImagem', 2, 'delete Imagem', NULL, NULL, 1733229614, 1733229614),
('deleteIva', 2, 'delete Iva', NULL, NULL, 1733229614, 1733229614),
('deleteLinhaCarrinhoCompra', 2, 'Delete LinhaCarrinhoCompra', NULL, NULL, 1733229614, 1733229614),
('deleteLinhaCompra', 2, 'delete linha compra', NULL, NULL, 1733229614, 1733229614),
('deleteMarca', 2, 'delete Marca', NULL, NULL, 1733229614, 1733229614),
('deleteMetodoEntrega', 2, 'delete Metodo Entrega', NULL, NULL, 1733229614, 1733229614),
('deleteMetodoPagamento', 2, 'delete Metodo Pagamento', NULL, NULL, 1733229614, 1733229614),
('deleteProduto', 2, 'delete produto', NULL, NULL, 1733229614, 1733229614),
('deleteProfile', 2, 'delete profile', NULL, NULL, 1733229614, 1733229614),
('deleteTamanho', 2, 'delete tamanho', NULL, NULL, 1733229614, 1733229614),
('deleteUser', 2, 'delete user', NULL, NULL, 1733229614, 1733229614),
('funcionario', 1, NULL, NULL, NULL, 1733229615, 1733229615),
('updateAvaliacao', 2, 'Update Avaliacao', NULL, NULL, 1733229614, 1733229614),
('updateCategoria', 2, 'Update Categoria', NULL, NULL, 1733229614, 1733229614),
('updateCompra', 2, 'Update Compra', NULL, NULL, 1733229614, 1733229614),
('updateCupao', 2, 'Update Cupao', NULL, NULL, 1733229614, 1733229614),
('updateEncomenda', 2, 'Update Encomenda', NULL, NULL, 1733229614, 1733229614),
('updateFavorito', 2, 'Update Favorito', NULL, NULL, 1733229614, 1733229614),
('updateFornecedor', 2, 'Update Fornecedor', NULL, NULL, 1733229614, 1733229614),
('updateGenero', 2, 'Update Genero', NULL, NULL, 1733229614, 1733229614),
('updateImagem', 2, 'Update Imagem', NULL, NULL, 1733229614, 1733229614),
('updateIva', 2, 'Update Iva', NULL, NULL, 1733229614, 1733229614),
('updateLInhaCarrinhoCompra', 2, 'Update LinhaCarrinhoCompra', NULL, NULL, 1733229614, 1733229614),
('updateLinhaCompra', 2, 'Update Linha Compra', NULL, NULL, 1733229614, 1733229614),
('updateMarca', 2, 'Update Marca', NULL, NULL, 1733229614, 1733229614),
('updateMetodoEntrega', 2, 'Update Metodo Entrega', NULL, NULL, 1733229614, 1733229614),
('updateMetodoPagamento', 2, 'Update Metodo Pagamento', NULL, NULL, 1733229614, 1733229614),
('updateProduto', 2, 'Update Produto', NULL, NULL, 1733229614, 1733229614),
('updateProfile', 2, 'Update profile', NULL, NULL, 1733229614, 1733229614),
('updateTamanho', 2, 'Update Tamanho', NULL, NULL, 1733229614, 1733229614),
('updateUser', 2, 'Update user', NULL, NULL, 1733229614, 1733229614),
('viewAvaliacoes', 2, 'View Avaliacoes', NULL, NULL, 1733229614, 1733229614),
('viewCarrinhoCompras', 2, 'View CarrinhoCompras', NULL, NULL, 1733229614, 1733229614),
('viewCategorias', 2, 'View Categorias', NULL, NULL, 1733229614, 1733229614),
('viewCompras', 2, 'View Compras', NULL, NULL, 1733229614, 1733229614),
('viewCupoes', 2, 'View Cupoes Descontos', NULL, NULL, 1733229614, 1733229614),
('viewEncomendas', 2, 'View Encomendas', NULL, NULL, 1733229614, 1733229614),
('viewFaturas', 2, 'View Faturas', NULL, NULL, 1733229614, 1733229614),
('viewFavoritos', 2, 'View Favoritos', NULL, NULL, 1733229614, 1733229614),
('viewFornecedores', 2, 'View Fornecedores', NULL, NULL, 1733229614, 1733229614),
('viewGeneros', 2, 'View Generos', NULL, NULL, 1733229614, 1733229614),
('viewImagens', 2, 'View Imagens', NULL, NULL, 1733229614, 1733229614),
('viewIvas', 2, 'View Ivas', NULL, NULL, 1733229614, 1733229614),
('viewLinhaCarrinhoCompras', 2, 'View LinhaCarrinhoCompras', NULL, NULL, 1733229614, 1733229614),
('viewLinhasCompras', 2, 'View Linhas Compras', NULL, NULL, 1733229614, 1733229614),
('viewLinhasFaturas', 2, 'View Linhas Faturas', NULL, NULL, 1733229614, 1733229614),
('viewMarcas', 2, 'View Marcas', NULL, NULL, 1733229614, 1733229614),
('viewMetodosEntregas', 2, 'View Metodos Entregas', NULL, NULL, 1733229614, 1733229614),
('viewMetodosPagamentos', 2, 'View Metodos Pagamentos', NULL, NULL, 1733229614, 1733229614),
('viewProdutos', 2, 'View Produtos', NULL, NULL, 1733229614, 1733229614),
('viewProfiles', 2, 'View Profiles', NULL, NULL, 1733229614, 1733229614),
('viewTamanhos', 2, 'View Tamanhos', NULL, NULL, 1733229614, 1733229614),
('viewUsers', 2, 'View users', NULL, NULL, 1733229614, 1733229614),
('viewUsoCupoes', 2, 'View uso cupoes', NULL, NULL, 1733229614, 1733229614);

-- --------------------------------------------------------

--
-- Estrutura da tabela `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `child` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('cliente', 'createAvaliacao'),
('admin', 'createCategoria'),
('funcionario', 'createCategoria'),
('admin', 'createCompra'),
('funcionario', 'createCompra'),
('admin', 'createCupao'),
('funcionario', 'createCupao'),
('cliente', 'createFavorito'),
('admin', 'createFornecedor'),
('funcionario', 'createFornecedor'),
('admin', 'createGenero'),
('funcionario', 'createGenero'),
('admin', 'createImagem'),
('funcionario', 'createImagem'),
('admin', 'createIva'),
('funcionario', 'createIva'),
('cliente', 'createLinhaCarrinhoCompra'),
('admin', 'createLinhaCompra'),
('funcionario', 'createLinhaCompra'),
('admin', 'createMarca'),
('funcionario', 'createMarca'),
('admin', 'createMetodoEntrega'),
('funcionario', 'createMetodoEntrega'),
('admin', 'createMetodoPagamento'),
('funcionario', 'createMetodoPagamento'),
('admin', 'createProduto'),
('funcionario', 'createProduto'),
('admin', 'createProfile'),
('admin', 'createTamanho'),
('funcionario', 'createTamanho'),
('admin', 'createUser'),
('cliente', 'deleteAvaliacao'),
('admin', 'deleteCategoria'),
('funcionario', 'deleteCategoria'),
('admin', 'deleteCompra'),
('funcionario', 'deleteCompra'),
('admin', 'deleteCupao'),
('funcionario', 'deleteCupao'),
('cliente', 'deleteFavorito'),
('admin', 'deleteFornecedor'),
('funcionario', 'deleteFornecedor'),
('admin', 'deleteGenero'),
('funcionario', 'deleteGenero'),
('admin', 'deleteImagem'),
('funcionario', 'deleteImagem'),
('admin', 'deleteIva'),
('funcionario', 'deleteIva'),
('cliente', 'deleteLinhaCarrinhoCompra'),
('admin', 'deleteLinhaCompra'),
('funcionario', 'deleteLinhaCompra'),
('admin', 'deleteMarca'),
('funcionario', 'deleteMarca'),
('admin', 'deleteMetodoEntrega'),
('funcionario', 'deleteMetodoEntrega'),
('admin', 'deleteMetodoPagamento'),
('funcionario', 'deleteMetodoPagamento'),
('admin', 'deleteProduto'),
('funcionario', 'deleteProduto'),
('admin', 'deleteProfile'),
('admin', 'deleteTamanho'),
('funcionario', 'deleteTamanho'),
('admin', 'deleteUser'),
('cliente', 'updateAvaliacao'),
('admin', 'updateCategoria'),
('funcionario', 'updateCategoria'),
('admin', 'updateCompra'),
('funcionario', 'updateCompra'),
('admin', 'updateCupao'),
('funcionario', 'updateCupao'),
('admin', 'updateEncomenda'),
('funcionario', 'updateEncomenda'),
('cliente', 'updateFavorito'),
('admin', 'updateFornecedor'),
('funcionario', 'updateFornecedor'),
('admin', 'updateGenero'),
('funcionario', 'updateGenero'),
('admin', 'updateImagem'),
('funcionario', 'updateImagem'),
('admin', 'updateIva'),
('funcionario', 'updateIva'),
('cliente', 'updateLInhaCarrinhoCompra'),
('admin', 'updateLinhaCompra'),
('funcionario', 'updateLinhaCompra'),
('admin', 'updateMarca'),
('funcionario', 'updateMarca'),
('admin', 'updateMetodoEntrega'),
('funcionario', 'updateMetodoEntrega'),
('admin', 'updateMetodoPagamento'),
('funcionario', 'updateMetodoPagamento'),
('admin', 'updateProduto'),
('funcionario', 'updateProduto'),
('admin', 'updateProfile'),
('cliente', 'updateProfile'),
('admin', 'updateTamanho'),
('funcionario', 'updateTamanho'),
('admin', 'updateUser'),
('cliente', 'updateUser'),
('admin', 'viewAvaliacoes'),
('cliente', 'viewAvaliacoes'),
('funcionario', 'viewAvaliacoes'),
('cliente', 'viewCarrinhoCompras'),
('admin', 'viewCategorias'),
('cliente', 'viewCategorias'),
('funcionario', 'viewCategorias'),
('admin', 'viewCompras'),
('funcionario', 'viewCompras'),
('admin', 'viewCupoes'),
('cliente', 'viewCupoes'),
('funcionario', 'viewCupoes'),
('admin', 'viewEncomendas'),
('cliente', 'viewEncomendas'),
('funcionario', 'viewEncomendas'),
('admin', 'viewFaturas'),
('cliente', 'viewFaturas'),
('funcionario', 'viewFaturas'),
('cliente', 'viewFavoritos'),
('admin', 'viewFornecedores'),
('funcionario', 'viewFornecedores'),
('admin', 'viewGeneros'),
('cliente', 'viewGeneros'),
('funcionario', 'viewGeneros'),
('admin', 'viewImagens'),
('cliente', 'viewImagens'),
('funcionario', 'viewImagens'),
('admin', 'viewIvas'),
('cliente', 'viewIvas'),
('funcionario', 'viewIvas'),
('cliente', 'viewLinhaCarrinhoCompras'),
('admin', 'viewLinhasCompras'),
('funcionario', 'viewLinhasCompras'),
('admin', 'viewLinhasFaturas'),
('cliente', 'viewLinhasFaturas'),
('funcionario', 'viewLinhasFaturas'),
('admin', 'viewMarcas'),
('cliente', 'viewMarcas'),
('funcionario', 'viewMarcas'),
('admin', 'viewMetodosEntregas'),
('cliente', 'viewMetodosEntregas'),
('funcionario', 'viewMetodosEntregas'),
('admin', 'viewMetodosPagamentos'),
('cliente', 'viewMetodosPagamentos'),
('funcionario', 'viewMetodosPagamentos'),
('admin', 'viewProdutos'),
('cliente', 'viewProdutos'),
('funcionario', 'viewProdutos'),
('admin', 'viewProfiles'),
('cliente', 'viewProfiles'),
('admin', 'viewTamanhos'),
('cliente', 'viewTamanhos'),
('funcionario', 'viewTamanhos'),
('admin', 'viewUsers'),
('cliente', 'viewUsers'),
('admin', 'viewUsoCupoes'),
('funcionario', 'viewUsoCupoes');

-- --------------------------------------------------------

--
-- Estrutura da tabela `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `avaliacoes`
--

DROP TABLE IF EXISTS `avaliacoes`;
CREATE TABLE IF NOT EXISTS `avaliacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` text NOT NULL,
  `produto_id` int NOT NULL,
  `profile_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_avaliacoes_produtos1_idx` (`produto_id`),
  KEY `fk_avaliacoes_profiles1_idx` (`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `carrinhocompras`
--

DROP TABLE IF EXISTS `carrinhocompras`;
CREATE TABLE IF NOT EXISTS `carrinhocompras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quantidade` int NOT NULL,
  `valorTotal` float NOT NULL,
  `profile_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_carrinhocompras_profiles1_idx` (`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nomeCategoria` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `compras`
--

DROP TABLE IF EXISTS `compras`;
CREATE TABLE IF NOT EXISTS `compras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `total` float NOT NULL,
  `dataDespesa` date NOT NULL,
  `fornecedor_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_compras_fornecedores1_idx` (`fornecedor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cupoesdescontos`
--

DROP TABLE IF EXISTS `cupoesdescontos`;
CREATE TABLE IF NOT EXISTS `cupoesdescontos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  `desconto` float NOT NULL,
  `dataFim` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_UNIQUE` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `encomendas`
--

DROP TABLE IF EXISTS `encomendas`;
CREATE TABLE IF NOT EXISTS `encomendas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `morada` text NOT NULL,
  `telefone` mediumint NOT NULL,
  `email` mediumtext NOT NULL,
  `estadoEncomenda` text NOT NULL,
  `profile_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_encomendas_profiles1_idx` (`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `faturas`
--

DROP TABLE IF EXISTS `faturas`;
CREATE TABLE IF NOT EXISTS `faturas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dataEmissao` date NOT NULL,
  `horaEmissao` time NOT NULL,
  `valorTotal` float NOT NULL,
  `ivaTotal` float NOT NULL,
  `nif` int DEFAULT NULL,
  `metodopagamento_id` int NOT NULL,
  `metodoentrega_id` int NOT NULL,
  `encomenda_id` int NOT NULL,
  `profile_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_faturas_metodospagamentos1_idx` (`metodopagamento_id`),
  KEY `fk_faturas_metodosentregas1_idx` (`metodoentrega_id`),
  KEY `fk_faturas_profiles1_idx` (`profile_id`),
  KEY `fk_faturas_encomendas1_idx` (`encomenda_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `favoritos`
--

DROP TABLE IF EXISTS `favoritos`;
CREATE TABLE IF NOT EXISTS `favoritos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `produto_id` int NOT NULL,
  `profile_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_favoritos_produtos1_idx` (`produto_id`),
  KEY `fk_favoritos_profiles1_idx` (`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `fornecedores`
--

DROP TABLE IF EXISTS `fornecedores`;
CREATE TABLE IF NOT EXISTS `fornecedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `telefone` mediumint NOT NULL,
  `email` varchar(50) NOT NULL,
  `marca_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_fornecedores_marcas1_idx` (`marca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `generos`
--

DROP TABLE IF EXISTS `generos`;
CREATE TABLE IF NOT EXISTS `generos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `referencia` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `imagens`
--

DROP TABLE IF EXISTS `imagens`;
CREATE TABLE IF NOT EXISTS `imagens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `filename` text NOT NULL,
  `produto_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_imagens_produtos1_idx` (`produto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ivas`
--

DROP TABLE IF EXISTS `ivas`;
CREATE TABLE IF NOT EXISTS `ivas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `percentagem` float NOT NULL,
  `vigor` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `linhascarrinhos`
--

DROP TABLE IF EXISTS `linhascarrinhos`;
CREATE TABLE IF NOT EXISTS `linhascarrinhos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quantidade` int NOT NULL,
  `precoUnit` float NOT NULL,
  `valorIva` float NOT NULL,
  `valorComIva` float NOT NULL,
  `subtotal` float NOT NULL,
  `carrinhocompras_id` int NOT NULL,
  `produto_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_linhasCarrinhos_carrinhocompras1_idx` (`carrinhocompras_id`),
  KEY `fk_linhasCarrinhos_produtos1_idx` (`produto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `linhascompras`
--

DROP TABLE IF EXISTS `linhascompras`;
CREATE TABLE IF NOT EXISTS `linhascompras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quantidade` int NOT NULL,
  `preco` float NOT NULL,
  `iva` float NOT NULL,
  `compra_id` int NOT NULL,
  `produto_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_linhascompras_compras1_idx` (`compra_id`),
  KEY `fk_linhascompras_produtos1_idx` (`produto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `linhasfaturas`
--

DROP TABLE IF EXISTS `linhasfaturas`;
CREATE TABLE IF NOT EXISTS `linhasfaturas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dataVenda` date NOT NULL,
  `nomeProduto` varchar(50) NOT NULL,
  `quantidade` int NOT NULL,
  `precoUnit` float NOT NULL,
  `valorIva` float NOT NULL,
  `valorComIva` float NOT NULL,
  `subtotal` float NOT NULL,
  `fatura_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_linhasfaturas_faturas1_idx` (`fatura_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `marcas`
--

DROP TABLE IF EXISTS `marcas`;
CREATE TABLE IF NOT EXISTS `marcas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nomeMarca` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `metodosentregas`
--

DROP TABLE IF EXISTS `metodosentregas`;
CREATE TABLE IF NOT EXISTS `metodosentregas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` text NOT NULL,
  `diasEntrega` text NOT NULL,
  `preco` float NOT NULL,
  `vigor` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `metodospagamentos`
--

DROP TABLE IF EXISTS `metodospagamentos`;
CREATE TABLE IF NOT EXISTS `metodospagamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `metodoPagamento` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `migration`
--

DROP TABLE IF EXISTS `migration`;
CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

DROP TABLE IF EXISTS `produtos`;
CREATE TABLE IF NOT EXISTS `produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nomeProduto` varchar(50) NOT NULL,
  `preco` float NOT NULL,
  `quantidade` int NOT NULL,
  `descricaoProduto` text NOT NULL,
  `marca_id` int NOT NULL,
  `categoria_id` int NOT NULL,
  `iva_id` int NOT NULL,
  `genero_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_produtos_marcas_idx` (`marca_id`),
  KEY `fk_produtos_categorias1_idx` (`categoria_id`),
  KEY `fk_produtos_ivas1_idx` (`iva_id`),
  KEY `fk_produtos_generos1_idx` (`genero_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos_has_tamanhos`
--

DROP TABLE IF EXISTS `produtos_has_tamanhos`;
CREATE TABLE IF NOT EXISTS `produtos_has_tamanhos` (
  `produto_id` int NOT NULL,
  `tamanho_id` int NOT NULL,
  `quantidade` int NOT NULL,
  PRIMARY KEY (`produto_id`,`tamanho_id`),
  KEY `fk_produtos_has_tamanhos_tamanhos1_idx` (`tamanho_id`),
  KEY `fk_produtos_has_tamanhos_produtos1_idx` (`produto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `profiles`
--

DROP TABLE IF EXISTS `profiles`;
CREATE TABLE IF NOT EXISTS `profiles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nif` int NOT NULL,
  `morada` text NOT NULL,
  `telefone` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_profiles_user1_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tamanhos`
--

DROP TABLE IF EXISTS `tamanhos`;
CREATE TABLE IF NOT EXISTS `tamanhos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `referencia` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `auth_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` smallint NOT NULL DEFAULT '10',
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  `verification_token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `verification_token`) VALUES
(1, 'admin', 'jm5uVUT6MDVaS06rYjvxsTTdjXzI8qQ6', '$2y$13$jvbLS1UYQRUEgV.v1Pd2pOU48PxsFwonElSJcucw3l/yC9Y/Nmux.', NULL, 'admin@exemplo.pt', 10, 1731540818, 1731540819, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usocupoes`
--

DROP TABLE IF EXISTS `usocupoes`;
CREATE TABLE IF NOT EXISTS `usocupoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cupaodesconto_id` int NOT NULL,
  `profile_id` int NOT NULL,
  `dataUso` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_usocupoes_cupoesdescontos1_idx` (`cupaodesconto_id`),
  KEY `fk_usocupoes_profiles1_idx` (`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD CONSTRAINT `fk_avaliacoes_produtos1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`),
  ADD CONSTRAINT `fk_avaliacoes_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`);

--
-- Limitadores para a tabela `carrinhocompras`
--
ALTER TABLE `carrinhocompras`
  ADD CONSTRAINT `fk_carrinhocompras_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`);

--
-- Limitadores para a tabela `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `fk_compras_fornecedores1` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`);

--
-- Limitadores para a tabela `encomendas`
--
ALTER TABLE `encomendas`
  ADD CONSTRAINT `fk_encomendas_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`);

--
-- Limitadores para a tabela `faturas`
--
ALTER TABLE `faturas`
  ADD CONSTRAINT `fk_faturas_encomendas1` FOREIGN KEY (`encomenda_id`) REFERENCES `encomendas` (`id`),
  ADD CONSTRAINT `fk_faturas_metodosentregas1` FOREIGN KEY (`metodoentrega_id`) REFERENCES `metodosentregas` (`id`),
  ADD CONSTRAINT `fk_faturas_metodospagamentos1` FOREIGN KEY (`metodopagamento_id`) REFERENCES `metodospagamentos` (`id`),
  ADD CONSTRAINT `fk_faturas_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`);

--
-- Limitadores para a tabela `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `fk_favoritos_produtos1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`),
  ADD CONSTRAINT `fk_favoritos_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`);

--
-- Limitadores para a tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD CONSTRAINT `fk_fornecedores_marcas1` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`);

--
-- Limitadores para a tabela `imagens`
--
ALTER TABLE `imagens`
  ADD CONSTRAINT `fk_imagens_produtos1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

--
-- Limitadores para a tabela `linhascarrinhos`
--
ALTER TABLE `linhascarrinhos`
  ADD CONSTRAINT `fk_linhasCarrinhos_carrinhocompras1` FOREIGN KEY (`carrinhocompras_id`) REFERENCES `carrinhocompras` (`id`),
  ADD CONSTRAINT `fk_linhasCarrinhos_produtos1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

--
-- Limitadores para a tabela `linhascompras`
--
ALTER TABLE `linhascompras`
  ADD CONSTRAINT `fk_linhascompras_compras1` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`),
  ADD CONSTRAINT `fk_linhascompras_produtos1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

--
-- Limitadores para a tabela `linhasfaturas`
--
ALTER TABLE `linhasfaturas`
  ADD CONSTRAINT `fk_linhasfaturas_faturas1` FOREIGN KEY (`fatura_id`) REFERENCES `faturas` (`id`);

--
-- Limitadores para a tabela `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_produtos_categorias1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `fk_produtos_generos1` FOREIGN KEY (`genero_id`) REFERENCES `generos` (`id`),
  ADD CONSTRAINT `fk_produtos_ivas1` FOREIGN KEY (`iva_id`) REFERENCES `ivas` (`id`),
  ADD CONSTRAINT `fk_produtos_marcas` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`);

--
-- Limitadores para a tabela `produtos_has_tamanhos`
--
ALTER TABLE `produtos_has_tamanhos`
  ADD CONSTRAINT `fk_produtos_has_tamanhos_produtos1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`),
  ADD CONSTRAINT `fk_produtos_has_tamanhos_tamanhos1` FOREIGN KEY (`tamanho_id`) REFERENCES `tamanhos` (`id`);

--
-- Limitadores para a tabela `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `fk_profiles_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Limitadores para a tabela `usocupoes`
--
ALTER TABLE `usocupoes`
  ADD CONSTRAINT `fk_usocupoes_cupoesdescontos1` FOREIGN KEY (`cupaodesconto_id`) REFERENCES `cupoesdescontos` (`id`),
  ADD CONSTRAINT `fk_usocupoes_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
