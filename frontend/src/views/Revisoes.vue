<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Revisões</h1>
      <BaseButton
        @click="abrirModalNovaRevisao"
        class="bg-blue-600 hover:bg-blue-700"
      >
        <PlusIcon class="w-5 h-5 mr-2" />
        Nova Revisão
      </BaseButton>
    </div>

    <!-- Filtros e busca -->
    <div class="mb-6 flex flex-wrap gap-4">
      <div class="flex-1 min-w-[200px]">
        <BaseInput
          v-model="filtros.busca"
          placeholder="Buscar por veículo, serviço..."
          type="search"
        >
          <template #prepend>
            <SearchIcon class="w-5 h-5 text-gray-400" />
          </template>
        </BaseInput>
      </div>
      <BaseSelect
        v-model="filtros.veiculo"
        :options="opcoesVeiculos"
        placeholder="Todos os veículos"
        class="w-48"
      />
      <BaseSelect
        v-model="filtros.status"
        :options="opcoesStatus"
        placeholder="Todos os status"
        class="w-40"
      />
    </div>

    <!-- Lista de revisões em formato de cards -->
    <div v-if="revisoesFiltradas.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="revisao in revisoesFiltradas"
        :key="revisao.id"
        class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col justify-between"
      >
        <div>
          <div class="flex items-center mb-2">
            <CarIcon class="w-6 h-6 text-blue-500 mr-2" />
            <span class="font-semibold text-lg">{{ revisao.veiculoLabel }}</span>
          </div>
          <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-600 dark:text-gray-300 mb-2">
            <span><b>Placa:</b> {{ revisao.placa }}</span>
            <span><b>Ano:</b> {{ revisao.ano }}</span>
            <span><b>Proprietário:</b> {{ revisao.proprietario }}</span>
          </div>
          <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-600 dark:text-gray-300 mb-2">
            <span><b>Próxima Revisão:</b> {{ formatarData(revisao.proximaRevisao) }}</span>
            <span>
              <b>Status:</b>
              <span :class="['px-2 py-1 rounded-full text-xs font-semibold', getStatusClass(revisao.status)]">
                {{ revisao.status.charAt(0).toUpperCase() + revisao.status.slice(1) }}
              </span>
            </span>
          </div>
          <div class="mb-2">
            <b>Serviços:</b>
            <ul class="list-disc list-inside ml-4 mt-1">
              <li v-for="(servico, idx) in revisao.servicos" :key="idx">{{ servico }}</li>
            </ul>
          </div>
          <div v-if="revisao.observacoes" class="mb-2">
            <b>Observações:</b>
            <span class="block text-gray-500 dark:text-gray-400">{{ revisao.observacoes }}</span>
          </div>
        </div>
        <div class="flex gap-2 mt-4 justify-end">
          <button class="btn btn-sm btn-outline" @click="editarRevisao(revisao)" title="Editar">
            <EditIcon class="w-4 h-4" />
          </button>
          <button
            class="btn btn-sm btn-success"
            v-if="revisao.status === 'Agendada'"
            @click="concluirRevisao(revisao)"
            title="Concluir"
          >
            <CheckIcon class="w-4 h-4" />
          </button>
          <button class="btn btn-sm btn-danger" @click="confirmarExclusao(revisao)" title="Excluir">
            <TrashIcon class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>

    <!-- Mensagem quando não há revisões -->
    <div
      v-else
      class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center flex flex-col items-center"
    >
      <WrenchIcon class="w-16 h-16 text-gray-400 mb-4" />
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
        Nenhuma revisão encontrada
      </h3>
      <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
        {{
          revisoes.length === 0
            ? "Ainda não há revisões cadastradas."
            : "Nenhuma revisão corresponde aos filtros aplicados."
        }}
      </p>
      <BaseButton
        v-if="revisoes.length === 0"
        @click="abrirModalNovaRevisao"
        class="bg-blue-600 hover:bg-blue-700"
      >
        Agendar a primeira revisão
      </BaseButton>
    </div>

    <!-- Modal para adicionar/editar revisão -->
    <div
      v-if="mostrarModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6"
      >
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">
            {{ revisaoEmEdicao.id ? "Editar" : "Nova" }} Revisão
          </h3>
          <button
            @click="fecharModal"
            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
          >
            <XIcon class="w-5 h-5" />
          </button>
        </div>

        <form @submit.prevent="salvarRevisao">
          <div class="space-y-4">
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                >Veículo</label
              >
              <BaseSelect
                v-model="revisaoEmEdicao.veiculoId"
                :options="veiculosList"
                placeholder="Selecione o veículo"
                class="w-full"
                required
                @change="atualizarDadosVeiculo"
              />
            </div>

            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                >Data Prevista</label
              >
              <BaseInput
                v-model="revisaoEmEdicao.dataPrevista"
                type="date"
                :min="dataHoje"
                required
              />
            </div>

            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                >Status</label
              >
              <BaseSelect
                v-model="revisaoEmEdicao.status"
                :options="opcoesStatus"
                placeholder="Selecione o status"
                class="w-full"
                required
              />
            </div>

            <div v-if="revisaoEmEdicao.status === 'Concluída'">
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                >Data Realizada</label
              >
              <BaseInput
                v-model="revisaoEmEdicao.dataRealizada"
                type="date"
                :max="dataHoje"
                required
              />
            </div>

            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                >Serviços</label
              >

              <div class="space-y-2">
                <div
                  v-for="(servico, index) in revisaoEmEdicao.servicos"
                  :key="index"
                  class="flex items-center"
                >
                  <BaseInput
                    v-model="revisaoEmEdicao.servicos[index]"
                    placeholder="Descrição do serviço"
                    class="flex-grow"
                  />
                  <button
                    type="button"
                    @click="removerServico(index)"
                    class="ml-2 text-red-500 hover:text-red-700"
                  >
                    <XIcon class="w-5 h-5" />
                  </button>
                </div>
              </div>

              <button
                type="button"
                @click="adicionarServico"
                class="mt-2 text-sm flex items-center text-blue-600 hover:text-blue-800"
              >
                <PlusIcon class="w-4 h-4 mr-1" /> Adicionar serviço
              </button>
            </div>

            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                >Observações</label
              >
              <textarea
                v-model="revisaoEmEdicao.observacoes"
                rows="3"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
              ></textarea>
            </div>
          </div>

          <div class="mt-6 flex justify-end space-x-3">
            <BaseButton
              type="button"
              @click="fecharModal"
              class="bg-gray-200 hover:bg-gray-300 text-gray-800"
            >
              Cancelar
            </BaseButton>
            <BaseButton type="submit" class="bg-blue-600 hover:bg-blue-700">
              {{ revisaoEmEdicao.id ? "Atualizar" : "Agendar" }}
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
      <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md"
      >
        <div class="text-center">
          <AlertTriangleIcon class="mx-auto h-12 w-12 text-red-500" />
          <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">
            Confirmar exclusão
          </h3>
          <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Tem certeza que deseja excluir a revisão do veículo
            {{ revisaoParaExcluir?.veiculo }}? Esta ação não pode ser desfeita.
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
            @click="excluirRevisao"
            class="bg-red-600 hover:bg-red-700"
          >
            Excluir
          </BaseButton>
        </div>
      </div>
    </div>

    <!-- Modal de conclusão de revisão -->
    <div
      v-if="mostrarModalConclusao"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">Concluir Revisão</h3>
          <button
            @click="fecharModalConclusao"
            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
          >
            <XIcon class="w-5 h-5" />
          </button>
        </div>

        <!-- Dados do veículo -->
        <div class="mb-4 p-3 rounded bg-gray-100 dark:bg-gray-700 flex flex-col gap-1">
          <div class="font-semibold text-base text-blue-700 dark:text-blue-300">
            {{ revisaoParaConcluir?.veiculo?.marca || revisaoParaConcluir?.veiculo?.modelo || revisaoParaConcluir?.veiculo || '' }}
            <span v-if="revisaoParaConcluir?.veiculo?.ano_fabricacao">
              ({{ revisaoParaConcluir?.veiculo?.ano_fabricacao }})
            </span>
          </div>
          <div class="text-sm text-gray-700 dark:text-gray-200">
            <b>Placa:</b> {{ revisaoParaConcluir?.veiculo?.placa || revisaoParaConcluir?.placa }}
            <span class="ml-4"><b>Cor:</b> {{ revisaoParaConcluir?.veiculo?.cor }}</span>
          </div>
          <div class="text-sm text-gray-700 dark:text-gray-200">
            <b>Proprietário:</b> {{ revisaoParaConcluir?.proprietario || revisaoParaConcluir?.veiculo?.proprietario_nome }}
          </div>
        </div>

        <form @submit.prevent="finalizarRevisao">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Data de Conclusão
              </label>
              <BaseInput
                v-model="dataRealizacao"
                type="date"
                :max="dataHoje"
                required
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Serviços Realizados
              </label>
              <div class="space-y-2">
                <div
                  v-for="(servico, index) in servicosRealizados"
                  :key="index"
                  class="flex items-center gap-2"
                >
                  <input
                    type="checkbox"
                    :id="`servico-${index}`"
                    v-model="servicosRealizados[index].realizado"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  />
                  <label
                    :for="`servico-${index}`"
                    class="text-sm text-gray-900 dark:text-white"
                  >
                    {{ servico.descricao }}
                  </label>
                </div>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Observações
              </label>
              <textarea
                v-model="observacoesConclusao"
                rows="3"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Data da Próxima Revisão
              </label>
              <BaseInput
                v-model="proximaRevisao"
                type="date"
                :min="dataHoje"
                required
              />
            </div>
          </div>

          <div class="mt-6 flex flex-col sm:flex-row justify-end gap-2">
            <BaseButton
              type="button"
              @click="fecharModalConclusao"
              class="bg-gray-200 hover:bg-gray-300 text-gray-800"
            >
              Fechar
            </BaseButton>
            <BaseButton
              type="button"
              @click="cancelarRevisaoConclusao"
              class="bg-red-600 hover:bg-red-700 text-white"
            >
              Cancelar Revisão
            </BaseButton>
            <BaseButton type="submit" class="bg-green-600 hover:bg-green-700 text-white">
              Finalizar Revisão
            </BaseButton>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
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
  CheckIcon,
  WrenchIcon,
} from "lucide-vue-next";
import {
  Edit,
  Trash,
  Check,
  Plus,
  Search,
  X,
  AlertTriangle
} from "lucide-vue-next";
import axios from "axios";

