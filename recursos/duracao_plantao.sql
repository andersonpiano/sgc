-- Duração de plantão
UPDATE `escalas`
SET duracao = cast(abs(horafinalplantao - horainicialplantao)/10000 as int)