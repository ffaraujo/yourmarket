-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 19-Abr-2016 às 15:32
-- Versão do servidor: 5.6.17
-- PHP Version: 5.5.12

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yourmarketdb`
--

--
-- Extraindo dados da tabela `products`
--

INSERT INTO `products` (`prd_id`, `prd_name`, `prd_create_date`, `prd_user`, `prd_active`) VALUES
(1, 'ABS ALWAYS P. TOTAL PACOT S/ ABA', '2016-04-07 21:13:23', 1, 1),
(2, 'ABS INTIMUS INTERNO MEDIO 8UN', '2016-04-07 21:13:23', 1, 1),
(3, 'ACHOCOLATADO MAGICO EM PO 200G', '2016-04-07 21:13:23', 1, 1),
(64, 'ACHOCOLATADO POWERLATE PO PT 400G', '2016-04-07 21:15:11', 1, 1),
(4, 'ACUCAR ALEGRE 1KG CRISTAL', '2016-04-07 21:13:23', 1, 1),
(78, 'ACUCAR ALEGRE 1KG TRITURADO', '2016-04-07 21:15:22', 1, 1),
(177, 'ACUCAREIRO PLASUTIL DE MESA', '2016-04-19 10:06:14', 1, 1),
(137, 'AGUA SANITARIA IGUAL 2L', '2016-04-19 10:05:02', 1, 1),
(5, 'AGUA SANITARIA OLIMPO 2L', '2016-04-07 21:13:23', 1, 1),
(6, 'ALFACE CRESPA', '2016-04-07 21:13:23', 1, 1),
(154, 'ALFACE LISA UND', '2016-04-19 10:05:02', 1, 1),
(62, 'ALGODAO BOLA CREMER C/40 UND', '2016-04-07 21:14:52', 1, 1),
(7, 'AMACIANTE URCA 2L ESTACAO', '2016-04-07 21:13:23', 1, 1),
(8, 'AP BARBEADOR BIC SENSITIVE 15X', '2016-04-07 21:13:23', 1, 1),
(210, 'ARROZ KIKA PARBOILIZADO TIPO 1', '2016-04-19 10:07:12', 1, 1),
(168, 'ARROZ NAMORADO 1KG PARBOILIZADO', '2016-04-19 10:06:13', 1, 1),
(9, 'ARROZ OURO BOM PARBOILIZADO', '2016-04-07 21:13:23', 1, 1),
(52, 'AZEITE LA VIOLETERA E.V VIDRO 50', '2016-04-07 21:14:43', 1, 1),
(105, 'AZEITONA VALE FERTIL S/C SH 120G', '2016-04-07 21:16:04', 1, 1),
(10, 'BANANA PACOVAN', '2016-04-07 21:13:23', 1, 1),
(11, 'BATATA INGLESA ESCOVADA', '2016-04-07 21:13:23', 1, 1),
(202, 'BIFE BOVINO CONTRA FILE LIGHT', '2016-04-19 10:07:12', 1, 1),
(203, 'BIFE BOVINO PALHA LOMBO PAULISTA', '2016-04-19 10:07:12', 1, 1),
(106, 'BISCOITO BAUDUCCO CEREALE 170G', '2016-04-07 21:16:04', 1, 1),
(79, 'BISCOITO NESFIT 120G CACAU E CERE', '2016-04-07 21:15:22', 1, 1),
(169, 'BISCOITO NESFIT 200G DOCES AVEIA M', '2016-04-19 10:06:13', 1, 1),
(80, 'BISCOITO PILAR CR CRACKER TRAD 400G', '2016-04-07 21:15:22', 1, 1),
(12, 'BISCOITO VITAMASSA CREAM CRACKER', '2016-04-07 21:13:23', 1, 1),
(81, 'BISCOITO VITAMASSA MARIA 400G CHOC', '2016-04-07 21:15:22', 1, 1),
(82, 'BISCOITO VITAMASSA WAFER 130G', '2016-04-07 21:15:22', 1, 1),
(108, 'BISCOITO VITARELLA WAFER 130G', '2016-04-07 21:16:04', 1, 1),
(83, 'BOLO SERAFIM SABORES 600G', '2016-04-07 21:15:22', 1, 1),
(13, 'CAFE SAO BRAZ SOLUVEL GRAN 50G', '2016-04-07 21:13:23', 1, 1),
(209, 'CAFE SAO BRAZ SOLUVEL SACHE 50G FA', '2016-04-19 10:07:12', 1, 1),
(84, 'CALDO ARISCO 114G GALINHA', '2016-04-07 21:15:22', 1, 1),
(14, 'CALDO ARISCO 57G GALINHA CAIPIRA', '2016-04-07 21:13:23', 1, 1),
(173, 'CAPPUCCINO SAO BRAZ TRADICIONA 400', '2016-04-19 10:06:14', 1, 1),
(65, 'CARNE CHARQUE SERRA NEGRA 500G', '2016-04-07 21:15:11', 1, 1),
(15, 'CEBOLA ROXA', '2016-04-07 21:13:23', 1, 1),
(16, 'CENOURA', '2016-04-07 21:13:23', 1, 1),
(180, 'CHOCOLATE BIS 126G LIMAO', '2016-04-19 10:06:14', 1, 1),
(178, 'CHOCOLATE LACTA 150G OREO', '2016-04-19 10:06:14', 1, 1),
(179, 'CHOCOLATE LACTA BIS 126GR CHOC AO', '2016-04-19 10:06:14', 1, 1),
(175, 'CHOCOLATE LACTA BIS 126GR LAKA BRA', '2016-04-19 10:06:14', 1, 1),
(17, 'COENTRO', '2016-04-07 21:13:23', 1, 1),
(86, 'CONJUNTO SANREMO POTE RETANG C/3', '2016-04-07 21:15:22', 1, 1),
(87, 'COXA E SOBRECOXA BOM TODO CONG', '2016-04-07 21:15:22', 1, 1),
(18, 'CR DENTAL COLGATE TRIPLA ACAO', '2016-04-07 21:13:23', 1, 1),
(88, 'CR DENTAL SORRISO SUPER REFRESC 180G', '2016-04-07 21:15:22', 1, 1),
(66, 'DESINFETANTE URCA 2L FLORAL', '2016-04-07 21:15:11', 1, 1),
(139, 'DESINFETANTE URCA 2L LAVANDA', '2016-04-19 10:05:02', 1, 1),
(19, 'DESOD FRANCIS AERO HYDRATTA 165ML', '2016-04-07 21:13:23', 1, 1),
(110, 'DESODORANTE DOVE AEROSOL 150ML/89G', '2016-04-07 21:16:04', 1, 1),
(197, 'DESODORANTE REXONA AEROSOL 175ML', '2016-04-19 10:07:12', 1, 1),
(20, 'DETERGENTE ALA EM PO 500G FLOR CEREJ', '2016-04-07 21:13:23', 1, 1),
(68, 'DETERGENTE LIMPOL LIQ 500ML MAC', '2016-04-07 21:15:11', 1, 1),
(198, 'DETERGENTE MAX CLEAR 2 NEUTRO', '2016-04-19 10:07:12', 1, 1),
(94, 'EMPANADO D FGO AUROGGETS CROCA', '2016-04-07 21:15:31', 1, 1),
(49, 'ESC SANITARIA CONDOR FLOWERS', '2016-04-07 21:14:33', 1, 1),
(187, 'ESCOND SADIA CARNE SE/PURE MAN', '2016-04-19 10:06:49', 1, 1),
(112, 'ESCOVA CONDOR MULT DMAO PLAST 1121', '2016-04-07 21:16:04', 1, 1),
(89, 'ESCOVA SANITARIA CONDOR C/ SUP 6100', '2016-04-07 21:15:22', 1, 1),
(54, 'ESFREBOM ALTA PERFORMANCE MULTIUSO', '2016-04-07 21:14:43', 1, 1),
(200, 'ESPONJA P/BANHO RICCA PQ', '2016-04-19 10:07:12', 1, 1),
(21, 'EXTRATO DE TOMATE QUERO TP 320G', '2016-04-07 21:13:23', 1, 1),
(113, 'FARINHA LACTEA NUTRIMENTAL REF 230', '2016-04-07 21:16:04', 1, 1),
(69, 'FAROFA YOKI CARNE SECA 250G', '2016-04-07 21:15:11', 1, 1),
(114, 'FAROFA YOKI PRONTA MANDIOCA TEMPER', '2016-04-07 21:16:04', 1, 1),
(128, 'FEIJAO KICALDO CARIOCA', '2016-04-07 21:16:59', 1, 1),
(22, 'FEIJAO LIGEIRINHO CARIOCA TIPO 2', '2016-04-07 21:13:23', 1, 1),
(95, 'FILE DE PEITO BOM TODO CONGELAD', '2016-04-07 21:15:31', 1, 1),
(55, 'FILME LUSAFILM PVC 28X15CM', '2016-04-07 21:14:43', 1, 1),
(23, 'FLOCAO NORDESTINO 500G', '2016-04-07 21:13:23', 1, 1),
(24, 'FOSFORO PARANA CARTOLINA C/10', '2016-04-07 21:13:23', 1, 1),
(194, 'FRALDA CREMER DISNEY JUMBINHO M 24', '2016-04-19 10:07:12', 1, 1),
(115, 'FUBA NOVOMILHO PRECOZIDO 500G', '2016-04-07 21:16:04', 1, 1),
(142, 'GEL PATO ADESIVO REFIL CITRUS', '2016-04-19 10:05:02', 1, 1),
(116, 'GEL PATO ADESIVO REFIL MARINE', '2016-04-07 21:16:04', 1, 1),
(150, 'GELEIA INBASA MOCOTO ARISCO TB', '2016-04-19 10:05:02', 1, 1),
(147, 'GELEIA PREDILECTA FRAMBOESA PT', '2016-04-19 10:05:02', 1, 1),
(181, 'GELEIA PREDILECTA MORANGO POTE 230', '2016-04-19 10:06:14', 1, 1),
(56, 'GRANOLA TIA SONIA TRADICIONAL', '2016-04-07 21:14:43', 1, 1),
(70, 'INHAME', '2016-04-07 21:15:11', 1, 1),
(138, 'INSET BAYGON ACAO TT ECONOMIC 3', '2016-04-19 10:05:02', 1, 1),
(117, 'IOGURTE LEBOM 900G C/MEL', '2016-04-07 21:16:04', 1, 1),
(96, 'IOGURTE LEBOM 900G MORANGO CERE', '2016-04-07 21:15:31', 1, 1),
(205, 'IOGURTE NESTLE GREGO 3 SAB LIG 540', '2016-04-19 10:07:12', 1, 1),
(129, 'LANCHE DE FRG PERDIGAO CHEST', '2016-04-07 21:16:59', 1, 1),
(25, 'LARANJA PERA', '2016-04-07 21:13:23', 1, 1),
(161, 'LASANHA SEARA 600G CALABRESA', '2016-04-19 10:05:31', 1, 1),
(26, 'LEITE CAMPONESA EM PO 200G INTE', '2016-04-07 21:13:23', 1, 1),
(97, 'LEITE CAMPONESA PO 800G INTEGRA', '2016-04-07 21:15:31', 1, 1),
(27, 'LIMAO TAITY', '2016-04-07 21:13:23', 1, 1),
(145, 'LING PERDIGAO MISTA COZIDA DEF', '2016-04-19 10:05:02', 1, 1),
(72, 'LINGUICA SEARA CALABRESA CURADA', '2016-04-07 21:15:11', 1, 1),
(183, 'MACA NACIONAL', '2016-04-19 10:06:14', 1, 1),
(28, 'MACARRAO ESPAGUETE ALIANCA 500G', '2016-04-07 21:13:23', 1, 1),
(29, 'MACARRAO VITARELLA LAMEN 85G', '2016-04-07 21:13:23', 1, 1),
(130, 'MACAXEIRA JACARAUNA CONGELADA', '2016-04-07 21:16:59', 1, 1),
(57, 'MAMAO FORMOSA', '2016-04-07 21:14:43', 1, 1),
(162, 'MANTEIGA CAMPONESA PT 500G', '2016-04-19 10:05:31', 1, 1),
(118, 'MANTEIGA LEBOM POTE 500G', '2016-04-07 21:16:04', 1, 1),
(185, 'MELAO GALIA', '2016-04-19 10:06:14', 1, 1),
(192, 'MOLHO LIZA P/SALADA 234ML MEL/M', '2016-04-19 10:06:49', 1, 1),
(119, 'MOLHO MASTERFOODS P/SALADA 234ML', '2016-04-07 21:16:04', 1, 1),
(171, 'MOLHO QUERO REFOG PRONTO TP 1,050K', '2016-04-19 10:06:14', 1, 1),
(120, 'MOLHO QUERO REFOGADO PENEIR TP 520', '2016-04-07 21:16:04', 1, 1),
(211, 'MOP BETTANIN ZIG ZAG UN', '2016-04-19 10:07:12', 1, 1),
(30, 'NAFTALINA IGUAL 40G', '2016-04-07 21:13:23', 1, 1),
(31, 'OLEO SOYA DE SOJA PET 900ML', '2016-04-07 21:13:23', 1, 1),
(159, 'OVO DE PASCOA NESTLE STAR WARS', '2016-04-19 10:05:31', 1, 1),
(32, 'OVOS BEMAIS GRANDE BRANCO C/30', '2016-04-07 21:13:23', 1, 1),
(50, 'PANO DE CHAO CRU', '2016-04-07 21:14:33', 1, 1),
(33, 'PAO DE FORMA PAO DE MEL 400G', '2016-04-07 21:13:23', 1, 1),
(99, 'PAO DE FORMA PAO DE MEL 400G IN', '2016-04-07 21:15:31', 1, 1),
(133, 'PAO DE FORMA PAO DE MEL 400G INTEG', '2016-04-07 21:18:45', 1, 1),
(102, 'PAO FRANCES PAN CRISTAL TRAD', '2016-04-07 21:15:41', 1, 1),
(91, 'PAO MAXVITTA 500G INTEGRAL', '2016-04-07 21:15:22', 1, 1),
(131, 'PAO PAO DE MEL 300G 12 GRAOS', '2016-04-07 21:16:59', 1, 1),
(121, 'PAO PAO DE MEL 300G SOJA', '2016-04-07 21:16:04', 1, 1),
(58, 'PAPEL HIGIENICO NEVE COMP', '2016-04-07 21:14:43', 1, 1),
(59, 'PAPEL TOALHA LEVE FOLHA DUPLA', '2016-04-07 21:14:43', 1, 1),
(136, 'PASSADEIRA NC JURANDIR UN', '2016-04-19 10:05:02', 1, 1),
(170, 'PASTA DE ALHO E CEBOLA IPE 200G S/', '2016-04-19 10:06:14', 1, 1),
(34, 'PASTA DE ALHO IPE 200G SEM SAL', '2016-04-07 21:13:23', 1, 1),
(73, 'PEITO FRANGO BOM TODO CONG', '2016-04-07 21:15:11', 1, 1),
(189, 'PEITO PERU SEARA LIGHT DEFUMADO', '2016-04-19 10:06:49', 1, 1),
(60, 'PENEIRA IGUAL PLASTICA N4', '2016-04-07 21:14:43', 1, 1),
(61, 'PILHA PANASONIC RECARREG AAA', '2016-04-07 21:14:43', 1, 1),
(35, 'PIPOCA YOKI', '2016-04-07 21:13:23', 1, 1),
(174, 'PIPOCA YOKI POPCORN 100G', '2016-04-19 10:06:14', 1, 1),
(122, 'PIPOCA YOKI POPCORN 100G MICRO', '2016-04-07 21:16:04', 1, 1),
(74, 'PIPOCA ZAELI MICROONDAS 100G BACON', '2016-04-07 21:15:11', 1, 1),
(92, 'PIZZA SEARA 460G LOMBO CATUPIRY', '2016-04-07 21:15:22', 1, 1),
(36, 'POLPA BEMAIS 100G', '2016-04-07 21:13:23', 1, 1),
(143, 'POLPA BEMAIS GRAVIOLA 100G', '2016-04-19 10:05:02', 1, 1),
(75, 'POLPA IDEAL 100G', '2016-04-07 21:15:11', 1, 1),
(144, 'POLPA IDEAL MISTA ABACAXI/HORT', '2016-04-19 10:05:02', 1, 1),
(160, 'POTE VAC SANREMO FREEZER 1,5L', '2016-04-19 10:05:31', 1, 1),
(51, 'PRENDEDORES ROUPA ETM PLASTICO', '2016-04-07 21:14:33', 1, 1),
(123, 'PRESUNTO PERDIGAO MAGR OVAL TRAD', '2016-04-07 21:16:04', 1, 1),
(76, 'PRESUNTO PERDIGAO PERU', '2016-04-07 21:15:11', 1, 1),
(37, 'PRESUNTO SADIA PERU COZIDO', '2016-04-07 21:13:23', 1, 1),
(184, 'QUEIJO AURORA MUSSARELA', '2016-04-19 10:06:14', 1, 1),
(149, 'QUEIJO BURITIS PARM PICO BCO FRA', '2016-04-19 10:05:02', 1, 1),
(124, 'QUEIJO DAVACA MUSSARELA LIGHT', '2016-04-07 21:16:04', 1, 1),
(201, 'QUEIJO DAVACA PRATO LIGHT', '2016-04-19 10:07:12', 1, 1),
(77, 'QUEIJO LEBOM MUSSARELA', '2016-04-07 21:15:11', 1, 1),
(38, 'QUEIJO MUSSARELA DAMARE', '2016-04-07 21:13:23', 1, 1),
(100, 'QUEIJO MUSSARELA LA MUCCA', '2016-04-07 21:15:31', 1, 1),
(188, 'QUEIJO RICOT FRES SANTO EXPEDITO', '2016-04-19 10:06:49', 1, 1),
(101, 'RECIP SANREMO REDO FREEZ BAIXO', '2016-04-07 21:15:31', 1, 1),
(39, 'REFRESCO FRESH 15G', '2016-04-07 21:13:23', 1, 1),
(176, 'REFRESCO TANG MATE C/LARA 25G', '2016-04-19 10:06:14', 1, 1),
(125, 'REFRIGERANTE ANTARCTIC GUARANA 2,5', '2016-04-07 21:16:04', 1, 1),
(165, 'REFRIGERANTE ANTARCTIC GUARANA 3,3', '2016-04-19 10:06:13', 1, 1),
(63, 'REMOV ESMALTE BASE ACET IDEAL', '2016-04-07 21:14:52', 1, 1),
(40, 'REPOLHO VERDE', '2016-04-07 21:13:23', 1, 1),
(191, 'ROCAMBOLE MAXVITTA 400G', '2016-04-19 10:06:49', 1, 1),
(212, 'RODO BETTANIN BALLENA 30CM', '2016-04-19 10:07:12', 1, 1),
(155, 'RUCULA UN', '2016-04-19 10:05:02', 1, 1),
(41, 'SABAO BARRA REDENCAO AMARELO 5X', '2016-04-07 21:13:23', 1, 1),
(141, 'SABONETE EVEN SUAVE 90G', '2016-04-19 10:05:02', 1, 1),
(140, 'SABONETE NIVEA HIDRATANTE 90G', '2016-04-19 10:05:02', 1, 1),
(42, 'SACO P/ LIXO IGUAL 15LT', '2016-04-07 21:13:23', 1, 1),
(43, 'SAL MIRAMAR', '2016-04-07 21:13:23', 1, 1),
(146, 'SALSICHA SEARA FRANGO 500G', '2016-04-19 10:05:02', 1, 1),
(195, 'SHAMPOO DISNEY BABY CREMER 200ML N', '2016-04-19 10:07:12', 1, 1),
(126, 'SHAMPOO PALMOLIVE NATURALS 350ML', '2016-04-07 21:16:04', 1, 1),
(127, 'SHAMPOO TRESEMME 400ML PER DESARRU', '2016-04-07 21:16:04', 1, 1),
(44, 'SOBRECOXA BOM TODO CONGELADA', '2016-04-07 21:13:23', 1, 1),
(208, 'SORVETE BEMAIS 2LT PAVE', '2016-04-19 10:07:12', 1, 1),
(182, 'SORVETE ZECAS 2L BEM CASADO', '2016-04-19 10:06:14', 1, 1),
(152, 'TEMPERO MAGGI TEMPERO E SABOR 5', '2016-04-19 10:05:02', 1, 1),
(45, 'TEMPERO SAZON 60G VERMELHO', '2016-04-07 21:13:23', 1, 1),
(164, 'TM OMO SUP AC RP COLOR 60G', '2016-04-19 10:06:13', 1, 1),
(46, 'TOMATE', '2016-04-07 21:13:23', 1, 1),
(204, 'TORTA SADIA IOG DE PALMI/CATUP 500', '2016-04-19 10:07:12', 1, 1),
(132, 'TORTA SADIA IOGURTE DE FRANGO', '2016-04-07 21:16:59', 1, 1),
(134, 'TRIO SM LIGHT C/3UN BANAN/CHOCOLAT', '2016-04-07 21:18:45', 1, 1),
(47, 'VELA SANTA CLARA N03', '2016-04-07 21:13:23', 1, 1),
(48, 'VINAGRE MARATA ALCOOL 750ML', '2016-04-07 21:13:23', 1, 1);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;