--
-- Dump dei dati per la tabella `Lingue`
--

INSERT INTO `Lingue` (`idLingua`, `Nome`, `Sigla`, `Attiva`, `Dominio`, `Langtag`) VALUES
(1, 'Italiano', 'it', 1, '.it', 'it_IT');

--
-- Dump dei dati per la tabella `Gruppi Amministratore`
--

INSERT INTO `Gruppi Amministratore` (`idGruppo`, `Nome Gruppo`, `Permessi`, `Note`) VALUES
(1, 'SuperAdmin', NULL, NULL);

--
-- Dump dei dati per la tabella `Amministratori`
--

INSERT INTO `Amministratori` (`idAmministratore`, `idGruppo`, `Nome`, `Cognome`, `Nome Utente`, `Password`, `Note`, `Attivo`) VALUES
(1, 1, 'Super', 'Admin', 'superadmin', '889a3a791b3875cfae413574b53da4bb8a90d53e', NULL, 1);

--
-- Dump dei dati per la tabella `Causali`
--

INSERT INTO `Causali` (`idCausale`, `Nome`, `Descrizione`, `Direzione`) VALUES
(1, 'Vendita Online', 'Vendita tramite la piattaforma di e-commerce AddiXi', 'u');

--
-- Dump dei dati per la tabella `Utenti`
--

INSERT INTO `Utenti` (`idUtente`, `idClientePrincipale`, `Nome Utente`, `Password`, `Attivazione`, `Attivo`, `Data Registrazione`, `idLingua`, `Note`) VALUES
(1, 1, 'test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', '', 1, '2010-02-03 19:00:07', 1, NULL);

--
-- Associazione Cliente - Utente
--

UPDATE `Clienti` SET `idUtente` = 1 WHERE `idCliente` = 1;

--
-- Dump dei dati per la tabella `Impostazioni`
--

INSERT INTO `Impostazioni` (`idImpostazione`, `Nome`, `Valore`) VALUES
(1, 'Titolo Base', 'AddiXi'),
(2, 'UltimoPingSitemap', NULL),
(3, 'Aggiornato', '1'),
(4, 'AddThis', '1');

--
-- Dump dei dati per la tabella `Motori di ricerca`
--

INSERT INTO `Motori di ricerca` (`idMotore`, `Nome Motore`, `URL Ping Sitemap`, `Ultimo Ping Sitemap`) VALUES
(1, 'Google', 'http://www.google.com/webmasters/tools/ping?sitemap=', NULL),
(2, 'Yahoo!', '', NULL),
(3, 'Ask.com', 'http://submissions.ask.com/ping?sitemap=', NULL),
(4, 'Bing', 'http://www.bing.com/webmaster/ping.aspx?siteMap=', NULL);
