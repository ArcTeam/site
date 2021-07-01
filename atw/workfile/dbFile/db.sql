BEGIN;
create TABLE inventario(
  id serial primary key,
  inventario integer not null,
  scavo integer not null references scavo(id) on delete cascade,
  UNIQUE(inventario, scavo)
);

COMMIT;
