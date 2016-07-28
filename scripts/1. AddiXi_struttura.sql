-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: 26 set, 2011 at 08:03 PM
-- Versione MySQL: 5.1.41
-- Versione PHP: 5.3.8-ZS5.5.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `AddiXi`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `Allarmi Giacenze`
--

CREATE TABLE IF NOT EXISTS `Allarmi Giacenze` (
  `idAllarme` int(11) NOT NULL AUTO_INCREMENT,
  `idArticoloSpecifico` int(11) NOT NULL,
  `Ora` datetime DEFAULT NULL,
  `Quantità` int(11) DEFAULT NULL,
  `Risolto` tinyint(1) NOT NULL DEFAULT '0',
  `Note` text,
  PRIMARY KEY (`idAllarme`),
  KEY `fk_Allarmi Giacenze_Articolo Specifico1` (`idArticoloSpecifico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Allegati`
--

CREATE TABLE IF NOT EXISTS `Allegati` (
  `idAllegato` int(11) NOT NULL,
  `idTipoAllegato` int(11) DEFAULT NULL,
  `URI` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idAllegato`),
  KEY `fk_Allegati_Tipi Allegato1` (`idTipoAllegato`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Allegati Articoli`
--

CREATE TABLE IF NOT EXISTS `Allegati Articoli` (
  `idAllegatoArticolo` int(11) NOT NULL AUTO_INCREMENT,
  `idAllegato` int(11) NOT NULL,
  `idArticoloSpecifico` int(11) NOT NULL,
  PRIMARY KEY (`idAllegatoArticolo`),
  KEY `fk_Allegati Articoli_Allegati1` (`idAllegato`),
  KEY `fk_Allegati Articoli_Articoli Specifici1` (`idArticoloSpecifico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Amministratori`
--

CREATE TABLE IF NOT EXISTS `Amministratori` (
  `idAmministratore` int(11) NOT NULL AUTO_INCREMENT,
  `idGruppo` int(11) NOT NULL,
  `Nome` varchar(50) DEFAULT NULL,
  `Cognome` varchar(50) DEFAULT NULL,
  `Nome Utente` varchar(40) NOT NULL,
  `Password` varchar(40) NOT NULL,
  `Note` text,
  `Attivo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idAmministratore`),
  KEY `fk_Utenti_Gruppi Utente1` (`idGruppo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Articoli Correlati`
--

CREATE TABLE IF NOT EXISTS `Articoli Correlati` (
  `idCorrelazione` int(11) NOT NULL AUTO_INCREMENT,
  `idArticoloSpecifico1` int(11) NOT NULL,
  `idArticoloSpecifico2` int(11) NOT NULL,
  PRIMARY KEY (`idCorrelazione`),
  KEY `fk_Articoli Correlati_Articoli Specifici1` (`idArticoloSpecifico1`),
  KEY `fk_Articoli Correlati_Articoli Specifici2` (`idArticoloSpecifico2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Articoli Generici`
--

CREATE TABLE IF NOT EXISTS `Articoli Generici` (
  `idArticolo` int(11) NOT NULL AUTO_INCREMENT,
  `idCategoria` int(11) NOT NULL,
  `idUnità` int(11) DEFAULT NULL,
  `idMarca` int(11) DEFAULT NULL,
  `Note` text,
  `Attivo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idArticolo`),
  KEY `fk_Articoli_Sottocategorie Articoli1` (`idCategoria`),
  KEY `fk_Articoli_Unità di Misura1` (`idUnità`),
  KEY `fk_Articoli_Marche1` (`idMarca`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Articoli Listino Speciale`
--

CREATE TABLE IF NOT EXISTS `Articoli Listino Speciale` (
  `idArticoloListino` int(11) NOT NULL AUTO_INCREMENT,
  `idListino` int(11) NOT NULL,
  `idArticoloSpecifico` int(11) NOT NULL,
  `Sconto` decimal(5,2) DEFAULT NULL,
  `Segno Sconto` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`idArticoloListino`),
  KEY `fk_Listino Speciale_has_Articoli_Listino Speciale1` (`idListino`),
  KEY `fk_Articoli Listino Speciale_Articolo Specifico1` (`idArticoloSpecifico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Articoli Specifici`
--

CREATE TABLE IF NOT EXISTS `Articoli Specifici` (
  `idArticoloSpecifico` int(11) NOT NULL AUTO_INCREMENT,
  `idArticolo` int(11) NOT NULL,
  `idImmagineTagliata` int(11) DEFAULT NULL,
  `idColore` int(11) DEFAULT NULL,
  `idTaglia` int(11) DEFAULT NULL,
  `idValuta` int(11) DEFAULT NULL,
  `Data` date DEFAULT NULL,
  `Prezzo d'acquisto` decimal(12,2) DEFAULT NULL,
  `Prezzo di vendita` decimal(12,2) DEFAULT NULL,
  `Ricarico` decimal(5,2) DEFAULT NULL,
  `Quantità` int(11) NOT NULL,
  `Giacenza Minima` int(11) DEFAULT NULL,
  `Codice Articolo` varchar(50) DEFAULT NULL,
  `Vecchio Codice Articolo` varchar(50) DEFAULT NULL,
  `Note` text,
  `Attivo` tinyint(1) NOT NULL DEFAULT '1',
  `Fuori Produzione` tinyint(1) DEFAULT '0',
  `Offerta` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`idArticoloSpecifico`),
  KEY `fk_Articoli_has_Colori_Articoli` (`idArticolo`),
  KEY `fk_Articoli Specifici_Colori1` (`idColore`),
  KEY `fk_Articoli Specifici_Taglie1` (`idTaglia`),
  KEY `fk_Articoli Specifici_Valute1` (`idValuta`),
  KEY `fk_Articoli Specifici_Immagini Tagliate1` (`idImmagineTagliata`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura Stand-in per le viste `Articoli View`
--
CREATE TABLE IF NOT EXISTS `Articoli View` (
`idArticoloSpecifico` int(11)
,`idArticolo` int(11)
,`idImmagineTagliata` int(11)
,`idColore` int(11)
,`idTaglia` int(11)
,`idValuta` int(11)
,`Data` date
,`Prezzo d'acquisto` decimal(12,2)
,`Prezzo di vendita` decimal(12,2)
,`Ricarico` decimal(5,2)
,`Quantità` int(11)
,`Giacenza Minima` int(11)
,`Codice Articolo` varchar(50)
,`Vecchio Codice Articolo` varchar(50)
,`Note` text
,`Attivo` tinyint(1)
,`Fuori Produzione` tinyint(1)
,`Offerta` tinyint(1)
,`idCategoria` int(11)
,`idMarca` int(11)
,`Marca` varchar(50)
,`Sito` varchar(80)
,`idUnità` int(11)
,`Nome` varchar(100)
,`Descrizione` text
,`Path Immagine` varchar(255)
,`Thumbnail Base64` text
,`QuantitàEsatte` tinyint(1)
);
-- --------------------------------------------------------

--
-- Struttura della tabella `Attributi`
--

CREATE TABLE IF NOT EXISTS `Attributi` (
  `idAttributo` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(50) DEFAULT NULL,
  `Descrizione` varchar(200) DEFAULT NULL,
  `Valore Libero` tinyint(1) NOT NULL DEFAULT '1',
  `Note` text,
  PRIMARY KEY (`idAttributo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Attributi Richiesti`
--

CREATE TABLE IF NOT EXISTS `Attributi Richiesti` (
  `idAttributoRichiesto` int(11) NOT NULL AUTO_INCREMENT,
  `idCategoria` int(11) NOT NULL,
  `idAttributo` int(11) NOT NULL,
  `Attributo Specificante` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idAttributoRichiesto`),
  KEY `fk_Attributi di Categoria_Categorie Articoli1` (`idCategoria`),
  KEY `fk_Attributi di Categoria_Attributi1` (`idAttributo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Bolle`
--

CREATE TABLE IF NOT EXISTS `Bolle` (
  `idBolla` int(11) NOT NULL AUTO_INCREMENT,
  `idOrdine` int(11) NOT NULL,
  `idMovimento` int(11) NOT NULL,
  `Parziale` tinyint(1) NOT NULL DEFAULT '0',
  `Numero Bolla` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`idBolla`),
  KEY `fk_Bolle_Movimenti1` (`idMovimento`),
  KEY `fk_Bolle_Ordini1` (`idOrdine`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Categorie Articoli`
--

CREATE TABLE IF NOT EXISTS `Categorie Articoli` (
  `idCategoria` int(11) NOT NULL AUTO_INCREMENT,
  `idCategoriaPadre` int(11) DEFAULT NULL,
  `Note` text,
  `Attivo` tinyint(1) NOT NULL DEFAULT '1',
  `Ordine` int(11) DEFAULT NULL,
  `QuantitàEsatte` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`idCategoria`),
  KEY `fk_Categorie Articoli_Categorie Articoli1` (`idCategoriaPadre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Causali`
--

CREATE TABLE IF NOT EXISTS `Causali` (
  `idCausale` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(100) NOT NULL,
  `Descrizione` varchar(200) DEFAULT NULL,
  `Direzione` varchar(1) NOT NULL,
  PRIMARY KEY (`idCausale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Clienti`
--

CREATE TABLE IF NOT EXISTS `Clienti` (
  `idCliente` int(11) NOT NULL AUTO_INCREMENT,
  `idUtente` int(11) DEFAULT NULL,
  `Nome` varchar(100) NOT NULL,
  `Cognome` varchar(100) DEFAULT NULL,
  `Intestazione` varchar(200) DEFAULT NULL,
  `Partita IVA` varchar(11) DEFAULT NULL,
  `Codice Fiscale` varchar(16) DEFAULT NULL,
  `Indirizzo` varchar(150) DEFAULT NULL,
  `CAP` varchar(10) DEFAULT NULL,
  `Città` varchar(100) DEFAULT NULL,
  `Provincia` varchar(50) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Nominativo Riferimento` varchar(50) DEFAULT NULL,
  `Note` text,
  `Principale` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idCliente`),
  KEY `fk_Clienti_Utenti1` (`idUtente`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Clienti Listino Speciale`
--

CREATE TABLE IF NOT EXISTS `Clienti Listino Speciale` (
  `idClienteListino` int(11) NOT NULL AUTO_INCREMENT,
  `idListino` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  PRIMARY KEY (`idClienteListino`),
  KEY `fk_Clienti Listino Speciale_Listino Speciale1` (`idListino`),
  KEY `fk_Clienti Listino Speciale_Clienti1` (`idCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura Stand-in per le viste `Clienti View`
--
CREATE TABLE IF NOT EXISTS `Clienti View` (
`idCliente` int(11)
,`idUtente` int(11)
,`Nome` varchar(100)
,`Cognome` varchar(100)
,`Intestazione` varchar(200)
,`Partita IVA` varchar(11)
,`Codice Fiscale` varchar(16)
,`Indirizzo` varchar(150)
,`CAP` varchar(10)
,`Città` varchar(100)
,`Provincia` varchar(50)
,`Email` varchar(50)
,`Nominativo Riferimento` varchar(50)
,`Note` text
,`Principale` tinyint(1)
,`Nome Utente` varchar(40)
,`Password` varchar(40)
,`Attivazione` varchar(40)
,`Attivo` tinyint(1)
,`Data Registrazione` datetime
,`idLingua` int(11)
);
-- --------------------------------------------------------

--
-- Struttura della tabella `Codici a Barre`
--

CREATE TABLE IF NOT EXISTS `Codici a Barre` (
  `idCodice` int(11) NOT NULL AUTO_INCREMENT,
  `idArticolo` int(11) DEFAULT NULL,
  `idArticoloSpecifico` int(11) DEFAULT NULL,
  `Tipo` varchar(20) DEFAULT NULL,
  `Codice` varchar(50) DEFAULT NULL,
  `Formato` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`idCodice`),
  KEY `fk_Codice a Barre_Articolo Specifico1` (`idArticoloSpecifico`),
  KEY `fk_Codici a Barre_Articoli Generici1` (`idArticolo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Colori`
--

CREATE TABLE IF NOT EXISTS `Colori` (
  `idColore` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idColore`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Comunicazioni`
--

CREATE TABLE IF NOT EXISTS `Comunicazioni` (
  `idComunicazione` int(11) NOT NULL AUTO_INCREMENT,
  `idUtente` int(11) NOT NULL,
  `Oggetto` varchar(100) DEFAULT NULL,
  `Testo` text,
  `idRisposta` int(11) DEFAULT NULL,
  `Note` text,
  PRIMARY KEY (`idComunicazione`),
  KEY `fk_Comunicazioni_Risposte1` (`idRisposta`),
  KEY `fk_Comunicazioni_Utenti1` (`idUtente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Cronjobs`
--

CREATE TABLE IF NOT EXISTS `Cronjobs` (
  `idCronjob` int(11) NOT NULL AUTO_INCREMENT,
  `Nome Classe` varchar(100) NOT NULL,
  `Prossimo Run` datetime NOT NULL,
  PRIMARY KEY (`idCronjob`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Descrizioni Articoli`
--

CREATE TABLE IF NOT EXISTS `Descrizioni Articoli` (
  `idDescrizioneArticolo` int(11) NOT NULL AUTO_INCREMENT,
  `idArticolo` int(11) DEFAULT NULL,
  `idArticoloSpecifico` int(11) DEFAULT NULL,
  `idLingua` int(11) NOT NULL,
  `Descrizione` text,
  PRIMARY KEY (`idDescrizioneArticolo`),
  KEY `fk_table1_Articoli Specifici1` (`idArticoloSpecifico`),
  KEY `fk_table1_Lingue1` (`idLingua`),
  KEY `fk_table1_Articoli Generici1` (`idArticolo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Descrizioni Categorie`
--

CREATE TABLE IF NOT EXISTS `Descrizioni Categorie` (
  `idDescrizioneCategoria` int(11) NOT NULL AUTO_INCREMENT,
  `idCategoria` int(11) NOT NULL,
  `idLingua` int(11) NOT NULL,
  `Descrizione` text,
  PRIMARY KEY (`idDescrizioneCategoria`),
  KEY `fk_Descrizioni Categorie_Categorie Articoli1` (`idCategoria`),
  KEY `fk_Descrizioni Categorie_Lingue1` (`idLingua`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Documenti`
--

CREATE TABLE IF NOT EXISTS `Documenti` (
  `idDocumento` int(11) NOT NULL AUTO_INCREMENT,
  `idTipoDocumento` int(11) NOT NULL,
  `Numero` varchar(10) DEFAULT NULL,
  `Anno` int(11) DEFAULT NULL,
  `Data` date DEFAULT NULL,
  `Note` varchar(200) DEFAULT NULL,
  `idModalitàPagamento` int(11) DEFAULT NULL,
  `Numero Pagamento` varchar(50) DEFAULT NULL,
  `File` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idDocumento`),
  KEY `fk_Documenti_Tipi Documento1` (`idTipoDocumento`),
  KEY `fk_Documenti_Modalità Pagamento1` (`idModalitàPagamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Email`
--

CREATE TABLE IF NOT EXISTS `Email` (
  `idEmail` int(11) NOT NULL AUTO_INCREMENT,
  `Mittente` tinytext,
  `Destinatario` tinytext NOT NULL,
  `Oggetto` varchar(100) DEFAULT NULL,
  `Body` text,
  `Inviata` tinyint(1) DEFAULT '0',
  `Tentativi` int(11) DEFAULT '0',
  `Ultimo Tentativo` datetime DEFAULT NULL,
  PRIMARY KEY (`idEmail`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Fornitori`
--

CREATE TABLE IF NOT EXISTS `Fornitori` (
  `idFornitore` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(100) NOT NULL,
  `Intestazione` varchar(200) DEFAULT NULL,
  `Partita IVA` varchar(11) DEFAULT NULL,
  `Codice Fiscale` varchar(16) DEFAULT NULL,
  `Indirizzo` varchar(150) DEFAULT NULL,
  `Città` varchar(50) DEFAULT NULL,
  `Nominativo Riferimento` varchar(50) DEFAULT NULL,
  `Note` varchar(200) DEFAULT NULL,
  `Sito` varchar(50) DEFAULT NULL,
  `Nome Utente Sito` varchar(50) DEFAULT NULL,
  `Password Sito` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idFornitore`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Fornitori Articolo`
--

CREATE TABLE IF NOT EXISTS `Fornitori Articolo` (
  `idFornitoreArticolo` int(11) NOT NULL AUTO_INCREMENT,
  `idArticolo` int(11) NOT NULL,
  `idFornitore` int(11) NOT NULL,
  `Codice Articolo Fornitore` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idFornitoreArticolo`),
  KEY `fk_Articoli_has_Fornitori_Articoli1` (`idArticolo`),
  KEY `fk_Articoli_has_Fornitori_Fornitori1` (`idFornitore`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Gruppi Amministratore`
--

CREATE TABLE IF NOT EXISTS `Gruppi Amministratore` (
  `idGruppo` int(11) NOT NULL AUTO_INCREMENT,
  `Nome Gruppo` varchar(50) DEFAULT NULL,
  `Permessi` varchar(20) DEFAULT NULL,
  `Note` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`idGruppo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Immagini`
--

CREATE TABLE IF NOT EXISTS `Immagini` (
  `idImmagine` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(40) DEFAULT NULL,
  `Path` varchar(255) DEFAULT NULL,
  `Formato` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`idImmagine`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Immagini Tagliate`
--

CREATE TABLE IF NOT EXISTS `Immagini Tagliate` (
  `idImmagineTagliata` int(11) NOT NULL AUTO_INCREMENT,
  `idTaglio` int(11) DEFAULT NULL,
  `idImmagine` int(11) NOT NULL,
  `Path` varchar(255) DEFAULT NULL,
  `Thumbnail Base64` text,
  PRIMARY KEY (`idImmagineTagliata`),
  KEY `fk_Immagini Tagliate_Tagli Immagini1` (`idTaglio`),
  KEY `fk_Immagini Tagliate_Immagini1` (`idImmagine`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Impostazioni`
--

CREATE TABLE IF NOT EXISTS `Impostazioni` (
  `idImpostazione` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(50) DEFAULT NULL,
  `Valore` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idImpostazione`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Linee Bolle`
--

CREATE TABLE IF NOT EXISTS `Linee Bolle` (
  `idLineaBolla` int(11) NOT NULL AUTO_INCREMENT,
  `idLineaOrdine` int(11) NOT NULL,
  `idBolla` int(11) NOT NULL,
  `Quantità` int(11) NOT NULL,
  `Descrizione` varchar(200) DEFAULT NULL,
  `Importo` decimal(12,2) DEFAULT NULL,
  `idMovimentoArticolo` int(11) NOT NULL,
  PRIMARY KEY (`idLineaBolla`),
  KEY `fk_Linee Bolle_Bolle1` (`idBolla`),
  KEY `fk_Linee Bolle_Movimenti Articoli1` (`idMovimentoArticolo`),
  KEY `fk_Linee Bolle_Linee Ordini1` (`idLineaOrdine`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Linee Ordini`
--

CREATE TABLE IF NOT EXISTS `Linee Ordini` (
  `idLinea` int(11) NOT NULL AUTO_INCREMENT,
  `idOrdine` int(11) NOT NULL,
  `Quantità Ordinata` int(11) NOT NULL,
  `Quantità in Consegna` int(11) NOT NULL,
  `Descrizione` varchar(200) DEFAULT NULL,
  `Importo` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`idLinea`),
  KEY `fk_Ordini_has_Articolo Specifico_Ordini1` (`idOrdine`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Linee Vendita`
--

CREATE TABLE IF NOT EXISTS `Linee Vendita` (
  `idLinea` int(11) NOT NULL AUTO_INCREMENT,
  `idVendita` int(11) NOT NULL,
  `idMovimentoArticolo` int(11) DEFAULT NULL,
  `Sospesa` tinyint(1) DEFAULT NULL,
  `Descrizione` varchar(200) DEFAULT NULL,
  `Importo` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`idLinea`),
  KEY `fk_Linee Vendita_Vendite1` (`idVendita`),
  KEY `fk_Linee Vendita_Articoli Movimento1` (`idMovimentoArticolo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Lingue`
--

CREATE TABLE IF NOT EXISTS `Lingue` (
  `idLingua` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(30) DEFAULT NULL,
  `Sigla` varchar(5) DEFAULT NULL,
  `Attiva` tinyint(1) NOT NULL DEFAULT '1',
  `Dominio` varchar(50) DEFAULT NULL,
  `Langtag` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`idLingua`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Listino Speciale`
--

CREATE TABLE IF NOT EXISTS `Listino Speciale` (
  `idListino` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idListino`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Marche`
--

CREATE TABLE IF NOT EXISTS `Marche` (
  `idMarca` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(50) DEFAULT NULL,
  `Sito` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`idMarca`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Modalità Pagamento`
--

CREATE TABLE IF NOT EXISTS `Modalità Pagamento` (
  `idModalità Pagamento` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(50) DEFAULT NULL,
  `Richiede Numero` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idModalità Pagamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Motori di ricerca`
--

CREATE TABLE IF NOT EXISTS `Motori di ricerca` (
  `idMotore` int(11) NOT NULL AUTO_INCREMENT,
  `Nome Motore` varchar(45) DEFAULT NULL,
  `URL Ping Sitemap` varchar(200) DEFAULT NULL,
  `Ultimo Ping Sitemap` datetime DEFAULT NULL,
  PRIMARY KEY (`idMotore`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Movimenti`
--

CREATE TABLE IF NOT EXISTS `Movimenti` (
  `idMovimento` int(11) NOT NULL AUTO_INCREMENT,
  `Data` date DEFAULT NULL,
  `idCausale` int(11) DEFAULT NULL,
  `Manuale` tinyint(1) NOT NULL DEFAULT '0',
  `Provvisorio` tinyint(1) NOT NULL DEFAULT '1',
  `idDocumento` int(11) DEFAULT NULL,
  PRIMARY KEY (`idMovimento`),
  KEY `fk_Movimenti_Documenti1` (`idDocumento`),
  KEY `fk_Movimenti_Causali1` (`idCausale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Movimenti Articoli`
--

CREATE TABLE IF NOT EXISTS `Movimenti Articoli` (
  `idMovimentoArticolo` int(11) NOT NULL AUTO_INCREMENT,
  `idMovimento` int(11) NOT NULL,
  `idArticoloSpecifico` int(11) NOT NULL,
  `Direzione` varchar(1) NOT NULL,
  `Quantità` int(11) NOT NULL,
  `Prezzo acquisto unitario` decimal(12,2) DEFAULT NULL,
  `Prezzo vendita unitario` decimal(12,2) DEFAULT NULL,
  `Sconto` decimal(5,2) DEFAULT NULL,
  `Tipo Sconto` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`idMovimentoArticolo`),
  KEY `fk_Movimenti_has_Articolo Specifico_Movimenti2` (`idMovimento`),
  KEY `fk_Movimenti_has_Articolo Specifico_Articolo Specifico2` (`idArticoloSpecifico`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Nomi Articoli`
--

CREATE TABLE IF NOT EXISTS `Nomi Articoli` (
  `idNomeArticolo` int(11) NOT NULL AUTO_INCREMENT,
  `idArticolo` int(11) DEFAULT NULL,
  `idArticoloSpecifico` int(11) DEFAULT NULL,
  `idLingua` int(11) NOT NULL,
  `Nome` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idNomeArticolo`),
  KEY `fk_Nomi Articoli_Articoli Generici1` (`idArticolo`),
  KEY `fk_Nomi Articoli_Lingue1` (`idLingua`),
  KEY `fk_Nomi Articoli_Articoli Specifici1` (`idArticoloSpecifico`),
  KEY `IndexNomiArticoli` (`Nome`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Nomi Categorie`
--

CREATE TABLE IF NOT EXISTS `Nomi Categorie` (
  `idNomeCategoria` int(11) NOT NULL AUTO_INCREMENT,
  `idCategoria` int(11) DEFAULT NULL,
  `idLingua` int(11) NOT NULL,
  `Nome` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idNomeCategoria`),
  KEY `fk_table1_Categorie Articoli1` (`idCategoria`),
  KEY `fk_table1_Lingue2` (`idLingua`),
  KEY `IndexNomiCategorie` (`Nome`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Ordini`
--

CREATE TABLE IF NOT EXISTS `Ordini` (
  `idOrdine` int(11) NOT NULL AUTO_INCREMENT,
  `idFornitore` int(11) NOT NULL,
  `Data` date DEFAULT NULL,
  `Bozza` tinyint(1) NOT NULL,
  PRIMARY KEY (`idOrdine`),
  KEY `fk_Ordini_Fornitori1` (`idFornitore`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Primanota`
--

CREATE TABLE IF NOT EXISTS `Primanota` (
  `idPrimanota` int(11) NOT NULL AUTO_INCREMENT,
  `Mese` int(11) NOT NULL,
  `Anno` int(11) NOT NULL,
  `Data Generazione` date DEFAULT NULL,
  `Note` text,
  PRIMARY KEY (`idPrimanota`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Risposte`
--

CREATE TABLE IF NOT EXISTS `Risposte` (
  `idRisposta` int(11) NOT NULL AUTO_INCREMENT,
  `idAmministratore` int(11) NOT NULL,
  `Oggetto` varchar(100) DEFAULT NULL,
  `Testo` varchar(1000) DEFAULT NULL,
  `idComunicazione` int(11) NOT NULL,
  `Note` text,
  PRIMARY KEY (`idRisposta`),
  KEY `fk_Risposte_Comunicazioni1` (`idComunicazione`),
  KEY `fk_Risposte_Amministratori1` (`idAmministratore`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Storico Prezzi Articoli`
--

CREATE TABLE IF NOT EXISTS `Storico Prezzi Articoli` (
  `idPrezzo` int(11) NOT NULL AUTO_INCREMENT,
  `idArticoloSpecifico` int(11) NOT NULL,
  `Data` date DEFAULT NULL,
  `Prezzo d'acquisto` decimal(12,2) DEFAULT NULL,
  `Prezzo di vendita` decimal(12,2) DEFAULT NULL,
  `Ricarico` decimal(5,2) DEFAULT NULL,
  `Sconto` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`idPrezzo`),
  KEY `fk_Storico Prezzi Articolo_Articolo Specifico1` (`idArticoloSpecifico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Tag`
--

CREATE TABLE IF NOT EXISTS `Tag` (
  `idTag` int(11) NOT NULL AUTO_INCREMENT,
  `idLingua` int(11) NOT NULL,
  `Tag` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idTag`),
  KEY `fk_Tag_Lingue1` (`idLingua`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Tag Articoli`
--

CREATE TABLE IF NOT EXISTS `Tag Articoli` (
  `idTagArticolo` int(11) NOT NULL AUTO_INCREMENT,
  `idTag` int(11) NOT NULL,
  `idArticoloSpecifico` int(11) NOT NULL,
  PRIMARY KEY (`idTagArticolo`),
  KEY `fk_Articoli_has_Tag_Tag1` (`idTag`),
  KEY `fk_Tag Articoli_Articolo Specifico1` (`idArticoloSpecifico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Taglie`
--

CREATE TABLE IF NOT EXISTS `Taglie` (
  `idColore` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idColore`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Tagli Immagini`
--

CREATE TABLE IF NOT EXISTS `Tagli Immagini` (
  `idTaglio` int(11) NOT NULL AUTO_INCREMENT,
  `Altezza` int(11) DEFAULT NULL,
  `Larghezza` int(11) DEFAULT NULL,
  `Default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idTaglio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Tipi Allegato`
--

CREATE TABLE IF NOT EXISTS `Tipi Allegato` (
  `idTipoAllegato` int(11) NOT NULL,
  `Tipo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idTipoAllegato`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Tipi Documento`
--

CREATE TABLE IF NOT EXISTS `Tipi Documento` (
  `idTipoDocumento` int(11) NOT NULL AUTO_INCREMENT,
  `Direzione` varchar(1) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Descrizione` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idTipoDocumento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Unità di Misura`
--

CREATE TABLE IF NOT EXISTS `Unità di Misura` (
  `idUnità` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(20) DEFAULT NULL,
  `Dicitura` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`idUnità`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Utenti`
--

CREATE TABLE IF NOT EXISTS `Utenti` (
  `idUtente` int(11) NOT NULL AUTO_INCREMENT,
  `idClientePrincipale` int(11) NOT NULL,
  `Nome Utente` varchar(40) DEFAULT NULL,
  `Password` varchar(40) DEFAULT NULL,
  `Attivazione` varchar(40) DEFAULT NULL,
  `Attivo` tinyint(1) DEFAULT NULL,
  `Data Registrazione` datetime DEFAULT NULL,
  `idLingua` int(11) DEFAULT NULL,
  `Note` text,
  PRIMARY KEY (`idUtente`),
  KEY `fk_Utenti_Clienti1` (`idClientePrincipale`),
  KEY `fk_Utenti_Lingue1` (`idLingua`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Valori Attributi`
--

CREATE TABLE IF NOT EXISTS `Valori Attributi` (
  `idValore` int(11) NOT NULL AUTO_INCREMENT,
  `idAttributo` int(11) NOT NULL,
  `Valore` varchar(50) NOT NULL,
  PRIMARY KEY (`idValore`),
  KEY `fk_table1_Attributi1` (`idAttributo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Valori Attributi Articolo Generico`
--

CREATE TABLE IF NOT EXISTS `Valori Attributi Articolo Generico` (
  `idValoreAttributo` int(11) NOT NULL AUTO_INCREMENT,
  `idArticolo` int(11) NOT NULL,
  `idAttributoRichiesto` int(11) NOT NULL,
  `idValore` int(11) DEFAULT NULL,
  `Valore Personalizzato` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idValoreAttributo`),
  KEY `fk_Valori Attributi Articolo Generico_Valori Attributi1` (`idValore`),
  KEY `fk_Valori Attributi Articolo Generico_Articoli Generici1` (`idArticolo`),
  KEY `fk_Valori Attributi Articolo Generico_Attributi di Categoria1` (`idAttributoRichiesto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Valori Attributi Articolo Specifico`
--

CREATE TABLE IF NOT EXISTS `Valori Attributi Articolo Specifico` (
  `idValoreAttributo` int(11) NOT NULL AUTO_INCREMENT,
  `idArticoloSpecifico` int(11) NOT NULL,
  `idAttributoRichiesto` int(11) NOT NULL,
  `idValore` int(11) DEFAULT NULL,
  `Valore Personalizzato` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idValoreAttributo`),
  KEY `fk_Valori Attributi Articolo Specifico_Articoli Specifici1` (`idArticoloSpecifico`),
  KEY `fk_Valori Attributi Articolo Specifico_Attributi Richiesti1` (`idAttributoRichiesto`),
  KEY `fk_Valori Attributi Articolo Specifico_Valori Attributi1` (`idValore`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Valute`
--

CREATE TABLE IF NOT EXISTS `Valute` (
  `idValuta` int(11) NOT NULL,
  `Nome` varchar(50) DEFAULT NULL,
  `Simbolo` varchar(2) DEFAULT NULL,
  `Sigla` varchar(3) DEFAULT NULL,
  `Conversione Euro` decimal(15,8) DEFAULT NULL,
  `Note` text,
  `Default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idValuta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Vendite`
--

CREATE TABLE IF NOT EXISTS `Vendite` (
  `idVendita` int(11) NOT NULL AUTO_INCREMENT,
  `idCliente` int(11) NOT NULL,
  `idMovimento` int(11) DEFAULT NULL,
  `Data` datetime DEFAULT NULL,
  `Descrizione` varchar(200) DEFAULT NULL,
  `Note` text,
  `Online` tinyint(1) DEFAULT NULL,
  `Confermata` tinyint(1) DEFAULT NULL,
  `Evasa` tinyint(1) DEFAULT NULL,
  `SubTotale` decimal(10,2) DEFAULT NULL,
  `Tipo Spedizione` varchar(100) DEFAULT NULL,
  `Costo Spedizione` decimal(7,2) DEFAULT NULL,
  `Totale` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`idVendita`),
  KEY `fk_Vendite_Clienti1` (`idCliente`),
  KEY `fk_Vendite_Movimenti1` (`idMovimento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Voci Primanota`
--

CREATE TABLE IF NOT EXISTS `Voci Primanota` (
  `idVoci Primanota` int(11) NOT NULL AUTO_INCREMENT,
  `idPrimanota` int(11) NOT NULL,
  `Direzione` varchar(1) NOT NULL,
  `Corrispettivo` tinyint(1) NOT NULL,
  `Data` date DEFAULT NULL,
  `Descrizione` varchar(200) DEFAULT NULL,
  `Importo` decimal(15,2) NOT NULL,
  `idModalitàPagamento` int(11) NOT NULL,
  `Numero Pagamento` varchar(50) DEFAULT NULL,
  `Note` varchar(500) DEFAULT NULL,
  `idMovimento` int(11) NOT NULL,
  PRIMARY KEY (`idVoci Primanota`),
  KEY `fk_Voci Primanota_Primanota1` (`idPrimanota`),
  KEY `fk_Voci Primanota_Modalità Pagamento1` (`idModalitàPagamento`),
  KEY `fk_Voci Primanota_Movimenti1` (`idMovimento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura per la vista `Articoli View`
--
DROP TABLE IF EXISTS `Articoli View`;

CREATE OR REPLACE VIEW `Articoli View` AS (select `Articoli Specifici`.`idArticoloSpecifico` AS `idArticoloSpecifico`,`Articoli Specifici`.`idArticolo` AS `idArticolo`,`Articoli Specifici`.`idImmagineTagliata` AS `idImmagineTagliata`,`Articoli Specifici`.`idColore` AS `idColore`,`Articoli Specifici`.`idTaglia` AS `idTaglia`,`Articoli Specifici`.`idValuta` AS `idValuta`,`Articoli Specifici`.`Data` AS `Data`,`Articoli Specifici`.`Prezzo d'acquisto` AS `Prezzo d'acquisto`,`Articoli Specifici`.`Prezzo di vendita` AS `Prezzo di vendita`,`Articoli Specifici`.`Ricarico` AS `Ricarico`,`Articoli Specifici`.`Quantità` AS `Quantità`,`Articoli Specifici`.`Giacenza Minima` AS `Giacenza Minima`,`Articoli Specifici`.`Codice Articolo` AS `Codice Articolo`,`Articoli Specifici`.`Vecchio Codice Articolo` AS `Vecchio Codice Articolo`,`Articoli Specifici`.`Note` AS `Note`,`Articoli Specifici`.`Attivo` AS `Attivo`,`Articoli Specifici`.`Fuori Produzione` AS `Fuori Produzione`,`Articoli Specifici`.`Offerta` AS `Offerta`,`Articoli Generici`.`idCategoria` AS `idCategoria`,`Articoli Generici`.`idMarca` AS `idMarca`,`Marche`.`Nome` AS `Marca`,`Marche`.`Sito` AS `Sito`,`Articoli Generici`.`idUnità` AS `idUnità`,`Nomi Articoli`.`Nome` AS `Nome`,`Descrizioni Articoli`.`Descrizione` AS `Descrizione`,`Immagini Tagliate`.`Path` AS `Path Immagine`,`Immagini Tagliate`.`Thumbnail Base64` AS `Thumbnail Base64`,`Categorie Articoli`.`QuantitàEsatte` AS `QuantitàEsatte` from ((((((`Articoli Specifici` left join `Articoli Generici` on((`Articoli Generici`.`idArticolo` = `Articoli Specifici`.`idArticolo`))) left join `Categorie Articoli` on((`Articoli Generici`.`idCategoria` = `Categorie Articoli`.`idCategoria`))) left join `Marche` on((`Articoli Generici`.`idMarca` = `Marche`.`idMarca`))) left join `Nomi Articoli` on((`Articoli Specifici`.`idArticoloSpecifico` = `Nomi Articoli`.`idArticoloSpecifico`))) left join `Descrizioni Articoli` on((`Articoli Specifici`.`idArticoloSpecifico` = `Descrizioni Articoli`.`idArticoloSpecifico`))) left join `Immagini Tagliate` on((`Immagini Tagliate`.`idImmagineTagliata` = `Articoli Specifici`.`idImmagineTagliata`))) where ((`Nomi Articoli`.`idLingua` = 1) and (`Descrizioni Articoli`.`idLingua` = 1)));

-- --------------------------------------------------------

--
-- Struttura per la vista `Clienti View`
--
DROP TABLE IF EXISTS `Clienti View`;

CREATE OR REPLACE VIEW `Clienti View` AS (select `Clienti`.`idCliente` AS `idCliente`,`Clienti`.`idUtente` AS `idUtente`,`Clienti`.`Nome` AS `Nome`,`Clienti`.`Cognome` AS `Cognome`,`Clienti`.`Intestazione` AS `Intestazione`,`Clienti`.`Partita IVA` AS `Partita IVA`,`Clienti`.`Codice Fiscale` AS `Codice Fiscale`,`Clienti`.`Indirizzo` AS `Indirizzo`,`Clienti`.`CAP` AS `CAP`,`Clienti`.`Città` AS `Città`,`Clienti`.`Provincia` AS `Provincia`,`Clienti`.`Email` AS `Email`,`Clienti`.`Nominativo Riferimento` AS `Nominativo Riferimento`,`Clienti`.`Note` AS `Note`,`Clienti`.`Principale` AS `Principale`,`Utenti`.`Nome Utente` AS `Nome Utente`,`Utenti`.`Password` AS `Password`,`Utenti`.`Attivazione` AS `Attivazione`,`Utenti`.`Attivo` AS `Attivo`,`Utenti`.`Data Registrazione` AS `Data Registrazione`,`Utenti`.`idLingua` AS `idLingua` from (`Clienti` join `Utenti` on((`Clienti`.`idUtente` = `Utenti`.`idUtente`))));

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `Allarmi Giacenze`
--
ALTER TABLE `Allarmi Giacenze`
  ADD CONSTRAINT `fk_Allarmi Giacenze_Articolo Specifico1` FOREIGN KEY (`idArticoloSpecifico`) REFERENCES `Articoli Specifici` (`idArticoloSpecifico`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Allegati`
--
ALTER TABLE `Allegati`
  ADD CONSTRAINT `fk_Allegati_Tipi Allegato1` FOREIGN KEY (`idTipoAllegato`) REFERENCES `Tipi Allegato` (`idTipoAllegato`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Allegati Articoli`
--
ALTER TABLE `Allegati Articoli`
  ADD CONSTRAINT `fk_Allegati Articoli_Allegati1` FOREIGN KEY (`idAllegato`) REFERENCES `Allegati` (`idAllegato`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Allegati Articoli_Articoli Specifici1` FOREIGN KEY (`idArticoloSpecifico`) REFERENCES `Articoli Specifici` (`idArticoloSpecifico`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Amministratori`
--
ALTER TABLE `Amministratori`
  ADD CONSTRAINT `fk_Utenti_Gruppi Utente1` FOREIGN KEY (`idGruppo`) REFERENCES `Gruppi Amministratore` (`idGruppo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Articoli Correlati`
--
ALTER TABLE `Articoli Correlati`
  ADD CONSTRAINT `fk_Articoli Correlati_Articoli Specifici1` FOREIGN KEY (`idArticoloSpecifico1`) REFERENCES `Articoli Specifici` (`idArticoloSpecifico`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Articoli Correlati_Articoli Specifici2` FOREIGN KEY (`idArticoloSpecifico2`) REFERENCES `Articoli Specifici` (`idArticoloSpecifico`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Articoli Generici`
--
ALTER TABLE `Articoli Generici`
  ADD CONSTRAINT `fk_Articoli_Marche1` FOREIGN KEY (`idMarca`) REFERENCES `Marche` (`idMarca`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Articoli_Sottocategorie Articoli1` FOREIGN KEY (`idCategoria`) REFERENCES `Categorie Articoli` (`idCategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Articoli_Unità di Misura1` FOREIGN KEY (`idUnità`) REFERENCES `Unità di Misura` (`idUnità`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Articoli Listino Speciale`
--
ALTER TABLE `Articoli Listino Speciale`
  ADD CONSTRAINT `fk_Articoli Listino Speciale_Articolo Specifico1` FOREIGN KEY (`idArticoloSpecifico`) REFERENCES `Articoli Specifici` (`idArticoloSpecifico`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Listino Speciale_has_Articoli_Listino Speciale1` FOREIGN KEY (`idListino`) REFERENCES `Listino Speciale` (`idListino`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Articoli Specifici`
--
ALTER TABLE `Articoli Specifici`
  ADD CONSTRAINT `Articoli@0020Specifici_ibfk_1` FOREIGN KEY (`idImmagineTagliata`) REFERENCES `Immagini Tagliate` (`idImmagineTagliata`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Articoli Specifici_Colori1` FOREIGN KEY (`idColore`) REFERENCES `Colori` (`idColore`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Articoli Specifici_Taglie1` FOREIGN KEY (`idTaglia`) REFERENCES `Taglie` (`idColore`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Articoli Specifici_Valute1` FOREIGN KEY (`idValuta`) REFERENCES `Valute` (`idValuta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Articoli_has_Colori_Articoli` FOREIGN KEY (`idArticolo`) REFERENCES `Articoli Generici` (`idArticolo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Attributi Richiesti`
--
ALTER TABLE `Attributi Richiesti`
  ADD CONSTRAINT `fk_Attributi di Categoria_Attributi1` FOREIGN KEY (`idAttributo`) REFERENCES `Attributi` (`idAttributo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Attributi di Categoria_Categorie Articoli1` FOREIGN KEY (`idCategoria`) REFERENCES `Categorie Articoli` (`idCategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Bolle`
--
ALTER TABLE `Bolle`
  ADD CONSTRAINT `fk_Bolle_Movimenti1` FOREIGN KEY (`idMovimento`) REFERENCES `Movimenti` (`idMovimento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Bolle_Ordini1` FOREIGN KEY (`idOrdine`) REFERENCES `Ordini` (`idOrdine`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Categorie Articoli`
--
ALTER TABLE `Categorie Articoli`
  ADD CONSTRAINT `fk_Categorie Articoli_Categorie Articoli1` FOREIGN KEY (`idCategoriaPadre`) REFERENCES `Categorie Articoli` (`idCategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Clienti`
--
ALTER TABLE `Clienti`
  ADD CONSTRAINT `Clienti_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `Utenti` (`idUtente`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Clienti Listino Speciale`
--
ALTER TABLE `Clienti Listino Speciale`
  ADD CONSTRAINT `fk_Clienti Listino Speciale_Clienti1` FOREIGN KEY (`idCliente`) REFERENCES `Clienti` (`idCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Clienti Listino Speciale_Listino Speciale1` FOREIGN KEY (`idListino`) REFERENCES `Listino Speciale` (`idListino`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Codici a Barre`
--
ALTER TABLE `Codici a Barre`
  ADD CONSTRAINT `fk_Codice a Barre_Articolo Specifico1` FOREIGN KEY (`idArticoloSpecifico`) REFERENCES `Articoli Specifici` (`idArticoloSpecifico`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Codici a Barre_Articoli Generici1` FOREIGN KEY (`idArticolo`) REFERENCES `Articoli Generici` (`idArticolo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Comunicazioni`
--
ALTER TABLE `Comunicazioni`
  ADD CONSTRAINT `fk_Comunicazioni_Risposte1` FOREIGN KEY (`idRisposta`) REFERENCES `Risposte` (`idRisposta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Comunicazioni_Utenti1` FOREIGN KEY (`idUtente`) REFERENCES `Utenti` (`idUtente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Descrizioni Articoli`
--
ALTER TABLE `Descrizioni Articoli`
  ADD CONSTRAINT `fk_table1_Articoli Generici1` FOREIGN KEY (`idArticolo`) REFERENCES `Articoli Generici` (`idArticolo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_table1_Articoli Specifici1` FOREIGN KEY (`idArticoloSpecifico`) REFERENCES `Articoli Specifici` (`idArticoloSpecifico`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_table1_Lingue1` FOREIGN KEY (`idLingua`) REFERENCES `Lingue` (`idLingua`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Descrizioni Categorie`
--
ALTER TABLE `Descrizioni Categorie`
  ADD CONSTRAINT `fk_Descrizioni Categorie_Categorie Articoli1` FOREIGN KEY (`idCategoria`) REFERENCES `Categorie Articoli` (`idCategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Descrizioni Categorie_Lingue1` FOREIGN KEY (`idLingua`) REFERENCES `Lingue` (`idLingua`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Documenti`
--
ALTER TABLE `Documenti`
  ADD CONSTRAINT `fk_Documenti_Modalità Pagamento1` FOREIGN KEY (`idModalitàPagamento`) REFERENCES `Modalità Pagamento` (`idModalità Pagamento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Documenti_Tipi Documento1` FOREIGN KEY (`idTipoDocumento`) REFERENCES `Tipi Documento` (`idTipoDocumento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Fornitori Articolo`
--
ALTER TABLE `Fornitori Articolo`
  ADD CONSTRAINT `fk_Articoli_has_Fornitori_Articoli1` FOREIGN KEY (`idArticolo`) REFERENCES `Articoli Generici` (`idArticolo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Articoli_has_Fornitori_Fornitori1` FOREIGN KEY (`idFornitore`) REFERENCES `Fornitori` (`idFornitore`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Immagini Tagliate`
--
ALTER TABLE `Immagini Tagliate`
  ADD CONSTRAINT `fk_Immagini Tagliate_Immagini1` FOREIGN KEY (`idImmagine`) REFERENCES `Immagini` (`idImmagine`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Immagini Tagliate_Tagli Immagini1` FOREIGN KEY (`idTaglio`) REFERENCES `Tagli Immagini` (`idTaglio`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Linee Bolle`
--
ALTER TABLE `Linee Bolle`
  ADD CONSTRAINT `fk_Linee Bolle_Bolle1` FOREIGN KEY (`idBolla`) REFERENCES `Bolle` (`idBolla`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Linee Bolle_Linee Ordini1` FOREIGN KEY (`idLineaOrdine`) REFERENCES `Linee Ordini` (`idLinea`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Linee Bolle_Movimenti Articoli1` FOREIGN KEY (`idMovimentoArticolo`) REFERENCES `Movimenti Articoli` (`idMovimentoArticolo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Linee Ordini`
--
ALTER TABLE `Linee Ordini`
  ADD CONSTRAINT `fk_Ordini_has_Articolo Specifico_Ordini1` FOREIGN KEY (`idOrdine`) REFERENCES `Ordini` (`idOrdine`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Linee Vendita`
--
ALTER TABLE `Linee Vendita`
  ADD CONSTRAINT `fk_Linee Vendita_Articoli Movimento1` FOREIGN KEY (`idMovimentoArticolo`) REFERENCES `Movimenti Articoli` (`idMovimentoArticolo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Linee Vendita_Vendite1` FOREIGN KEY (`idVendita`) REFERENCES `Vendite` (`idVendita`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Movimenti`
--
ALTER TABLE `Movimenti`
  ADD CONSTRAINT `fk_Movimenti_Causali1` FOREIGN KEY (`idCausale`) REFERENCES `Causali` (`idCausale`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Movimenti_Documenti1` FOREIGN KEY (`idDocumento`) REFERENCES `Documenti` (`idDocumento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Movimenti Articoli`
--
ALTER TABLE `Movimenti Articoli`
  ADD CONSTRAINT `fk_Movimenti_has_Articolo Specifico_Articolo Specifico2` FOREIGN KEY (`idArticoloSpecifico`) REFERENCES `Articoli Specifici` (`idArticoloSpecifico`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Movimenti_has_Articolo Specifico_Movimenti2` FOREIGN KEY (`idMovimento`) REFERENCES `Movimenti` (`idMovimento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Nomi Articoli`
--
ALTER TABLE `Nomi Articoli`
  ADD CONSTRAINT `fk_Nomi Articoli_Articoli Generici1` FOREIGN KEY (`idArticolo`) REFERENCES `Articoli Generici` (`idArticolo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Nomi Articoli_Articoli Specifici1` FOREIGN KEY (`idArticoloSpecifico`) REFERENCES `Articoli Specifici` (`idArticoloSpecifico`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Nomi Articoli_Lingue1` FOREIGN KEY (`idLingua`) REFERENCES `Lingue` (`idLingua`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Nomi Categorie`
--
ALTER TABLE `Nomi Categorie`
  ADD CONSTRAINT `fk_table1_Categorie Articoli1` FOREIGN KEY (`idCategoria`) REFERENCES `Categorie Articoli` (`idCategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_table1_Lingue2` FOREIGN KEY (`idLingua`) REFERENCES `Lingue` (`idLingua`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Ordini`
--
ALTER TABLE `Ordini`
  ADD CONSTRAINT `fk_Ordini_Fornitori1` FOREIGN KEY (`idFornitore`) REFERENCES `Fornitori` (`idFornitore`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Risposte`
--
ALTER TABLE `Risposte`
  ADD CONSTRAINT `fk_Risposte_Amministratori1` FOREIGN KEY (`idAmministratore`) REFERENCES `Amministratori` (`idAmministratore`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Risposte_Comunicazioni1` FOREIGN KEY (`idComunicazione`) REFERENCES `Comunicazioni` (`idComunicazione`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Storico Prezzi Articoli`
--
ALTER TABLE `Storico Prezzi Articoli`
  ADD CONSTRAINT `fk_Storico Prezzi Articolo_Articolo Specifico1` FOREIGN KEY (`idArticoloSpecifico`) REFERENCES `Articoli Specifici` (`idArticoloSpecifico`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Tag`
--
ALTER TABLE `Tag`
  ADD CONSTRAINT `fk_Tag_Lingue1` FOREIGN KEY (`idLingua`) REFERENCES `Lingue` (`idLingua`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Tag Articoli`
--
ALTER TABLE `Tag Articoli`
  ADD CONSTRAINT `fk_Articoli_has_Tag_Tag1` FOREIGN KEY (`idTag`) REFERENCES `Tag` (`idTag`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Tag Articoli_Articolo Specifico1` FOREIGN KEY (`idArticoloSpecifico`) REFERENCES `Articoli Specifici` (`idArticoloSpecifico`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Utenti`
--
ALTER TABLE `Utenti`
  ADD CONSTRAINT `fk_Utenti_Clienti1` FOREIGN KEY (`idClientePrincipale`) REFERENCES `Clienti` (`idCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Utenti_Lingue1` FOREIGN KEY (`idLingua`) REFERENCES `Lingue` (`idLingua`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Valori Attributi`
--
ALTER TABLE `Valori Attributi`
  ADD CONSTRAINT `fk_table1_Attributi1` FOREIGN KEY (`idAttributo`) REFERENCES `Attributi` (`idAttributo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Valori Attributi Articolo Generico`
--
ALTER TABLE `Valori Attributi Articolo Generico`
  ADD CONSTRAINT `fk_Valori Attributi Articolo Generico_Articoli Generici1` FOREIGN KEY (`idArticolo`) REFERENCES `Articoli Generici` (`idArticolo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Valori Attributi Articolo Generico_Attributi di Categoria1` FOREIGN KEY (`idAttributoRichiesto`) REFERENCES `Attributi Richiesti` (`idAttributoRichiesto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Valori Attributi Articolo Generico_Valori Attributi1` FOREIGN KEY (`idValore`) REFERENCES `Valori Attributi` (`idValore`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Valori Attributi Articolo Specifico`
--
ALTER TABLE `Valori Attributi Articolo Specifico`
  ADD CONSTRAINT `fk_Valori Attributi Articolo Specifico_Articoli Specifici1` FOREIGN KEY (`idArticoloSpecifico`) REFERENCES `Articoli Specifici` (`idArticoloSpecifico`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Valori Attributi Articolo Specifico_Attributi Richiesti1` FOREIGN KEY (`idAttributoRichiesto`) REFERENCES `Attributi Richiesti` (`idAttributoRichiesto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Valori Attributi Articolo Specifico_Valori Attributi1` FOREIGN KEY (`idValore`) REFERENCES `Valori Attributi` (`idValore`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Vendite`
--
ALTER TABLE `Vendite`
  ADD CONSTRAINT `fk_Vendite_Clienti1` FOREIGN KEY (`idCliente`) REFERENCES `Clienti` (`idCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Vendite_Movimenti1` FOREIGN KEY (`idMovimento`) REFERENCES `Movimenti` (`idMovimento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Voci Primanota`
--
ALTER TABLE `Voci Primanota`
  ADD CONSTRAINT `fk_Voci Primanota_Modalità Pagamento1` FOREIGN KEY (`idModalitàPagamento`) REFERENCES `Modalità Pagamento` (`idModalità Pagamento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Voci Primanota_Movimenti1` FOREIGN KEY (`idMovimento`) REFERENCES `Movimenti` (`idMovimento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Voci Primanota_Primanota1` FOREIGN KEY (`idPrimanota`) REFERENCES `Primanota` (`idPrimanota`) ON DELETE NO ACTION ON UPDATE NO ACTION;
