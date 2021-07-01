BEGIN;
DROP TABLE campione;
CREATE TABLE campione(
  inventario INTEGER PRIMARY KEY REFERENCES inventario(id),
  us SMALLINT NOT NULL REFERENCES us(id),
  campione integer NOT NULL,
  tipo SMALLINT NOT NULL REFERENCES list.tipo_campione(id),
  data DATE NOT NULL DEFAULT now(),
  autore SMALLINT NOT NULL REFERENCES users(id),
  descrizione text NOT NULL,
  UNIQUE (us, campione)
);

ALTER TABLE reperto
  ADD COLUMN inventario INTEGER NOT NULL REFERENCES inventario(id),
  ADD CONSTRAINT reperto_unique UNIQUE (us, reperto),
  ADD CONSTRAINT reperto_us_fki FOREIGN KEY (us) REFERENCES us(id) ON DELETE CASCADE;
COMMIT;
