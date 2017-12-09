SET SERVEROUTPUT ON

CREATE OR REPLACE FUNCTION verificare_camera
(v_id_camera IN camere.id%TYPE, v_data_sosire IN rezervare_ocupare.data_start%TYPE, v_data_plecare IN rezervare_ocupare.data_sfarsit%TYPE)
RETURN number
IS
v_exists number := 0;
BEGIN
	SELECT count(*)
	INTO v_exists
	FROM rezervare_ocupare
	WHERE id_camera=v_id_camera
	AND not in (data_start<=v_data_plecare
	AND data_sfarsit>=v_data_sosire);
	RETURN v_exists;
END verificare_camera;
/

--insert into rezervare_ocupare values(1, 1, 1, "2017-11-14", "2017-12-01", 1);
--insert into rezervare_ocupare values(1, 1, 1, '14-NOV-2017', '01-DEC-2017', 1);

--EXECUTARE FISIER PL/SQL
--@/home/sabin/Downloads/PSBD/verificare_camera.sql
--@/var/www/html/psbdproject/verificare_camera.sql
