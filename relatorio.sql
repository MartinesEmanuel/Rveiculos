-- Relatório 1: Veículos por Gênero
SELECT pessoas.genero, COUNT(veiculos.id) AS total
FROM pessoas
JOIN veiculos ON pessoas.id = veiculos.pessoa_id
GROUP BY pessoas.genero
ORDER BY total DESC;

-- Relatório 2: Marcas por Veículos
SELECT marca, COUNT(id) AS total
FROM veiculos
GROUP BY marca
ORDER BY total DESC;

-- Relatório 3: Média de Tempo entre Revisões
SELECT veiculos.pessoa_id, 
       AVG(data_prevista - LAG(data_prevista) OVER (PARTITION BY veiculos.pessoa_id ORDER BY data_prevista)) AS media_dias
FROM martins.revisoes
JOIN martins.veiculos ON revisoes.veiculo_id = veiculos.id
GROUP BY veiculos.pessoa_id;

-- Relatório 4: Revisões por Mês
SELECT EXTRACT(MONTH FROM data_prevista) AS mes, COUNT(id) AS total
FROM martins.revisoes
GROUP BY EXTRACT(MONTH FROM data_prevista)
ORDER BY mes;

-- Relatório 5: Histórico de Revisões Recentes
SELECT revisoes.id, pessoas.nome AS proprietario, veiculos.marca, veiculos.modelo, revisoes.data_realizada, revisoes.status
FROM revisoes
JOIN veiculos ON revisoes.veiculo_id = veiculos.id
JOIN pessoas ON veiculos.pessoa_id = pessoas.id
ORDER BY revisoes.data_realizada DESC
LIMIT 10;

-- Relatório 6: Status das Revisões
SELECT status, COUNT(id) AS total
FROM revisoes
GROUP BY status
ORDER BY total DESC;

--psql -U sail -d sail -f ./relatorio.sql