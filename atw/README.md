# DESCRIZIONE MODELLO

## Progetto
La tabella equivale ad una sorta di "cartella" in cui sono convogliate tutte le informazioni relative al progetto come, ad esempio:
- le ore di lavoro
- il diario giornaliero
- le attività legate al progetto
- gli incarichi
- le fatture emesse
- nel caso di interventi archeologici tutta la documentazione:
  - us
  - fotopiani
  - lista reperti
  - ...

Ad ogni progetto è associata una categoria:
- scavo
- documentazione
- informatica
- didattica
- laboratorio
- altro

### Campi
**campo** | **descrizione**
--------- | -------------
**nome** | campo univoco obbligatorio, identifica il progetto.  
**anno** | campo obbligatorio  
**created_at** | valore automatico, corrisponde alla data di creazione del record  
**updated_at** | valore automatico, tiene traccia dell'ultima modifica effettuata  
**descrizione** | campo obbligatorio, breve descrizione del progetto. Il campo può essere riutilizzato per il sito "pubblico"  
**tipo** | campo lista obbligatorio, il campo serve a specificare il tipo di progetto  
**data_fine** | data di chiusura del progetto, il sistema controlla tutte le attività legate al progetto: il progetto non può essere chiuso finché le attività collegate non sono terminate

## Attività
La tabella rappresenta il cuore del database
