<template>
  <div class="p-6">
    <!-- Cabeçalho da página -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Veículos</h1>
      <!-- Botão para abrir o modal de cadastro de novo veículo -->
      <BaseButton
        @click="abrirModalNovoVeiculo"
        class="bg-blue-600 hover:bg-blue-700"
      >
        <PlusIcon class="w-5 h-5 mr-2" />
        Novo Veículo
      </BaseButton>
    </div>

    <!-- Filtros e busca -->
    <div class="mb-6 flex flex-wrap gap-4">
      <!-- Campo de busca -->
      <div class="flex-1 min-w-[200px]">
        <BaseInput
          v-model="filtros.busca"
          placeholder="Buscar por modelo, placa, proprietário..."
          type="search"
        >
          <template #prepend>
            <SearchIcon class="w-5 h-5 text-gray-400" />
          </template>
        </BaseInput>
      </div>
      <!-- Filtro por marca -->
      <BaseSelect
        v-model="filtros.marca"
        :options="opcoesMarcas"
        placeholder="Todas as marcas"
        class="w-40"
      />
      <!-- Filtro por ano -->
      <BaseSelect
        v-model="filtros.ano"
        :options="opcoesAnos"
        placeholder="Todos os anos"
        class="w-40"
      />
    </div>

    <!-- Lista de veículos em cards -->
    <div v-if="veiculosFiltrados.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Exibe cada veículo como um card -->
      <div
        v-for="veiculo in veiculosFiltrados"
        :key="veiculo.id"
        class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col justify-between"
      >
        <div>
          <!-- Informações do veículo -->
          <div class="flex items-center mb-2">
            <CarIcon class="w-6 h-6 text-blue-500 mr-2" />
            <span class="font-semibold text-lg">{{ veiculo.marca }} {{ veiculo.modelo }} ({{ veiculo.ano_fabricacao }})</span>
          </div>
          <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-600 dark:text-gray-300 mb-2">
            <span><b>Placa:</b> {{ veiculo.placa }}</span>
            <span><b>Cor:</b> {{ veiculo.cor }}</span>
            <span><b>Proprietário:</b> {{ veiculo.pessoa?.nome || veiculo.proprietario_nome }}</span>
          </div>
          <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-600 dark:text-gray-300 mb-2">
            <span>
              <b>Próxima Revisão:</b>
              <span :class="getStatusRevisaoClass(veiculo.proxima_revisao)">
                {{ formatarData(veiculo.proxima_revisao) }}
              </span>
            </span>
          </div>
        </div>
        <!-- Botões de ação -->
        <div class="flex gap-2 mt-4 justify-end">
          <!-- Botão para editar o veículo -->
          <button class="btn btn-sm btn-outline" @click="editarVeiculo(veiculo)" title="Editar">
            <EditIcon class="w-4 h-4" />
          </button>
          <!-- Botão para visualizar revisões -->
          <button class="btn btn-sm btn-warning" @click="visualizarRevisoes(veiculo)" title="Revisões">
            <WrenchIcon class="w-4 h-4" />
          </button>
          <!-- Botão para excluir o veículo -->
          <button class="btn btn-sm btn-danger" @click="confirmarExclusao(veiculo)" title="Excluir">
            <TrashIcon class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>

    <!-- Mensagem quando não há veículos -->
    <div
      v-else
      class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center flex flex-col items-center"
    >
      <CarIcon class="w-16 h-16 text-gray-400 mb-4" />
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
        Nenhum veículo encontrado
      </h3>
      <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
        {{
          veiculos.length === 0
            ? "Ainda não há veículos cadastrados."
            : "Nenhum veículo corresponde aos filtros aplicados."
        }}
      </p>
      <!-- Botão para adicionar o primeiro veículo -->
      <BaseButton
        v-if="veiculos.length === 0"
        @click="abrirModalNovoVeiculo"
        class="bg-blue-600 hover:bg-blue-700"
      >
        Adicionar o primeiro veículo
      </BaseButton>
    </div>

    <!-- Modal para adicionar/editar veículo -->
    <div
      v-if="mostrarModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">
            {{ veiculoEmEdicao.id ? "Editar" : "Novo" }} Veículo
          </h3>
          <!-- Botão para fechar o modal -->
          <button
            @click="fecharModal"
            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
          >
            <XIcon class="w-5 h-5" />
          </button>
        </div>
        <!-- Formulário para adicionar/editar veículo -->
        <form @submit.prevent="salvarVeiculo">
          <div class="space-y-4">
            <!-- Campo para selecionar o proprietário -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Proprietário</label>
              <BaseSelect
                v-model="veiculoEmEdicao.proprietarioId"
                :options="proprietarios"
                placeholder="Selecione o proprietário"
                class="w-full"
                required
              />
            </div>
            <!-- Campos para marca e modelo -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Marca</label>
                <BaseInput v-model="veiculoEmEdicao.marca" required />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Modelo</label>
                <BaseInput v-model="veiculoEmEdicao.modelo" required />
              </div>
            </div>
            <!-- Campos para ano e cor -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ano</label>
                <BaseInput
                  v-model="veiculoEmEdicao.ano_fabricacao"
                  type="number"
                  min="1900"
                  :max="anoAtual"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cor</label>
                <BaseInput v-model="veiculoEmEdicao.cor" required />
              </div>
            </div>
            <!-- Campo para placa -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Placa</label>
              <BaseInput
                v-model="veiculoEmEdicao.placa"
                placeholder="ABC-1234"
                maxlength="8"
                required
              />
            </div>
            <!-- Campo para data da próxima revisão -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data da Próxima Revisão</label>
              <BaseInput
                v-model="veiculoEmEdicao.proxima_revisao"
                type="date"
                :min="dataHoje"
                required
              />
            </div>
          </div>
          <!-- Botões do formulário -->
          <div class="mt-6 flex justify-end space-x-3">
            <BaseButton
              type="button"
              @click="fecharModal"
              class="bg-gray-200 hover:bg-gray-300 text-gray-800"
            >
              Cancelar
            </BaseButton>
            <BaseButton type="submit" class="bg-blue-600 hover:bg-blue-700">
              {{ veiculoEmEdicao.id ? "Atualizar" : "Cadastrar" }}
            </BaseButton>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal de confirmação de exclusão -->
    <div
      v-if="mostrarConfirmacao"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md">
        <div class="text-center">
          <AlertTriangleIcon class="mx-auto h-12 w-12 text-red-500" />
          <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">
            Confirmar exclusão
          </h3>
          <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Tem certeza que deseja excluir o veículo
            {{ veiculoParaExcluir?.marca }} {{ veiculoParaExcluir?.modelo }}?
            Esta ação não pode ser desfeita.
          </p>
        </div>
        <div class="mt-6 flex justify-center space-x-3">
          <BaseButton
            @click="cancelarExclusao"
            class="bg-gray-200 hover:bg-gray-300 text-gray-800"
          >
            Cancelar
          </BaseButton>
          <BaseButton
            @click="excluirVeiculo"
            class="bg-red-600 hover:bg-red-700"
          >
            Excluir
          </BaseButton>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";
