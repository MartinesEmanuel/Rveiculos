import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";
import router from "./router";

// FontAwesome
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
  faUsers,
  faCar,
  faWrench,
  faChartBar,
  faChartPie,
  faPlus,
  faEdit,
  faTrash,
  faTachometerAlt,
  faSearch,
  faBars,
  faUserPlus,
  faCarSide,
  faCogs,
  faFilePdf,
  faCalendarCheck,
  faArrowRight,
  faCheck,
  faTimes,
} from "@fortawesome/free-solid-svg-icons";

// Adicione os ícones à biblioteca
library.add(
  faUsers,
  faCar,
  faWrench,
  faChartBar,
  faChartPie,
  faPlus,
  faEdit,
  faTrash,
  faTachometerAlt,
  faSearch,
  faBars,
  faUserPlus,
  faCarSide,
  faCogs,
  faFilePdf,
  faCalendarCheck,
  faArrowRight,
  faCheck,
  faTimes,
);

const app = createApp(App);

// Use os plugins
app.use(createPinia());
app.use(router);

// Componentes globais
app.component("font-awesome-icon", FontAwesomeIcon);

app.mount("#app");
