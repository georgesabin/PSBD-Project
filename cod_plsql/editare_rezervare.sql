SET SERVEROUTPUT ON

CREATE OR REPLACE PROCEDURE editare_rezervare(v_cnp IN clienti.cnp%TYPE)
IS
v_id_client clienti.id%TYPE;
BEGIN
	SELECT id
	INTO v_id_client
	FROM clienti
	WHERE cnp=v_cnp;
	SELECT *
	FROM rezervare_ocupare
	WHERE id_client=v_id_client;
END;
/