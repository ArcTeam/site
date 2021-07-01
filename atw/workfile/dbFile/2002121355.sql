BEGIN;
CREATE TABLE list.tipo_us(id serial PRIMARY KEY, tipo CHARACTER VARYING NOT NULL UNIQUE);
INSERT INTO list.tipo_us(tipo) VALUES
  ('unità statigrafica'),
  ('unità stratigrafica negativa'),
  ('unità scheletrica'),
  ('unità stratigrafica muraria'),
  ('unità geologica');

CREATE TABLE list.natura_us(id serial PRIMARY KEY, natura CHARACTER VARYING NOT NULL UNIQUE);
INSERT INTO list.natura_us(natura) VALUES ('naturale'), ('artificiale');

CREATE TABLE list.criterio_us(id serial PRIMARY KEY, criterio CHARACTER VARYING NOT NULL UNIQUE);
INSERT INTO list.criterio_us(criterio) values
  ('colore'),
  ('composizione'),
  ('consistenza'),
  ('morfologia');

CREATE TABLE list.formazione_us(id serial PRIMARY KEY, cat integer NOT NULL, formazione CHARACTER VARYING NOT NULL UNIQUE);
INSERT INTO list.formazione_us(cat, formazione) values
  (1,'apporto'),
  (1,'asporto'),
  (2,'diacronico'),
  (2,'sincronico'),
  (3,'volontario'),
  (3,'involontario');

CREATE TABLE list.affidabilita_us(id serial PRIMARY KEY, affidabilita CHARACTER VARYING NOT NULL UNIQUE);
INSERT INTO list.affidabilita_us(affidabilita) VALUES ('alta'), ('media'), ('bassa');

CREATE TABLE list.consistenza_us(id serial PRIMARY KEY, consistenza CHARACTER VARYING NOT NULL UNIQUE);
INSERT INTO list.consistenza_us(consistenza) VALUES
  ('sciolto'),
  ('friabile'),
  ('poco compatto'),
  ('compatto'),
  ('plastico'),
  ('duro'),
  ('poco tenace'),
  ('tenace');


CREATE TABLE list.conservazione_us(id serial PRIMARY KEY, conservazione CHARACTER VARYING NOT NULL UNIQUE);
insert into list.conservazione_us(conservazione) values
  ('pessimo'),
  ('mediocre'),
  ('buono'),
  ('discreto'),
  ('ottimo');

CREATE TABLE list.rapporti_us(
  id serial PRIMARY KEY,
  rapporto CHARACTER VARYING NOT NULL UNIQUE,
  us_neg BOOLEAN NOT NULL,
  us_pos BOOLEAN NOT NULL,
  us_muro BOOLEAN NOT NULL,
  us_tafo BOOLEAN NOT NULL,
  us_geo BOOLEAN NOT NULL
);
insert into list.rapporti_us(rapporto, us_neg, us_pos, us_muro, us_tafo, us_geo) values
  ('coeva', 't', 't', 't', 't', 't'),
  ('uguale a', 't', 't', 't', 't', 't'),
  ('si lega a', 'f', 'f', 't', 'f', 'f'),
  ('gli si appoggia', 'f', 't', 't', 't', 't'),
  ('si appoggia a', 'f', 't', 't', 't', 't'),
  ('coperto da', 'f', 't', 't', 't', 't'),
  ('copre', 'f', 't', 't', 't', 't'),
  ('tagliato da', 'f', 't', 't', 't', 't'),
  ('taglia', 't', 'f', 'f', 'f', 'f'),
  ('riempito da', 't', 't', 'f', 'f', 't'),
  ('riempie', 'f', 't', 't', 't', 't');

CREATE TABLE list.materiale(id serial PRIMARY KEY, materiale CHARACTER VARYING NOT NULL UNIQUE);
INSERT INTO list.materiale(materiale) values ('ceramica'), ('ferro'), ('bronzo'),('argento'), ('oro'), ('calcare'), ('granito'), ('plastica'), ('legno'), ('cuoio'), ('materiale organico'), ('lega metallica');

