CREATE OR REPLACE PROCEDURE update_rezervare
(v_id IN rezervare_ocupare.id%TYPE, v_id_camera IN camere.id%TYPE, v_data_start IN rezervare_ocupare.data_start%TYPE, v_data_plecare IN rezervare_ocupare.data_sfarsit%TYPE)
IS
CURSOR C1 IS SELECT * FROM rezervare_ocupare where id_camera = v_id_camera AND id != v_id;
BEGIN
	IF v_data_start > v_data_plecare then
        Raise_Application_Error (-20343, 'Data plecare < Data sosire');
    end if;
    FOR rez in c1 loop
        IF NOT (v_data_start > rez.data_sfarsit OR v_data_plecare < rez.data_start)
        then
            Raise_Application_Error (-20343, 'Camera ocupata in intervalul ' || to_char(rez.data_start)|| ' '||to_char(rez.data_sfarsit));
        end if;

    end loop;
    UPDATE rezervare_ocupare SET data_start = v_data_start, data_sfarsit = v_data_plecare where id = v_id;
END update_rezervare;
/

--exec update_rezervare(22, 1, '25-DEC-2017', '30-DEC-2017')
--update rezervare_ocupare set data_start = '12-DEC-2017', data_sfarsit = '15-DEC-2017' where id = 22;
--insert into rezervare_ocupare(id_client, id_camera, data_start, data_sfarsit, status_camera) values ( 1, 1, '9-DEC-2017', '10-DEC-2017', 1)