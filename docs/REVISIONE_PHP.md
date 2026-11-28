# Revisione PHP — configurazione e collaudo

## Configurazione MySQL
`php/db.php` supporta queste variabili d'ambiente del processo PHP/web server:
- `BUDGET_DB_HOST`
- `BUDGET_DB_USER`
- `BUDGET_DB_PASSWORD`
- `BUDGET_DB_NAME`

Per compatibilità, in assenza di variabili il codice usa ancora i valori legacy nel file. **Le credenziali storiche non sono state rimosse dalla cronologia Git**. Per completare la migrazione: configurare le quattro variabili nel server, verificare la connessione, eliminare i valori hardcoded e ruotare le credenziali esposte.

## Requisiti
- PHP 8+ (l'importazione CSV utilizza `str_contains`).
- MySQL/MariaDB con tabelle InnoDB per garantire il rollback.
- Estensione mysqli con mysqlnd per gli endpoint che usano `mysqli_stmt_get_result`.

## Verifiche consigliate (su copia del database)
1. Homepage: importi, grafici, selezione anno.
2. Inserimento e modifica transazione: data, mese, importi e categoria corretti.
3. Rinomina categoria: aggiornamento della sola categoria interessata.
4. Pending: conferma e cancellazione multipla; verificare assenza di duplicazioni.
5. CSV: intestazione, prima riga senza intestazione, importi con virgola, CSV malformato (rollback).
6. Ricorrenze: conferma annullata, eliminazione di una serie, mantenimento delle operazioni passate e rimozione di quelle future.
7. Tabelle Gestione: layout mobile, icone e modali.

## Controlli statici
Eseguire dal repository:
```sh
find php -name '*.php' -print0 | xargs -0 -n1 php -l
```

Queste modifiche non sono state eseguite contro il database di produzione.
