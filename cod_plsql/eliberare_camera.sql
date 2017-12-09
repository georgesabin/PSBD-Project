SET SERVEROUTPUT ON

CREATE OR REPLACE PROCEDURE eliberare_camere

IS
CURSOR C1 IS select * from rezervare_ocupare;
BEGIN
	FOR rez in c1 LOOP
        if rez.data_start < SYSDATE AND rez.status_camera = 1 THEN
            --eliberam camera
            UPDATE rezervare_ocupare set status_camera = 0 where id = rez.id;
        end if; 
    end LOOP;
END;
/

--CREATE TABLE rezervare_ocupare (id number NOT NULL, id_client number DEFAULT 0 NOT NULL, id_camera number DEFAULT 0 NOT NULL, data_start date, data_sfarsit date, status_camera smallint DEFAULT 0 NOT NULL)",