import BaseButton from "../components/common/BaseButton.vue";
import BaseInput from "../components/common/BaseInput.vue";
import BaseSelect from "../components/common/BaseSelect.vue";
import {
  CarIcon,
  PlusIcon,
  SearchIcon,
  EditIcon,
  TrashIcon,
  XIcon,
  AlertTriangleIcon,
  WrenchIcon,
} from "lucide-vue-next";
import { useRouter } from "vue-router";

// Estado e lógica do componente
const router = useRouter();
const veiculos = ref([]);
const proprietarios = ref([]);
const filtros = ref({
  busca: "",
  marca: "",
  ano: "",
});
const veiculoEmEdicao = ref({
  marca: "",
  modelo: "",
  ano_fabricacao: new Date().getFullYear(),
  cor: "",
  placa: "",
  proxima_revisao: "",
  proprietarioId: "",
});
const mostrarModal = ref(false);
const mostrarConfirmacao = ref(false);
const veiculoParaExcluir = ref(null);
const anoAtual = new Date().getFullYear();
const dataHoje = new Date().toISOString().split("T")[0];

// Computed para filtrar veículos
const veiculosFiltrados = computed(() => {
  return veiculos.value.filter((veiculo) => {
    const matchBusca =
      filtros.value.busca === "" ||
      veiculo.marca?.toLowerCase().includes(filtros.value.busca.toLowerCase()) ||
      veiculo.modelo?.toLowerCase().includes(filtros.value.busca.toLowerCase()) ||
      veiculo.placa?.toLowerCase().includes(filtros.value.busca.toLowerCase()) ||
      (veiculo.pessoa?.nome || veiculo.proprietario_nome || "")
        .toLowerCase()
        .includes(filtros.value.busca.toLowerCase());

    const matchMarca =
      filtros.value.marca === "" || veiculo.marca === filtros.value.marca;
    const matchAno =
      filtros.value.ano === "" ||
      veiculo.ano_fabricacao?.toString() === filtros.value.ano;

    return matchBusca && matchMarca && matchAno;
  });
});

