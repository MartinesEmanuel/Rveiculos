import { createRouter, createWebHistory } from "vue-router";

// Importando as views
import Dashboard from "../views/Dashboard.vue";
import Pessoas from "../views/Pessoas.vue";
import Veiculos from "../views/Veiculos.vue";
import Revisoes from "../views/Revisoes.vue";
import Relatorios from "../views/Relatorios.vue";

const routes = [
  {
    path: "/",
    name: "Dashboard",
    component: Dashboard,
    meta: { icon: "chart-pie" },
  },
  {
    path: "/pessoas",
    name: "Pessoas",
    component: Pessoas,
    meta: { icon: "users" },
  },
  {
    path: "/veiculos",
    name: "Veículos",
    component: Veiculos,
    meta: { icon: "car" },
  },
  {
    path: "/revisoes",
    name: "Revisões",
    component: Revisoes,
    meta: { icon: "wrench" },
  },
  {
    path: "/relatorios",
    name: "Relatórios",
    component: Relatorios,
    meta: { icon: "chart-bar" },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
