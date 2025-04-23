<template>
  <div class="relatorios-container">
    <!-- Título da página -->
    <h1>Relatórios do Sistema</h1>

    <!-- Container para os gráficos -->
    <div class="card-container">
      <!-- Gráfico de Revisões por Mês -->
      <BaseCard>
        <template #header>
          <h2>Revisões por Mês</h2>
        </template>
        <!-- Componente de gráfico do tipo barra -->
        <ChartComponent
          v-if="revisoesPorMesData"
          :data="revisoesPorMesData"
          type="bar"
          :height="220"
        />
      </BaseCard>

      <!-- Gráfico de Distribuição de Veículos -->
      <BaseCard>
        <template #header>
          <h2>Distribuição de Veículos</h2>
        </template>
        <!-- Componente de gráfico do tipo pizza -->
        <ChartComponent
          v-if="distribVeiculosData"
          :data="distribVeiculosData"
          type="pie"
          :height="220"
        />
      </BaseCard>

      <!-- Gráfico de Status das Revisões -->
      <BaseCard>
        <template #header>
          <h2>Status das Revisões</h2>
        </template>
        <!-- Componente de gráfico do tipo doughnut -->
        <ChartComponent
          v-if="statusRevisoesData"
          :data="statusRevisoesData"
          type="doughnut"
          :height="220"
        />
      </BaseCard>

      <!-- Gráfico de Veículos por Gênero -->
      <BaseCard>
        <template #header>
          <h2>Veículos por Gênero</h2>
        </template>
        <!-- Componente de gráfico do tipo barra -->
        <ChartComponent
          v-if="veiculosPorGenero"
          :data="veiculosPorGenero"
          type="bar"
          :height="220"
        />
      </BaseCard>
    </div>

    <!-- Histórico de Revisões Recentes -->
    <BaseCard>
      <template #header>
        <h2>Histórico de Revisões Recentes</h2>
      </template>
      <div class="table-container">
        <table class="report-table">
          <thead>
            <tr>
              <th>Proprietário</th>
              <th>Veículo</th>
              <th>Data</th>
              <th>Status</th>
              <th>Serviços</th>
            </tr>
          </thead>
          <tbody>
            <!-- Exibe cada revisão no histórico -->
            <tr v-for="(rev, idx) in historicoRevisoes" :key="idx">
              <td>{{ rev.proprietario }}</td>
              <td>{{ rev.veiculo }}</td>
              <td>{{ formatDate(rev.data) }}</td>
              <td>
                <!-- Badge de status com cores diferentes -->
                <span :class="'status-badge status-' + rev.status.toLowerCase()">
                  {{ rev.status }}
                </span>
              </td>
              <td>{{ rev.servicos.join(', ') }}</td>
            </tr>
            <!-- Mensagem caso não haja histórico -->
            <tr v-if="historicoRevisoes.length === 0">
              <td colspan="5" class="empty-message">Nenhum histórico disponível.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </BaseCard>

    <!-- Média de Tempo entre Revisões -->
    <BaseCard>
      <template #header>
        <h2>Média de Tempo entre Revisões</h2>
      </template>
      <table class="table">
        <thead>
          <tr>
            <th>Pessoa</th>
            <th>Média de Dias</th>
          </tr>
        </thead>
        <tbody>
          <!-- Exibe a média de tempo entre revisões para cada pessoa -->
          <tr v-for="item in mediaTempoRevisoes" :key="item.pessoa_id">
            <td>{{ item.pessoa_id }}</td>
            <td>{{ item.media_dias ? item.media_dias.toFixed(2) : "N/A" }}</td>
          </tr>
        </tbody>
      </table>
    </BaseCard>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import BaseCard from "../components/common/BaseCard.vue";
import ChartComponent from "../components/dashboard/ChartComponent.vue";

// Dados para os gráficos
const revisoesPorMesData = ref(null); // Dados para o gráfico de revisões por mês
const distribVeiculosData = ref(null); // Dados para o gráfico de distribuição de veículos
const statusRevisoesData = ref(null); // Dados para o gráfico de status das revisões
const veiculosPorGenero = ref(null); // Dados para o gráfico de veículos por gênero

// Histórico de revisões recentes
const historicoRevisoes = ref([]);

// Média de tempo entre revisões
const mediaTempoRevisoes = ref([]);

