<template>
  <div class="pessoas-page">
    <!-- Cabeçalho da página -->
    <div class="page-header flex justify-between items-center mb-4">
      <h1 class="page-title">
        <!-- Ícone e título -->
        <font-awesome-icon icon="users" /> Proprietários
      </h1>
      <!-- Botão para abrir o modal de cadastro -->
      <button class="btn btn-primary" @click="showModal = true">
        <font-awesome-icon icon="user-plus" /> Novo Proprietário
      </button>
    </div>

    <!-- Filtros para busca e ordenação -->
    <BaseCard class="mb-4">
      <div class="flex gap-4">
        <!-- Campo de busca -->
        <BaseInput
          v-model="filters.search"
          placeholder="Buscar por nome..."
          icon="search"
          class="flex-1"
        />
        <!-- Filtro por gênero -->
        <BaseSelect
          v-model="filters.genero"
          :options="generoOptions"
          placeholder="Todos os gêneros"
          class="w-200"
        />
        <!-- Filtro por ordenação -->
        <BaseSelect
          v-model="filters.ordenacao"
          :options="ordenacaoOptions"
          placeholder="Ordenar por"
          class="w-200"
        />
      </div>
    </BaseCard>

    <!-- Lista de pessoas -->
    <BaseCard>
      <table class="table">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Idade</th>
            <th>Gênero</th>
            <th>Veículos</th>
            <th>Última Visita</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <!-- Exibe cada pessoa na tabela -->
          <tr v-for="pessoa in pessoas" :key="pessoa.id">
            <td>
              <div class="flex items-center gap-2">
                <!-- Avatar com as iniciais -->
                <div class="avatar">{{ pessoa.iniciais }}</div>
                <div>{{ pessoa.nome }}</div>
              </div>
            </td>
            <td>{{ pessoa.idade }}</td>
            <td>{{ pessoa.genero }}</td>
            <td>
              <div class="flex items-center gap-2">
                <!-- Exibe os veículos da pessoa -->
                <span
                  v-if="Array.isArray(pessoa.veiculos) && pessoa.veiculos.length"
                  class="badge badge-primary"
                >
                  {{ pessoa.veiculos.map(v => `${v.marca} ${v.modelo}`).join(', ') }}
                </span>
                <!-- Caso não tenha veículos -->
                <span v-else class="text-gray-400">
                  Nenhum veículo
                </span>
              </div>
            </td>
            <td>{{ pessoa.ultimaVisita }}</td>
            <td>
              <div class="flex gap-2">
                <!-- Botão para editar a pessoa -->
                <button
                  class="btn btn-sm btn-outline"
                  @click="editarPessoa(pessoa.id)"
                >
                  <font-awesome-icon icon="edit" />
                </button>
                <!-- Botão para excluir a pessoa -->
                <button
                  class="btn btn-sm btn-danger"
                  @click="excluirPessoa(pessoa.id)"
                >
                  <font-awesome-icon icon="trash" />
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Paginação -->
      <div class="pagination flex justify-center mt-4">
        <!-- Botão para página anterior -->
        <button class="btn btn-sm btn-outline" :disabled="currentPage === 1">
          Anterior
        </button>
        <!-- Exibe a página atual -->
        <span class="mx-4 flex items-center">
          Página {{ currentPage }} de {{ totalPages }}
        </span>
        <!-- Botão para próxima página -->
        <button
          class="btn btn-sm btn-outline"
          :disabled="currentPage === totalPages"
        >
          Próxima
        </button>
      </div>
    </BaseCard>

    <!-- Modal de Cadastro -->
    <div class="modal" v-if="showModal">
      <div class="modal-backdrop" @click="showModal = false"></div>
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Novo Proprietário</h2>
          <button class="modal-close" @click="showModal = false">×</button>
        </div>
        <div class="modal-body">
          <!-- Campos do formulário -->
          <BaseInput
            v-model="formData.nome"
            label="Nome"
            placeholder="Digite o nome completo"
            required
          />
          <BaseInput
            v-model="formData.idade"
            label="Idade"
            placeholder="Digite a idade"
            type="number"
            required
          />
          <BaseSelect
            v-model="formData.genero"
            label="Gênero"
            :options="generoOptions"
            required
          />
          <BaseInput
            v-model="formData.telefone"
            label="Telefone"
            placeholder="(XX) XXXXX-XXXX"
          />
          <BaseInput
            v-model="formData.email"
            label="Email"
            placeholder="email@exemplo.com"
            type="email"
          />
        </div>
        <div class="modal-footer">
          <!-- Botão para cancelar -->
          <button class="btn btn-outline" @click="showModal = false">
            Cancelar
          </button>
          <!-- Botão para salvar -->
          <button class="btn btn-primary" @click="salvarPessoa">Salvar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import BaseCard from "../components/common/BaseCard.vue";
