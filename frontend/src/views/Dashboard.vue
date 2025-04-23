<template>
  <div class="dashboard">
    <!-- Título da página -->
    <h1 class="page-title">
      <font-awesome-icon icon="chart-pie" /> Painel de Controle
    </h1>

    <!-- Estatísticas -->
    <div class="stats-grid">
      <!-- Cartão de estatísticas para Proprietários -->
      <StatCard
        titulo="Proprietários"
        :valor="stats.proprietarios"
        descricao="Total de pessoas cadastradas"
        tipo="pessoa"
        :tendencia="stats.tendenciaProprietarios"
      />
      <!-- Cartão de estatísticas para Veículos -->
      <StatCard
        titulo="Veículos"
        :valor="stats.veiculos"
        descricao="Total de veículos"
        tipo="veiculo"
        :tendencia="stats.tendenciaVeiculos"
      />
      <!-- Cartão de estatísticas para Revisões do mês -->
      <StatCard
        titulo="Revisões este mês"
        :valor="stats.revisoesMes"
        descricao="Revisões realizadas no mês"
        tipo="revisao"
        :tendencia="stats.tendenciaRevisoesMes"
      />
      <!-- Cartão de estatísticas para Próximas Revisões -->
      <StatCard
        titulo="Próximas Revisões"
        :valor="stats.proximasRevisoes"
        descricao="Agendadas para os próximos 30 dias"
        tipo="revisao"
        :tendencia="0"
      />
    </div>

    <!-- Gráficos -->
    <div class="charts-grid">
      <!-- Gráfico de Revisões por Mês -->
      <BaseCard>
        <template #header>
          <h2>Revisões por Mês</h2>
        </template>
        <ChartComponent
          v-if="chartData.revisoesPorMes"
          type="bar"
          :data="chartData.revisoesPorMes"
          :height="260"
        />
      </BaseCard>

      <!-- Gráfico de Distribuição de Veículos -->
      <BaseCard>
        <template #header>
          <h2>Distribuição de Veículos</h2>
        </template>
        <ChartComponent
          v-if="chartData.distribuicaoVeiculos"
          type="pie"
          :data="chartData.distribuicaoVeiculos"
          :height="260"
        />
      </BaseCard>

      <!-- Gráfico de Status das Revisões -->
      <BaseCard>
        <template #header>
          <h2>Status das Revisões</h2>
        </template>
        <ChartComponent
          v-if="chartData.statusRevisoes"
          type="doughnut"
          :data="chartData.statusRevisoes"
          :height="260"
        />
      </BaseCard>
    </div>

    <!-- Tabela de Próximas Revisões -->
    <BaseCard>
      <template #header>
        <h2>Próximas Revisões</h2>
      </template>
      <div class="table-container">
        <table class="table">
          <thead>
            <tr>
              <th>Proprietário</th>
              <th>Veículo</th>
              <th>Data Prevista</th>
              <th>Status</th>
              <th>Serviços</th>
            </tr>
          </thead>
          <tbody>
            <!-- Exibe cada revisão agendada -->
            <tr v-for="(revisao, index) in proximasRevisoes" :key="index">
              <td>{{ revisao.proprietario }}</td>
              <td>{{ revisao.veiculo }}</td>
              <td>{{ formatDate(revisao.data_prevista) }}</td>
              <td>
                <!-- Badge de status com cores diferentes -->
                <span :class="'status-badge status-' + revisao.status.toLowerCase()">
                  {{ revisao.status }}
                </span>
              </td>
              <td>{{ revisao.servicos.join(', ') }}</td>
            </tr>
            <!-- Mensagem caso não haja revisões -->
            <tr v-if="proximasRevisoes.length === 0">
              <td colspan="5" class="empty-message">Nenhuma revisão agendada.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </BaseCard>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import StatCard from "../components/dashboard/StatCard.vue";
import ChartComponent from "../components/dashboard/ChartComponent.vue";
import BaseCard from "../components/common/BaseCard.vue";

// Estado para as estatísticas gerais
const stats = ref({
  proprietarios: 0,
  veiculos: 0,
  revisoesMes: 0,
  proximasRevisoes: 0,
  tendenciaProprietarios: 0,
  tendenciaVeiculos: 0,
  tendenciaRevisoesMes: 0,
});

// Estado para os dados dos gráficos
const chartData = ref({
  revisoesPorMes: null,
  distribuicaoVeiculos: null,
  statusRevisoes: null,
});

// Lista de próximas revisões
const proximasRevisoes = ref([]);

// Função para formatar datas no formato brasileiro (dd/mm/aaaa)
function formatDate(date) {
  if (!date) return "";
  const d = new Date(date);
  return d.toLocaleDateString("pt-BR");
}