// Função para formatar datas no formato brasileiro (dd/mm/aaaa)
function formatDate(date) {
  if (!date) return "";
  const d = new Date(date);
  return d.toLocaleDateString("pt-BR");
}

// Quando o componente é carregado
onMounted(async () => {
  // 1. Buscar dados da API
  const [resRevisoes, resVeiculos, resPessoas] = await Promise.all([
    axios.get("http://localhost/api/revisoes"),
    axios.get("http://localhost/api/veiculos"),
    axios.get("http://localhost/api/pessoas"),
  ]);
  const revisoes = resRevisoes.data;
  const veiculos = resVeiculos.data;
  const pessoas = resPessoas.data;

  // 2. Preencher dados do gráfico de Revisões por Mês
  const meses = [
    "Jan", "Fev", "Mar", "Abr", "Mai", "Jun",
    "Jul", "Ago", "Set", "Out", "Nov", "Dez"
  ];
  const revisoesPorMes = Array(12).fill(0);
  revisoes.forEach(r => {
    const data = new Date(r.data_prevista || r.data_realizada);
    if (!isNaN(data)) {
      revisoesPorMes[data.getMonth()]++;
    }
  });
  revisoesPorMesData.value = {
    labels: meses,
    datasets: [
      {
        label: "Revisões",
        backgroundColor: "#36A2EB",
        data: revisoesPorMes,
      },
    ],
  };

  // 3. Preencher dados do gráfico de Distribuição de Veículos
  const tipos = {};
  veiculos.forEach(v => {
    const tipo = v.marca || "Outro";
    tipos[tipo] = (tipos[tipo] || 0) + 1;
  });
  distribVeiculosData.value = {
    labels: Object.keys(tipos),
    datasets: [
      {
        data: Object.values(tipos),
        backgroundColor: ["#36A2EB", "#FF6384", "#FFCE56", "#8BC34A", "#9C27B0"],
      },
    ],
  };

  // 4. Preencher dados do gráfico de Status das Revisões
  const statusCount = { Agendada: 0, Concluída: 0, Cancelada: 0 };
  revisoes.forEach(r => {
    const status = r.status || "Agendada";
    statusCount[status] = (statusCount[status] || 0) + 1;
  });
  statusRevisoesData.value = {
    labels: Object.keys(statusCount),
    datasets: [
      {
        data: Object.values(statusCount),
        backgroundColor: ["#2196f3", "#4caf50", "#f44336"],
      },
    ],
  };

  // 5. Preencher histórico de revisões recentes
  historicoRevisoes.value = revisoes
    .sort((a, b) => new Date(b.data_realizada) - new Date(a.data_realizada))
    .slice(0, 10)
    .map(r => {
      const veiculo = veiculos.find(v => v.id === r.veiculo_id);
      const pessoa = pessoas.find(p => p.id === veiculo?.pessoa_id);
      return {
        proprietario: pessoa ? pessoa.nome : "Desconhecido",
        veiculo: veiculo ? `${veiculo.marca} ${veiculo.modelo}` : "Desconhecido",
        data: r.data_realizada,
        status: r.status,
        servicos: Array.isArray(r.servicos) ? r.servicos : [],
      };
    });

  // 6. Preencher dados do gráfico de Veículos por Gênero
  const res = await axios.get("http://localhost/api/relatorios/veiculos-por-genero");
  veiculosPorGenero.value = {
    labels: res.data.map(item => item.genero),
    datasets: [
      {
        label: "Veículos",
        data: res.data.map(item => item.total),
        backgroundColor: ["#36A2EB", "#FF6384"],
      },
    ],
  };

  // 7. Preencher dados da Média de Tempo entre Revisões
  const resMediaTempo = await axios.get("http://localhost/api/relatorios/media-tempo-revisoes");
  mediaTempoRevisoes.value = resMediaTempo.data;
});
</script>

<style scoped>
/* Estilos gerais para a página de relatórios */
.relatorios-container {
  padding: 1.5rem;
}

h1 {
  margin-bottom: 2rem;
  font-size: 2rem;
  font-weight: 700;
}

.card-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.table-container {
  overflow-x: auto;
}

.report-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
  background: transparent;
}

.report-table th,
.report-table td {
  padding: 0.75rem 1rem;
  text-align: left;
  border-bottom: 1px solid #23243a;
}

.report-table th {
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
