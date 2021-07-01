BEGIN;
DROP TABLE campione;
DROP TABLE localizzazione;

CREATE TABLE inventario(
  id serial PRIMARY KEY,
  scavo INTEGER REFERENCES scavo(id),
  numero INTEGER,
  UNIQUE(scavo, numero)
);

CREATE TABLE campione(
  inventario INTEGER PRIMARY KEY REFERENCES inventario(id),
  tipo SMALLINT NOT NULL REFERENCES list.tipo_campione(id),
  data DATE NOT NULL DEFAULT now(),
  autore SMALLINT NOT NULL REFERENCES users(id),
  us SMALLINT NOT NULL REFERENCES us(id),
  descrizione text NOT NULL
);
CREATE TABLE sacchetto(
  inventario INTEGER PRIMARY KEY REFERENCES inventario(id),
  data DATE NOT NULL DEFAULT now(),
  autore SMALLINT NOT NULL REFERENCES users(id),
  scavo SMALLINT REFERENCES scavo(id) ON DELETE CASCADE,
  us SMALLINT REFERENCES us(id),
  descrizione text NOT NULL
);
comment on table sacchetto IS 'uno dei campi a scelta tra scavo e us deve essere obbligatoriamente compilato, non importa che lo siano entrambi';

CREATE TABLE localizzazione(
  id serial PRIMARY KEY,
  scavo INTEGER UNIQUE NOT NULL REFERENCES scavo(id) ON DELETE CASCADE,
  comune INTEGER NOT NULL REFERENCES osm(osm_id) ON DELETE CASCADE,
  localizzazione CHARACTER VARYING,
  lon NUMERIC(6,4),
  lat NUMERIC(6,4),
  geom GEOMETRY(point, 3857)
);
CREATE INDEX localizzazione_gix ON localizzazione USING GIST (geom);

CREATE TABLE scavo_config(
  scavo INTEGER PRIMARY KEY REFERENCES scavo(id) on DELETE CASCADE,
  us SMALLINT NOT NULL default 1,
  inventario integer NOT NULL default 1,
  reperto SMALLINT NOT NULL default 1,
  campione SMALLINT NOT NULL default 1,
  sacchetto SMALLINT NOT NULL default 1,
  fotopiano SMALLINT NOT NULL default 1,
  tomba SMALLINT NOT NULL default 1
);

INSERT INTO localizzazione(scavo, comune, localizzazione, lon, lat, geom) select id, comune, localizzazione, lon, lat, st_setSRID(st_makePoint(lon, lat), 3857) from scavo order by id, comune;
INSERT INTO scavo_config(scavo) SELECT id from scavo order by id asc;

ALTER TABLE scavo DROP COLUMN comune,DROP COLUMN localizzazione,DROP COLUMN lon,DROP COLUMN lat;

COMMIT;
