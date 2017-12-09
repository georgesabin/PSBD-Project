CREATE  SEQUENCE rezervare_ocupare_seq START WITH 1;

CREATE OR REPLACE TRIGGER rezervare_ocupare_on_insert 
BEFORE INSERT ON rezervare_ocupare
FOR EACH ROW
BEGIN
  SELECT rezervare_ocupare_seq.nextval
  INTO :new.id
  FROM dual;
END;
/

CREATE OR REPLACE PROCEDURE insert_rezervare_ocupare
(v_id_client rezervare_ocupare.id_client%TYPE, v_id_camera rezervare_ocupare.id_camera%TYPE,
v_data_start rezervare_ocupare.data_start%TYPE, v_data_finish rezervare_ocupare.data_sfarsit%TYPE)
IS
BEGIN
    INSERT INTO rezervare_ocupare (id_client, id_camera, data_start, data_sfarsit, status_camera) VALUES (v_id_client, v_id_camera, v_data_start, v_data_finish, 1);
END insert_rezervare_ocupare;
/