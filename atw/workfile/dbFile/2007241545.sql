BEGIN;
drop table inventario cascade;
drop table reperto;
drop table campione;
drop table sacchetto;
alter TABLE list.corrispettivo rename tipo to value;
alter TABLE list.criterio_us rename criterio to value;
alter TABLE list.materiale rename materiale to value;
alter TABLE list.soggetto_rubrica rename classe to value;
alter TABLE list.tipo_attivita rename tipo to value;
alter TABLE list.tipo_lavoro rename tipo to value;
alter TABLE list.tipologia rename tipologia to value;

CREATE TABLE list.tipo_sacchetto(id serial primary key, value character VARYING not null unique);
insert into list.tipo_sacchetto(value) values ('cp'),('rr'),('sg');

create TABLE sacchetto(
  id serial primary key,
  tipologia integer not null REFERENCES list.tipo_sacchetto(id),
  scavo integer not null REFERENCES scavo(id) on delete cascade,
  inventario INTEGER not null,
  numero INTEGER NOT NULL,
  data date not null default now(),
  compilatore INTEGER not null default 0 REFERENCES users(id) on delete set default,
  us INTEGER REFERENCES us(id) on DELETE CASCADE,
  descrizione character varying,
  unique(scavo, tipologia, inventario, numero)
);
CREATE TABLE reperto(
  sacchetto integer PRIMARY KEY REFERENCES sacchetto(id) on DELETE CASCADE,
  materiale integer not null REFERENCES list.materiale(id),
  tipologia integer not null REFERENCES list.tipologia(id),
  note CHARACTER VARYING
);
CREATE TABLE campione(
  sacchetto integer PRIMARY KEY REFERENCES sacchetto(id) on DELETE CASCADE,
  tipologia integer not null REFERENCES list.tipo_campione(id)
);
COMMIT;
