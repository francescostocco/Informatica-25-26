# Sito di recensioni per strutture turistiche  

**Sviluppatore:** Francesco Stocco

## Descrizione del progetto

Sito web di recensioni per strutture turistiche (hotel, B&B, case vacanza, agriturismi) nel quale sono presenti diverse strutture organizzate per località. Tutti gli utenti possono visualizzare le strutture disponibili, leggere le informazioni principali e inserire recensioni testuali e valutazioni tramite stelle.
Esiste una sezione in cui, tramite login, è possibile inserire nuove strutture turistiche. Le strutture e le recensioni sono invece visibili a tutti gli utenti, anche senza login.
Solo agli amministratori del sito saranno disponibili pagine di gestione che permettono di eliminare le strutture e di consultare statistiche, come le strutture più visitate o quelle che hanno ottenuto la valutazione media più alta. Sarà inoltre possibile applicare filtri ai risultati, ad esempio per località, tipologia di struttura o numero di stelle. Durante lo sviluppo alcune funzionalità potranno essere modificate o ampliate.

---

## Database

- **Tabella UTENTI**  
(<u>IdUtente</u>, Nome, Cognome, DataNascita, Email, PasswordUtente)

- **Tabella AMMINISTRATORI**  
(<u>IdUtente</u>FK, CodiceAccesso, DataNomina)

- **Tabella PROPRIETARI**   
(<u>IdUtente</u>FK, NomeAttività, SedeLegale, PartitaIVA, Telefono, IdProprietario)

- **Tabella TIPOLOCALITA’**  
(<u>IdTipoLocalità</u>, TipoLocalità)

- **Tabella STRUTTURE**  
(<u>CodStruttura</u>, NomeStruttura, Descrizione, Indirizzo, Città, IdTipoLocalitàFK, IdProprietarioFK)

- **Tabella FOTOSTRUTTURE**  
(<u>IdFoto</u>, UrlFoto, CoSdStrutturaFK)

- **Tabella ALBERGHI**  
(<u>CodStruttura</u>FK, Catena, NumeroCamere, NumeroStelle)

- **Tabella B&B**  
(<u>CodStruttura</u>FK, Categoria, NumeroCamere, ColazioneInclusa)

- **Tabella CASEVACANZE**  
(<u>CodStruttura</u>FK, NumPostiLetto, Superficie, NumBagni, AnimaliAmmessi)

- **Tabella RECENSIONI**  
(<u>IdRecensione</u>, IdUtenteFK, NumStelle, Titolo, Commento, CodStrutturaFK)