BEGIN;
alter table progetto DROP COLUMN chiuso;
create TABLE list.soggetto_rubrica(
  id serial PRIMARY KEY,
  classe CHARACTER VARYING UNIQUE NOT NULL
);
INSERT INTO list.soggetto_rubrica(classe) VALUES
  ('funzionario'),
  ('ente pubblico'),
  ('ente di ricerca'),
  ('università'),
  ('ditta'),
  ('azienda'),
  ('cooperativa'),
  ('ente religioso'),
  ('libero professionista'),
  ('soprintendenza'),
  ('altro');

CREATE TABLE rubrica(
  id serial PRIMARY KEY,
  classe INTEGER NOT NULL REFERENCES list.soggetto_rubrica(id),
  nome CHARACTER VARYING NOT NULL,
  email CHARACTER VARYING NOT NULL UNIQUE,
  indirizzo CHARACTER VARYING,
  cod_fisc CHARACTER VARYING,
  piva CHARACTER VARYING,
  telefono CHARACTER VARYING,
  mobile CHARACTER VARYING,
  web CHARACTER VARYING,
  note CHARACTER VARYING
);

CREATE TABLE list.corrispettivo(id serial PRIMARY KEY, tipo CHARACTER VARYING NOT NULL UNIQUE);
INSERT INTO list.corrispettivo(tipo) VALUES('orario'),('corpo');

CREATE TABLE incarico(
  id serial PRIMARY KEY,
  committente integer not NULL REFERENCES rubrica(id),
  oggetto CHARACTER VARYING not null,
  cig CHARACTER VARYING,
  cup CHARACTER VARYING,
  corrispettivo integer not NULL REFERENCES list.corrispettivo(id),
  prezzo NUMERIC(10,2) NOT NULL,
  inizio date NOT NULL,
  fine DATE NOT NULL,
  ore_stimate integer not null,
  chiuso boolean DEFAULT 't',
  pdf CHARACTER VARYING
);

create TABLE attivita(
  id serial PRIMARY KEY,
  nome CHARACTER VARYING UNIQUE NOT NULL,
  progetto INTEGER NOT NULL REFERENCES progetto(id) ON DELETE CASCADE,
  tipo INTEGER NOT NULL REFERENCES list.tipo_attivita(id),
  descrizione CHARACTER VARYING NOT NULL,
  ore_stimate integer NOT NULL,
  inizio date NOT NULL DEFAULT now(),
  fine date
);

CREATE TABLE attivita_incarico(
  attivita integer not null REFERENCES attivita(id) on delete CASCADE,
  incarico integer not null REFERENCES incarico(id) on delete CASCADE
);
alter TABLE attivita_incarico ADD CONSTRAINT attivita_incarico_unique UNIQUE (attivita, incarico);

CREATE TABLE ore(
  id serial PRIMARY KEY,
  attivita INTEGER NOT NULL REFERENCES attivita(id),
  data DATE not NULL,
  ore numeric(3,1) not NULL,
  operatore INTEGER NOT NULL REFERENCES users(id)
);
CREATE TABLE diario(
  id serial PRIMARY KEY,
  attivita INTEGER NOT NULL REFERENCES attivita(id),
  data DATE not NULL,
  operatore INTEGER NOT NULL REFERENCES users(id),
  descrizione text not NULL
);

CREATE TABLE comune(
  id serial PRIMARY KEY,
  comune CHARACTER VARYING UNIQUE NOT NULL
);

create TABLE localizzazione(
  attivita integer not null REFERENCES attivita(id) on delete CASCADE,
  comune integer not null REFERENCES comune(id) on delete CASCADE,
  localizzazione CHARACTER VARYING,
  coo CHARACTER VARYING
);
comment on column localizzazione.localizzazione is 'inserire la via o la località';
comment on column localizzazione.coo is 'inserire le coordinate in wgs84 (epsg:4326), nel formato LON,LAT';

COMMIT;
