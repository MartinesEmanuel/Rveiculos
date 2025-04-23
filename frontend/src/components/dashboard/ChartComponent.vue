<template>
  <div class="chart-container" v-if="chartData && chartData.datasets && chartData.labels">
    <component
      :is="chartComponent"
      :data="chartData"
      :options="computedOptions"
      :height="height"
      :id="chartId"
    />
  </div>
  <div v-else class="chart-error">
    Não há dados para exibir o gráfico.
  </div>
</template>

<script setup>
import { computed } from "vue";
import { Bar, Pie, Doughnut, Line } from "vue-chartjs";
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  ArcElement,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
} from "chart.js";

// Registrar componentes do Chart.js
ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  ArcElement,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement
);

const props = defineProps({
  type: { type: String, default: "bar" },
  data: { type: Object, required: true },
  options: { type: Object, default: () => ({}) },
  height: { type: Number, default: 300 },
  chartId: { type: String, default: () => `chart-${Math.random().toString(36).substring(2, 9)}` },
});

const chartComponent = computed(() => {
  switch (props.type) {
    case "bar":
      return Bar;
    case "pie":
      return Pie;
    case "doughnut":
      return Doughnut;
    case "line":
      return Line;
    default:
      return Bar;
  }
});

const defaultOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { labels: { color: "#ecf0f1" } },
    title: { display: false },
    tooltip: {
      backgroundColor: "#2a2a3c",
      titleColor: "#ecf0f1",
      bodyColor: "#ecf0f1",
      borderColor: "#3498db",
      borderWidth: 1,
    },
  },
  scales: {
    x: { ticks: { color: "#ecf0f1" }, grid: { color: "#23243a" } },
    y: { ticks: { color: "#ecf0f1" }, grid: { color: "#23243a" } },
  },
};

const computedOptions = computed(() => ({
  ...defaultOptions,
  ...props.options,
}));

const chartData = computed(() => {
  // Garante que os dados estejam no formato correto
  if (!props.data || !props.data.datasets || !props.data.labels) {
    return null;
  }
  return props.data;
});
</script>

<style scoped>
.chart-container {
  position: relative;
  width: 100%;
  min-height: 220px;
}
.chart-error {
  color: #f44336;
  text-align: center;
  padding: 2rem 0;
}
</style>
