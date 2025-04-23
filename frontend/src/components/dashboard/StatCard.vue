import { WrenchIcon, FileTextIcon } from 'lucide-vue-next'
<template>
  <BaseCard class="stat-card">
    <div class="flex flex-col">
      <!-- Título -->
      <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">
        {{ titulo }}
      </h3>

      <!-- Valor e Ícone -->
      <div class="flex items-center mt-2">
        <div
          class="flex items-center justify-center w-12 h-12 rounded-full"
          :class="corIcone"
        >
          <component :is="icone" class="w-6 h-6 text-white" />
        </div>
        <div class="ml-4">
          <p class="text-3xl font-bold">{{ valor }}</p>
          <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ descricao }}
          </p>
        </div>
      </div>

      <!-- Tendência -->
      <div
        v-if="tendencia !== null"
        class="flex items-center mt-4 text-sm"
        :class="corTendencia"
      >
        <span v-if="tendencia > 0" class="mr-1">▲</span>
        <span v-else-if="tendencia < 0" class="mr-1">▼</span>
        <span>{{ Math.abs(tendencia) }}%</span>
        <span class="ml-1">em relação ao mês anterior</span>
      </div>
    </div>
  </BaseCard>
</template>

<script>
import BaseCard from "../common/BaseCard.vue";
import { CarIcon, UsersIcon, WrenchIcon, FileTextIcon } from "lucide-vue-next";

export default {
  name: "StatCard",
  components: {
    BaseCard,
    CarIcon,
    UsersIcon,
    WrenchIcon,
    FileTextIcon,
  },
  props: {
    titulo: {
      type: String,
      required: true,
    },
    valor: {
      type: [String, Number],
      required: true,
    },
    descricao: {
      type: String,
      default: "",
    },
    tipo: {
      type: String,
      default: "veiculo",
      validator: (value) =>
        ["veiculo", "pessoa", "revisao", "relatorio"].includes(value),
    },
    tendencia: {
      type: Number,
      default: null, // Tendência percentual (positivo ou negativo)
    },
  },
  computed: {
    icone() {
      const icones = {
        veiculo: CarIcon,
        pessoa: UsersIcon,
        revisao: WrenchIcon,
        relatorio: FileTextIcon,
      };
      return icones[this.tipo];
    },
    corIcone() {
      const cores = {
        veiculo: "bg-blue-500",
        pessoa: "bg-green-500",
        revisao: "bg-yellow-500",
        relatorio: "bg-purple-500",
      };
      return cores[this.tipo];
    },
    corTendencia() {
      if (this.tendencia > 0) {
        return "text-green-500";
      } else if (this.tendencia < 0) {
        return "text-red-500";
      }
      return "text-gray-500";
    },
  },
};
</script>

<style scoped>
.stat-card {
  transition: transform 0.2s, box-shadow 0.2s;
  cursor: pointer;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}
</style>
