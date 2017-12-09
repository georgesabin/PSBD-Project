SET SERVEROUTPUT ON
CREATE OR REPLACE TRIGGER rez_verifiy_date
BEFORE INSERT ON rezervare_ocupare
FOR EACH ROW
declare
CURSOR C1 IS SELECT * FROM rezervare_ocupare where id_camera = :new.id_camera;
BEGIN
    IF :new.data_start < SYSDATE then
        Raise_Application_Error (-20343, 'Nu se poate face rezervare in trecut' );

    end if;
    FOR rez in c1 loop
        IF NOT (:new.data_start > rez.data_sfarsit OR :new.data_sfarsit < rez.data_start)
        then
            Raise_Application_Error (-20343, 'Camera ocupata in intervalul ' || to_char(rez.data_start)|| ' '||to_char(rez.data_sfarsit));
        end if;

    end loop;

end;
/
--CREATE TABLE rezervare_ocupare (id number NOT NULL, id_client number DEFAULT 0 NOT NULL, id_camera number DEFAULT 0 NOT NULL, data_start date, data_sfarsit date, status_camera smallint DEFAULT 0 NOT NULL)",

-- || to_char(rez.data_start)|| ' '||to_char(rez.data_sfarsit));