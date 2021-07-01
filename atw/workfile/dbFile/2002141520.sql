BEGIN;
DROP TABLE IF EXISTS reperto;
CREATE TABLE reperto(
  id serial PRIMARY KEY,
  scavo SMALLINT NOT NULL REFERENCES scavo(id) ON DELETE CASCADE,
  us SMALLINT NOT NULL,
  reperto SMALLINT NOT NULL,
  materiale SMALLINT NOT NULL REFERENCES list.materiale(id),
  tipologia SMALLINT NOT NULL REFERENCES list.tipologia(id),
  note CHARACTER VARYING,
  data DATE NOT NULL,
  operatore SMALLINT NOT NULL REFERENCES users(id),
  UNIQUE (scavo,us,reperto)
);
COMMIT;