import BaseInput from "../components/common/BaseInput.vue";
import BaseSelect from "../components/common/BaseSelect.vue";

// Estado para o modal de cadastro
const showModal = ref(false);

// Estado para os filtros
const filters = ref({
  search: "",
  genero: "",
  ordenacao: "",
});

// Opções para os selects
const generoOptions = [
  { value: "masculino", label: "Masculino" },
  { value: "feminino", label: "Feminino" },
  { value: "outro", label: "Outro" },
];

const ordenacaoOptions = [
  { value: "nome", label: "Nome (A-Z)" },
  { value: "nome_desc", label: "Nome (Z-A)" },
  { value: "idade", label: "Idade (crescente)" },
  { value: "idade_desc", label: "Idade (decrescente)" },
];

// Dados do formulário
const formData = ref({
  nome: "",
  idade: "",
  genero: "",
  telefone: "",
  email: "",
});

// Lista de pessoas e veículos
const pessoas = ref([]);
const veiculos = ref([]);

// Quando o componente é carregado
onMounted(async () => {
  // Busca pessoas e veículos da API
  const [resPessoas, resVeiculos] = await Promise.all([
    axios.get("http://localhost/api/pessoas"),
    axios.get("http://localhost/api/veiculos"),
  ]);
  pessoas.value = resPessoas.data;
  veiculos.value = resVeiculos.data;

  // Relaciona veículos a cada pessoa
  pessoas.value.forEach(pessoa => {
    pessoa.veiculos = veiculos.value.filter(v => v.pessoa_id === pessoa.id);
  });
});

// Navegação entre páginas
const router = useRouter();

// Função para salvar uma pessoa
const salvarPessoa = async () => {
  try {
    console.log("Tentando salvar pessoa:", formData.value);
    await axios.post("http://localhost/api/pessoas", formData.value);
    const res = await axios.get("http://localhost/api/pessoas");
    pessoas.value = res.data;
    showModal.value = false;
    formData.value = { nome: "", idade: "", genero: "", telefone: "", email: "" };
  } catch (e) {
    alert("Erro ao salvar proprietário!");
    console.error(e);
  }
};

// Função para excluir uma pessoa
const excluirPessoa = async (id) => {
  if (confirm("Tem certeza que deseja excluir este proprietário?")) {
    await axios.delete(`http://localhost/api/pessoas/${id}`);
    pessoas.value = pessoas.value.filter((pessoa) => pessoa.id !== id);
  }
};

// Função para editar uma pessoa
const editarPessoa = (id) => {
  const pessoa = pessoas.value.find((pessoa) => pessoa.id === id);
  if (pessoa) {
    formData.value = { ...pessoa };
    showModal.value = true;
  }
};
</script>

<style scoped>
/* Estilos para largura fixa */
.w-200 {
  width: 200px;
}

/* Estilos para o modal */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 100;
}

.modal-backdrop {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
  background-color: var(--background-light);
  border-radius: 8px;
  width: 100%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
  z-index: 101;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
  animation: modal-in 0.3s ease-out;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.25rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.modal-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0;
}

.modal-close {
  background: none;
  border: none;
  color: var(--gray-light);
  font-size: 1.5rem;
  cursor: pointer;
}

.modal-body {
  padding: 1.5rem;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  padding: 1rem 1.5rem;
}
</style>
