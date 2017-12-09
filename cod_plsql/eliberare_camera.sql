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


--insert into rezervare_ocupare values (1, 1, '1-DEC-2017', '2-DEC-2017', 1)
--insert into rezervare_ocupare(id_client, id_camera, data_start, data_sfarsit, status_camera) values ( 1, 1, '18-DEC-2017', '22-DEC-2017', 1)
--insert into rezervare_ocupare(id_client, id_camera, data_start, data_sfarsit, status_camera) values ( 1, 1, '12-DEC-2017', '14-DEC-2017', 1)
--insert into rezervare_ocupare(id_client, id_camera, data_start, data_sfarsit, status_camera) values ( 1, 1, '12-AUG-2017', '14-AUG-2017', 1)