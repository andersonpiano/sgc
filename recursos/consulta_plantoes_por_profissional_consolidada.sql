select escalas.id, escalas.dataplantao, escalas.datafinalplantao,
escalas.horainicialplantao, escalas.horafinalplantao,
escalas.duracao, escalas.profissional_id, escalas.setor_id,
profissionais.registro as profissional_registro,
profissionais.nome as profissional_nome,
setores.nome as setores_nomes,
unidadeshospitalares.razaosocial as unidadeshospitalares_razaosocial,
'Original' as tipoescala
from escalas
join profissionais on (escalas.profissional_id = profissionais.id)
join setores on (escalas.setor_id = setores.id)
join unidadeshospitalares on (setores.unidadehospitalar_id = unidadeshospitalares.id)
where profissionais.id = 12
and escalas.id not in
(select escala_id
from passagenstrocas
where escala_id = escalas.id)
union
select escalas.id, escalas.dataplantao, escalas.datafinalplantao,
escalas.horainicialplantao, escalas.horafinalplantao,
escalas.duracao, escalas.profissional_id, escalas.setor_id,
profissionais.registro as profissional_registro,
profissionais.nome as profissional_nome,
setores.nome as setores_nomes,
unidadeshospitalares.razaosocial as unidadeshospitalares_razaosocial,
case
  when passagenstrocas.tipopassagem=0 then 'Cessão'
  when passagenstrocas.tipopassagem=1 then 'Troca'
end as tipoescala
from escalas
join passagenstrocas on (escalas.id = passagenstrocas.escala_id)
join profissionais on (passagenstrocas.profissionalsubstituto_id = profissionais.id)
join setores on (escalas.setor_id = setores.id)
join unidadeshospitalares on (setores.unidadehospitalar_id = unidadeshospitalares.id)
where profissionais.id = 12
and passagenstrocas.statuspassagem = 1
order by dataplantao, horainicialplantao