// Computed para opções de marcas
const opcoesMarcas = computed(() => {
  const marcas = [...new Set(veiculos.value.map((v) => v.marca))];
  return marcas.map((marca) => ({ label: marca, value: marca }));
});

// Computed para opções de anos
const opcoesAnos = computed(() => {
  const anos = veiculos.value.map((v) => v.ano_fabricacao);
  return [...new Set(anos)]
    .sort((a, b) => b - a)
    .map((ano) => ({ label: ano?.toString(), value: ano?.toString() }));
});

// Função para abrir o modal de novo veículo
function abrirModalNovoVeiculo() {
  veiculoEmEdicao.value = {
    marca: "",
    modelo: "",
    ano_fabricacao: new Date().getFullYear(),
    cor: "",
    placa: "",
    proxima_revisao: "",
    proprietarioId: "",
  };
  mostrarModal.value = true;
}

// Função para editar um veículo
function editarVeiculo(veiculo) {
  veiculoEmEdicao.value = {
    ...veiculo,
    proprietarioId: veiculo.pessoa_id || veiculo.proprietarioId,
  };
  mostrarModal.value = true;
}

// Função para fechar o modal
function fecharModal() {
  mostrarModal.value = false;
}

// Função para salvar um veículo
async function salvarVeiculo() {
  if (!veiculoEmEdicao.value.proprietarioId) {
    alert("Selecione um proprietário!");
    return;
  }
  const payload = {
    pessoa_id: veiculoEmEdicao.value.proprietarioId,
    placa: veiculoEmEdicao.value.placa,
    marca: veiculoEmEdicao.value.marca,
    modelo: veiculoEmEdicao.value.modelo,
    ano_fabricacao: veiculoEmEdicao.value.ano_fabricacao,
    cor: veiculoEmEdicao.value.cor,
    proxima_revisao: veiculoEmEdicao.value.proxima_revisao,
  };

  if (veiculoEmEdicao.value.id) {
    await axios.put(
      `http://localhost/api/veiculos/${veiculoEmEdicao.value.id}`,
      payload
    );
  } else {
    await axios.post("http://localhost/api/veiculos", payload);
  }

  const res = await axios.get("http://localhost/api/veiculos");
  veiculos.value = res.data;
  fecharModal();
}

// Função para confirmar exclusão de veículo
function confirmarExclusao(veiculo) {
  veiculoParaExcluir.value = veiculo;
  mostrarConfirmacao.value = true;
}

// Função para cancelar exclusão
function cancelarExclusao() {
  veiculoParaExcluir.value = null;
  mostrarConfirmacao.value = false;
}

// Função para excluir um veículo
async function excluirVeiculo() {
  if (veiculoParaExcluir.value) {
    await axios.delete(`http://localhost/api/veiculos/${veiculoParaExcluir.value.id}`);
    const res = await axios.get("http://localhost/api/veiculos");
    veiculos.value = res.data;
    cancelarExclusao();
  }
}

// Função para visualizar revisões de um veículo
function visualizarRevisoes(veiculo) {
  router.push({
    name: "Revisoes",
    query: { veiculoId: veiculo.id },
  });
}

// Função para formatar datas no formato brasileiro
function formatarData(data) {
  if (!data) return "";
  const partes = data.split("-");
  if (partes.length !== 3) return data;
  return `${partes[2]}/${partes[1]}/${partes[0]}`;
}

// Função para obter a classe CSS do status da revisão
function getStatusRevisaoClass(data) {
  if (!data) return "bg-gray-100 text-gray-800";
  const hoje = new Date();
  const dataRevisao = new Date(data);
  const diffDias = Math.floor((dataRevisao - hoje) / (1000 * 60 * 60 * 24));
  if (diffDias < 0) {
    return "bg-red-100 text-red-800";
  } else if (diffDias <= 15) {
    return "bg-yellow-100 text-yellow-800";
  } else {
    return "bg-green-100 text-green-800";
  }
}

// Quando o componente é carregado
onMounted(async () => {
  const resVeiculos = await axios.get("http://localhost/api/veiculos");
  veiculos.value = resVeiculos.data;
  const resProprietarios = await axios.get("http://localhost/api/pessoas");
  proprietarios.value = resProprietarios.data.map(p => ({
    label: p.nome,
    value: p.id
  }));
});
</script>