export default {
  name: "Revisoes",
  components: {
    BaseButton,
    BaseInput,
    BaseSelect,
    CarIcon,
    PlusIcon,
    SearchIcon,
    EditIcon,
    TrashIcon,
    XIcon,
    AlertTriangleIcon,
    CheckIcon,
    WrenchIcon,
  },
  setup() {
    const route = useRoute();
    const router = useRouter();

    const revisoes = ref([
      {
        id: 1,
        veiculoId: 1,
        veiculo: "Toyota Corolla (2021)",
        placa: "ABC-1234",
        proprietario: "João Silva",
        dataPrevista: "2025-04-28",
        dataRealizada: null,
        status: "Agendada",
        servicos: [
          "Troca de óleo",
          "Verificação dos freios",
          "Alinhamento e balanceamento",
        ],
        observacoes: "Cliente solicitou revisão dos amortecedores também.",
      },
      {
        id: 2,
        veiculoId: 2,
        veiculo: "Honda Civic (2020)",
        placa: "DEF-5678",
        proprietario: "Maria Oliveira",
        dataPrevista: "2025-05-05",
        dataRealizada: null,
        status: "Agendada",
        servicos: ["Troca de filtros", "Verificação do sistema elétrico"],
        observacoes: "",
      },
      {
        id: 3,
        veiculoId: 3,
        veiculo: "Volkswagen Golf (2022)",
        placa: "GHI-9012",
        proprietario: "Emanuel Santos",
        dataPrevista: "2025-05-15",
        dataRealizada: null,
        status: "Agendada",
        servicos: ["Revisão completa", "Troca das pastilhas de freio"],
        observacoes: "Veículo com ruído no eixo dianteiro.",
      },
      {
        id: 4,
        veiculoId: 1,
        veiculo: "Toyota Corolla (2021)",
        placa: "ABC-1234",
        proprietario: "João Silva",
        dataPrevista: "2024-10-12",
        dataRealizada: "2024-10-15",
        status: "Concluída",
        servicos: [
          "Troca de óleo",
          "Substituição do filtro de ar",
          "Verificação do sistema de arrefecimento",
        ],
        observacoes: "Foi necessário trocar também a correia do alternador.",
      },
    ]);

    const veiculosList = ref([
      {
        id: 1,
        nome: "Toyota Corolla (2021)",
        placa: "ABC-1234",
        proprietario: "João Silva",
      },
      {
        id: 2,
        nome: "Honda Civic (2020)",
        placa: "DEF-5678",
        proprietario: "Maria Oliveira",
      },
      {
        id: 3,
        nome: "Volkswagen Golf (2022)",
        placa: "GHI-9012",
        proprietario: "Emanuel Santos",
      },
    ]);

    const pessoasList = ref([]);

    const filtros = ref({
      busca: "",
      veiculo: "",
      status: "",
    });

    const revisaoEmEdicao = ref({
      veiculoId: "",
      veiculo: "",
      placa: "",
      proprietario: "",
      dataPrevista: "",
      dataRealizada: null,
      status: "Agendada",
      servicos: [""],
      observacoes: "",
    });

    const mostrarModal = ref(false);
    const mostrarConfirmacao = ref(false);
    const mostrarModalConclusao = ref(false);
    const revisaoParaExcluir = ref(null);
    const revisaoParaConcluir = ref(null);
    const dataRealizacao = ref("");
    const proximaRevisao = ref("");
    const observacoesConclusao = ref("");
    const servicosRealizados = ref([]);

    const dataHoje = new Date().toISOString().split("T")[0];

    const revisoesFiltradas = computed(() => {
      return revisoes.value.filter((revisao) => {
        const matchBusca =
          filtros.value.busca === "" ||
          revisao.veiculo
            ?.toLowerCase()
            .includes(filtros.value.busca.toLowerCase()) ||
          revisao.placa
            ?.toLowerCase()
            .includes(filtros.value.busca.toLowerCase()) ||
          revisao.proprietario
            ?.toLowerCase()
            .includes(filtros.value.busca.toLowerCase()) ||
          revisao.servicos?.some((s) =>
            s.toLowerCase().includes(filtros.value.busca.toLowerCase()),
          );

        const matchVeiculo =
          filtros.value.veiculo === "" ||
          revisao.veiculoId?.toString() === filtros.value.veiculo;
        const matchStatus =
          filtros.value.status === "" ||
          revisao.status === filtros.value.status;

        return matchBusca && matchVeiculo && matchStatus;
      });
    });

    const opcoesVeiculos = computed(() => {
      return veiculosList.value.map((v) => ({
        label: v.nome,
        value: v.id.toString(),
      }));
    });

    const opcoesStatus = [
      { label: "Agendada", value: "Agendada" },
      { label: "Concluída", value: "Concluída" },
      { label: "Cancelada", value: "Cancelada" },
    ];

    function abrirModalNovaRevisao() {
      revisaoEmEdicao.value = {
        veiculoId: "",
        veiculo: "",
        placa: "",
        proprietario: "",
        dataPrevista: "",
        dataRealizada: null,
        status: "Agendada",
        servicos: [""],
        observacoes: "",
      };

      const veiculoId = route.query.veiculoId;
      if (veiculoId) {
        revisaoEmEdicao.value.veiculoId = parseInt(veiculoId);
        atualizarDadosVeiculo();
      }

      mostrarModal.value = true;
    }

    function editarRevisao(revisao) {
      revisaoEmEdicao.value = { ...revisao };
      mostrarModal.value = true;
    }

    function fecharModal() {
      mostrarModal.value = false;
    }

    function atualizarDadosVeiculo() {
      const veiculo = veiculosList.value.find(
        (v) => v.id === revisaoEmEdicao.value.veiculoId,
      );
      if (veiculo) {
        revisaoEmEdicao.value.veiculo = veiculo.nome;
        revisaoEmEdicao.value.placa = veiculo.placa;
        revisaoEmEdicao.value.proprietario = veiculo.proprietario;
      }
    }

    function adicionarServico() {
      revisaoEmEdicao.value.servicos.push("");
    }

    function removerServico(index) {
      revisaoEmEdicao.value.servicos = revisaoEmEdicao.value.servicos.filter(
        (_, i) => i !== index,
      );
      if (revisaoEmEdicao.value.servicos.length === 0) {
        revisaoEmEdicao.value.servicos.push("");
      }
    }

    async function salvarRevisao() {
      revisaoEmEdicao.value.servicos = revisaoEmEdicao.value.servicos.filter(
        (s) => s.trim() !== "",
      );
      if (revisaoEmEdicao.value.servicos.length === 0) {
        revisaoEmEdicao.value.servicos.push("Revisão padrão");
      }

      try {
        if (revisaoEmEdicao.value.id) {
          // Atualizar
          await axios.put(`http://localhost/api/revisoes/${revisaoEmEdicao.value.id}`, revisaoEmEdicao.value);
        } else {
          // Criar
          const payload = {
            veiculo_id: revisaoEmEdicao.value.veiculoId || revisaoEmEdicao.value.veiculo_id,
            data_prevista: revisaoEmEdicao.value.dataPrevista || revisaoEmEdicao.value.data_prevista,
            status: revisaoEmEdicao.value.status,
            servicos: revisaoEmEdicao.value.servicos,
            observacoes: revisaoEmEdicao.value.observacoes,
          };
          await axios.post("http://localhost/api/revisoes", payload);
        }

        // Atualize a lista após salvar
        const res = await axios.get("http://localhost/api/revisoes");
        revisoes.value = res.data;
        fecharModal();
      } catch (e) {
        if (e.response && e.response.data && e.response.data.errors) {
          alert(Object.values(e.response.data.errors).flat().join('\n'));
        } else {
          alert("Erro ao agendar revisão!");
        }
        console.error(e);
      }
    }

    function confirmarExclusao(revisao) {
      revisaoParaExcluir.value = revisao;
      mostrarConfirmacao.value = true;
    }

    function cancelarExclusao() {
      revisaoParaExcluir.value = null;
      mostrarConfirmacao.value = false;
    }

    async function excluirRevisao() {
      if (!revisaoParaExcluir.value) return;
      try {
        await axios.delete(`http://localhost/api/revisoes/${revisaoParaExcluir.value.id}`);
        // Atualize a lista após excluir
        const res = await axios.get("http://localhost/api/revisoes");
        revisoes.value = res.data;
        cancelarExclusao();
      } catch (e) {
        alert("Erro ao excluir revisão!");
        console.error(e);
      }
    }

    function concluirRevisao(revisao) {
      if (!revisao) return;
      revisaoParaConcluir.value = revisao;
      dataRealizacao.value = dataHoje;

      const dataAtual = new Date();
      dataAtual.setMonth(dataAtual.getMonth() + 6);
      proximaRevisao.value = dataAtual.toISOString().split("T")[0];

      servicosRealizados.value =
        revisao.servicos?.map((s) => ({
          descricao: s,
          realizado: true,
        })) || [];

      observacoesConclusao.value = revisao.observacoes || "";
      mostrarModalConclusao.value = true;
    }

    function fecharModalConclusao() {
      revisaoParaConcluir.value = null;
      mostrarModalConclusao.value = false;
    }

    async function finalizarRevisao() {
      if (revisaoParaConcluir.value) {
        try {
          // Atualiza a revisão atual para "Concluída" no backend
          await axios.put(`http://localhost/api/revisoes/${revisaoParaConcluir.value.id}`, {
            ...revisaoParaConcluir.value,
            status: "Concluída",
            dataRealizada: dataRealizacao.value,
            observacoes: observacoesConclusao.value,
            servicos: servicosRealizados.value
              .filter((s) => s.realizado)
              .map((s) => s.descricao),
          });

          // Atualiza a lista após salvar
          const res = await axios.get("http://localhost/api/revisoes");
          revisoes.value = res.data;

          fecharModalConclusao();
        } catch (e) {
          alert("Erro ao concluir revisão!");
          console.error(e);
        }
      }
    }

    async function cancelarRevisaoConclusao() {
      if (revisaoParaConcluir.value) {
        try {
          await axios.put(`http://localhost/api/revisoes/${revisaoParaConcluir.value.id}`, {
            ...revisaoParaConcluir.value,
            status: "Cancelada",
            dataRealizada: null,
            observacoes: observacoesConclusao.value,
            servicos: servicosRealizados.value
              .filter((s) => s.realizado)
              .map((s) => s.descricao),
          });
          // Atualiza a lista após salvar
          const res = await axios.get("http://localhost/api/revisoes");
          revisoes.value = res.data;
          fecharModalConclusao();
        } catch (e) {
          alert("Erro ao cancelar revisão!");
          console.error(e);
        }
      }
    }

    function formatarData(data) {
      if (!data || typeof data !== "string") return "";
      const partes = data.split("-");
      if (partes.length !== 3) return data;
      return `${partes[2]}/${partes[1]}/${partes[0]}`;
    }

    function getStatusClass(status) {
      const classes = {
        Agendada: "bg-blue-100 text-blue-800",
        Concluída: "bg-green-100 text-green-800",
        Cancelada: "bg-red-100 text-red-800",
      };

      return classes[status] || "bg-gray-100 text-gray-800";
    }

    onMounted(async () => {
      // Buscar pessoas primeiro
      const resPessoas = await axios.get("http://localhost/api/pessoas");
      pessoasList.value = resPessoas.data;

      // Buscar revisões depois
      const resRevisoes = await axios.get("http://localhost/api/revisoes");
      revisoes.value = resRevisoes.data.map(r => {
        // Tenta pegar o proprietário do veículo, se não, busca na lista de pessoas
        let proprietario = r.veiculo?.proprietario_nome || r.proprietario || "";
        if (!proprietario && r.veiculo?.pessoa_id && pessoasList.value.length > 0) {
          const pessoa = pessoasList.value.find(p => p.id === r.veiculo.pessoa_id);
          proprietario = pessoa ? pessoa.nome : "";
        }
        return {
          ...r,
          veiculoLabel: r.veiculo?.marca
            ? `${r.veiculo.marca} ${r.veiculo.modelo} (${r.veiculo.ano_fabricacao})`
            : r.veiculo?.modelo || r.modelo || "",
          placa: r.veiculo?.placa || r.placa || "",
          ano: r.veiculo?.ano_fabricacao || r.ano || "",
          proprietario,
          proximaRevisao: r.proxima_revisao || r.dataPrevista || r.data_prevista || "",
        };
      });

      // Buscar veículos
      const resVeiculos = await axios.get("http://localhost/api/veiculos");
      veiculosList.value = resVeiculos.data.map(v => ({
        label: `${v.marca} ${v.modelo} (${v.ano_fabricacao})`,
        value: v.id,
        placa: v.placa,
        proprietario: v.proprietario_nome
      }));

      // Filtro por veículo da URL (opcional)
      const veiculoId = route.query.veiculoId;
      if (veiculoId) {
        filtros.value.veiculo = veiculoId.toString();
      }
    });

    return {
      revisoes,
      veiculosList,
      pessoasList,
      filtros,
      revisoesFiltradas,
      opcoesVeiculos,
      opcoesStatus,
      revisaoEmEdicao,
      mostrarModal,
      mostrarConfirmacao,
      mostrarModalConclusao,
      revisaoParaExcluir,
      revisaoParaConcluir,
      dataRealizacao,
      proximaRevisao,
      observacoesConclusao,
      servicosRealizados,
      dataHoje,
      abrirModalNovaRevisao,
      editarRevisao,
      fecharModal,
      atualizarDadosVeiculo,
      adicionarServico,
      removerServico,
      salvarRevisao,
      confirmarExclusao,
      cancelarExclusao,
      excluirRevisao,
      concluirRevisao,
      fecharModalConclusao,
      finalizarRevisao,
      cancelarRevisaoConclusao,
      formatarData,
      getStatusClass,
    };
  },
};
</script>