CREATE TABLE list.tipologia(id serial PRIMARY KEY, tipologia CHARACTER VARYING NOT NULL UNIQUE);
INSERT INTO list.tipologia(tipologia) values ('orlo'), ('orlo decorato'), ('parete'), ('parete decorata'), ('fondo'), ('ansa'), ('presa'), ('anello'), ('lamina'), ('decorazione');

-- us
CREATE TABLE us(
  id serial PRIMARY KEY,
  scavo INTEGER NOT NULL REFERENCES scavo(id) ON DELETE CASCADE,
  tipo SMALLINT NOT NULL REFERENCES list.tipo_us(id),
  us SMALLINT NOT NULL,
  definizione text NOT NULL,
  compilazione DATE NOT NULL,
  compilatore integer REFERENCES users(id) NOT NULL,
  direttore integer REFERENCES rubrica(id) NOT NULL,
  chiusa BOOLEAN DEFAULT FALSE,
  UNIQUE(scavo,us)
);

CREATE TABLE us_info(
  us SMALLINT REFERENCES us(id) PRIMARY KEY,
  descrizione text NOT NULL,
  natura SMALLINT NOT NULL REFERENCES list.natura_us(id),
  criterio CHARACTER VARYING NOT NULL,
  formazione CHARACTER VARYING NOT NULL,
  affidabilita SMALLINT NOT NULL REFERENCES list.affidabilita_us(id),
  consistenza SMALLINT NOT NULL REFERENCES list.consistenza_us(id),
  colore character varying NOT NULL,
  conservazione SMALLINT NOT NULL REFERENCES list.conservazione_us(id),
  interpretazione text NOT NULL,
  foto_digitali BOOLEAN DEFAULT TRUE,
  osservazioni text,
  elem_datanti text,
  periodo character varying,
  dati_quantit text,
  flottazione BOOLEAN DEFAULT FALSE,
  setacciatura BOOLEAN DEFAULT FALSE
);

-- area
create TABLE us_area(
  us SMALLINT REFERENCES us(id) PRIMARY KEY,
  area CHARACTER VARYING,
  saggio CHARACTER VARYING,
  settore CHARACTER VARYING,
  ambiente CHARACTER VARYING,
  quadrato CHARACTER VARYING
);

--rapporti
create TABLE us_rapporti(
  us SMALLINT REFERENCES us(id) PRIMARY KEY,
  rapporto SMALLINT NOT NULL REFERENCES list.rapporti_us,
  us2 CHARACTER VARYING NOT NULL,
  UNIQUE(us,rapporto,us2)
);

--componenti
create TABLE componenti(
  us SMALLINT REFERENCES us(id) PRIMARY KEY,
  geologici CHARACTER VARYING,
  organici CHARACTER VARYING,
  artificiali CHARACTER VARYING
);

--fotopiani
CREATE TABLE fotopiano(
  id serial PRIMARY KEY,
  scavo INTEGER NOT NULL REFERENCES scavo(id) ON DELETE CASCADE,
  num_fotopiano integer not null,
  us CHARACTER VARYING NOT NULL,
  note CHARACTER VARYING,
  data date not null,
  autore SMALLINT NOT NULL REFERENCES users(id),
  UNIQUE(scavo, num_fotopiano)
);

--campioni
CREATE TABLE campione(
  id serial PRIMARY KEY,
  data date not null,
  autore SMALLINT NOT NULL REFERENCES users(id),
  us CHARACTER VARYING NOT NULL,
  note CHARACTER VARYING,
  UNIQUE(data,us)
);

-- reperti
CREATE TABLE reperto(
  id serial PRIMARY KEY,
  us SMALLINT REFERENCES us(id),
  materiale INTEGER NOT NULL REFERENCES list.materiale(id),
  tipologia INTEGER NOT NULL REFERENCES list.tipologia(id),
  note CHARACTER VARYING,
  data date not null default now(),
  usr SMALLINT not null REFERENCES users(id)
);

COMMIT;
