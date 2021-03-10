SELECT *
FROM escalas e
join profissionais p on (e.profissional_id = p.id)
WHERE dataplantao >= '2020-09-01' - INTERVAL 28 DAY
order by dataplantao, horainicialplantao
limit 84

SELECT e.horainicialplantao, e.horafinalplantao, e.duracao,
e.profissional_id
FROM escalas e
WHERE dataplantao >= '2020-09-01' - INTERVAL 28 DAY
order by dataplantao, horainicialplantao
limit 84