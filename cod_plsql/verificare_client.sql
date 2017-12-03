SET SERVEROUTPUT ON

--Am creat o secventa pentru a putea face AUTO_INCREMENT
--CREATE OR REPLACE SEQUENCE dept_seq START WITH 1;

--Am creat un trigger pentru a incrementa id-ul din tabela clienti
--Tabela dual nu exista. Este ceva fictiv pentru a putea accesa urmatorul id
CREATE OR REPLACE TRIGGER clienti_on_insert 
BEFORE INSERT ON clienti
FOR EACH ROW
BEGIN
  SELECT dept_seq.nextval
  INTO :new.id
  FROM dual;
END;
/

CREATE OR REPLACE FUNCTION verificare_client
(v_cnp_client IN clienti.cnp%TYPE, v_nume_client IN clienti.nume%TYPE,
v_nr_telefon_client IN clienti.nr_telefon%TYPE)
RETURN NUMBER
IS
v_exists number := 0;
v_client_id number;
BEGIN
	SELECT count(*)
	INTO v_exists
	FROM clienti
	WHERE cnp=v_cnp_client;
    IF v_exists = 1 THEN
        UPDATE clienti SET nume=v_nume_client, nr_telefon=v_nr_telefon_client WHERE cnp=v_cnp_client;
    ELSE
        INSERT INTO clienti (nume, cnp, nr_telefon) VALUES(v_nume_client, v_cnp_client, v_nr_telefon_client);
    END IF;
    SELECT id
	INTO v_client_id
	FROM clienti
	WHERE cnp=v_cnp_client;
    RETURN v_client_id;
END verificare_client;
/

--insert into rezervare_ocupare values(1, 1, 1, "2017-11-14", "2017-12-01", 1);
--insert into rezervare_ocupare values(1, 1, 1, '14-NOV-2017', '01-DEC-2017', 1);

--EXECUTARE FISIER PL/SQL
--@/home/sabin/Downloads/PSBD/verificare_camera.sql
--@/var/www/html/psbdproject/cod_plsql/verificare_camera.sql

--execute verificare_client('1950304227897', 'Sabin George', '0987654321');