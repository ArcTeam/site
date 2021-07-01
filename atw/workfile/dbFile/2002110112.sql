BEGIN;
DROP TABLE osm CASCADE;
ALTER TABLE scavo DROP CONSTRAINT scavo_comune_fkey;
create table osm as select osm_id, name, geom from osm_orig;
ALTER TABLE osm ALTER COLUMN osm_id TYPE integer USING (osm_id::integer);
alter TABLE osm add CONSTRAINT osm_pki PRIMARY KEY(osm_id);
alter table scavo add constraint scavo_comune_fki FOREIGN KEY (comune) REFERENCES osm(osm_id);
COMMIT;
