begin;
insert into users(nome, cognome, email, classe, attivo, password) VALUES
  ('Alessandro', 'Bezzi', 'alessandro.bezzi@arc-team.com', 1, 't', crypt('bezziMinor',gen_salt('bf',8))),
  ('Luca', 'Bezzi', 'luca.bezzi@arc-team.com', 1, 't', crypt('bezziMaior',gen_salt('bf',8))),
  ('Rupert', 'Gietl', 'ruppi@arc-team.com', 1, 't', crypt('iLoveItaly',gen_salt('bf',8)));
COMMIT;