// Quando o componente é carregado
onMounted(async () => {
  // 1. Buscar dados da API
  const [resPessoas, resVeiculos, resRevisoes] = await Promise.all([
    axios.get("http://localhost/api/pessoas"),
    axios.get("http://localhost/api/veiculos"),
    axios.get("http://localhost/api/revisoes"),
  ]);
  const pessoas = resPessoas.data;
  const veiculos = resVeiculos.data;
  const revisoes = resRevisoes.data;

  // Atualiza as estatísticas gerais
  stats.value.proprietarios = pessoas.length;
  stats.value.veiculos = veiculos.length;

  // Filtra revisões do mês atual
  const now = new Date();
  const mesAtual = now.getMonth();
  const anoAtual = now.getFullYear();
  const revisoesMes = revisoes.filter(r => {
    const data = new Date(r.data_realizada || r.data_prevista || r.dataPrevista);
    return data.getMonth() === mesAtual && data.getFullYear() === anoAtual && r.status === "Concluída";
  });
  stats.value.revisoesMes = revisoesMes.length;

  // Filtra próximas revisões (agendadas para os próximos 30 dias)
  const hoje = new Date();
  const daqui30 = new Date();
  daqui30.setDate(hoje.getDate() + 30);
  const proximas = revisoes.filter(r => {
    const data = new Date(r.data_prevista || r.dataPrevista);
    return r.status === "Agendada" && data >= hoje && data <= daqui30;
  });
  stats.value.proximasRevisoes = proximas.length;
  proximasRevisoes.value = proximas.map(r => {
    const veiculo = veiculos.find(v => v.id === (r.veiculo_id || r.veiculoId));
    const pessoa = pessoas.find(p => p.id === (veiculo?.pessoa_id || veiculo?.proprietario_id));
    return {
      proprietario: pessoa ? pessoa.nome : "Desconhecido",
      veiculo: veiculo ? `${veiculo.marca} ${veiculo.modelo} (${veiculo.ano_fabricacao})` : "Desconhecido",
      data_prevista: r.data_prevista || r.dataPrevista,
      status: r.status,
      servicos: Array.isArray(r.servicos) ? r.servicos : (r.servicos ? [r.servicos] : []),
    };
  });

  // Calcula tendências (exemplo simples)
  const revisoesMesAnterior = revisoes.filter(r => {
    const data = new Date(r.data_realizada || r.data_prevista || r.dataPrevista);
    return data.getMonth() === ((mesAtual + 11) % 12) && data.getFullYear() === (mesAtual === 0 ? anoAtual - 1 : anoAtual) && r.status === "Concluída";
  });
  stats.value.tendenciaRevisoesMes = stats.value.revisoesMes - revisoesMesAnterior.length;

  // Preenche dados dos gráficos
  // Gráfico de Revisões por Mês
  const meses = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];
  const revisoesPorMes = Array(12).fill(0);
  revisoes.forEach(r => {
    const data = new Date(r.data_realizada || r.data_prevista || r.dataPrevista);
    if (!isNaN(data)) {
      const diffMeses = (now.getFullYear() - data.getFullYear()) * 12 + (now.getMonth() - data.getMonth());
      if (diffMeses >= 0 && diffMeses < 12 && r.status === "Concluída") {
        revisoesPorMes[11 - diffMeses]++;
      }
    }
  });
  chartData.value.revisoesPorMes = {
    labels: meses,
    datasets: [
      {
        label: "Revisões Concluídas",
        backgroundColor: "#36A2EB",
        data: revisoesPorMes,
      },
    ],
  };

  // Gráfico de Distribuição de Veículos
  const marcas = {};
  veiculos.forEach(v => {
    const marca = v.marca || "Outro";
    marcas[marca] = (marcas[marca] || 0) + 1;
  });
  chartData.value.distribuicaoVeiculos = {
    labels: Object.keys(marcas),
    datasets: [
      {
        data: Object.values(marcas),
        backgroundColor: ["#36A2EB", "#FF6384", "#FFCE56", "#8BC34A", "#9C27B0", "#f44336"],
      },
    ],
  };

  // Gráfico de Status das Revisões
  const statusCount = { Agendada: 0, Concluída: 0, Cancelada: 0 };
  revisoes.forEach(r => {
    const status = r.status || "Agendada";
    statusCount[status] = (statusCount[status] || 0) + 1;
  });
  chartData.value.statusRevisoes = {
    labels: Object.keys(statusCount),
    datasets: [
      {
        data: Object.values(statusCount),
        backgroundColor: ["#2196f3", "#4caf50", "#f44336"],
      },
    ],
  };
});
</script>

<style scoped>
/* Estilos gerais do painel */
.dashboard {
  width: 100%;
  padding: 2rem;
  background: #181929;
  color: #ecf0f1;
}

/* Título da página */
.page-title {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 2rem;
  display: flex;
  align-items: center;
  gap: 0.7rem;
}

/* Grid para os cartões de estatísticas */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2.5rem;
}

/* Grid para os gráficos */
.charts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2.5rem;
}

/* Estilos para a tabela */
.table-container {
  overflow-x: auto;
}

.table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
  background: transparent;
}

.table th,
.table td {
  padding: 0.75rem 1rem;
  text-align: left;
  border-bottom: 1px solid #23243a;
}

.table th {
  background-color: #23243a;
  color: #fff;
  font-weight: 500;
}

.empty-message {
  text-align: center;
  color: #999;
  font-style: italic;
  padding: 1rem;
}

/* Estilos para os badges de status */
.status-badge {
  display: inline-block;
  padding: 0.3em 0.8em;
  border-radius: 1em;
  font-size: 0.95em;
  font-weight: 600;
  color: #fff;
}
.status-agendada {
  background: #2196f3;
}
.status-concluída {
  background: #4caf50;
}
.status-cancelada {
  background: #f44336;
}
</style>
