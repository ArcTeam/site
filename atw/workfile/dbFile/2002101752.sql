BEGIN;
DROP TABLE progetto CASCADE;
DROP TABLE attivita CASCADE;
DROP TABLE ore;
DROP TABLE diario;
CREATE TABLE comune(
  id serial PRIMARY KEY,
  comune CHARACTER VARYING UNIQUE NOT NULL
);
create table scavo(
  id serial primary key,
  nome_lungo CHARACTER VARYING UNIQUE NOT NULL,
  sigla CHARACTER VARYING UNIQUE NOT NULL,
  inizio date,
  fine date,
  ore_stimate integer,
  descrizione text,
  comune integer references comune(id) on delete set null,
  localizzazione CHARACTER VARYING,
  lon NUMERIC(6,4),
  lat NUMERIC(6,4)
);
CREATE TABLE ore(
  id serial PRIMARY KEY,
  scavo INTEGER NOT NULL REFERENCES scavo(id),
  data DATE not NULL,
  ore numeric(3,1) not NULL,
  operatore INTEGER NOT NULL REFERENCES users(id)
);
ALTER TABLE ore ADD CONSTRAINT ore_unique UNIQUE(scavo,data,operatore);
CREATE TABLE diario(
  id serial PRIMARY KEY,
  scavo INTEGER NOT NULL REFERENCES scavo(id),
  data DATE not NULL,
  operatore INTEGER NOT NULL REFERENCES users(id),
  descrizione text not NULL
);
ALTER TABLE diario ADD CONSTRAINT diario_unique UNIQUE(scavo,data,operatore);
COMMIT;
