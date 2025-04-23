<template>
  <aside class="sidebar" :class="{ 'sidebar-collapsed': collapsed }">
    <div class="sidebar-header">
      <div class="logo">
        <font-awesome-icon icon="wrench" class="logo-icon" />
        <span class="logo-text" v-if="!collapsed">AutoRevisão</span>
      </div>
    </div>
    <div class="sidebar-content">
      <nav class="sidebar-nav">
        <router-link
          v-for="route in routes"
          :key="route.path"
          :to="route.path"
          class="nav-link"
          :class="{ active: $route.path === route.path }"
        >
          <font-awesome-icon :icon="route.meta.icon" class="nav-icon" />
          <span class="nav-text" v-if="!collapsed">{{ route.name }}</span>
        </router-link>
      </nav>
    </div>
    <div class="sidebar-footer">
      <div class="theme-toggle" v-if="!collapsed">
        <span>Tema Escuro</span>
        <button class="toggle-btn">
          <span class="toggle-slider"></span>
        </button>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { useRouter } from "vue-router";

const props = defineProps({
  collapsed: {
    type: Boolean,
    default: false,
  },
});

const router = useRouter();
const routes = router.options.routes;
</script>

<style scoped>
.sidebar {
  background-color: var(--background-light);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  transition: width var(--transition-speed);
  width: 250px;
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
  z-index: 10;
}

.sidebar-collapsed {
  width: 70px;
}

.sidebar-header {
  padding: 1.5rem 1rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.logo {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-weight: 700;
  font-size: 1.25rem;
}

.logo-icon {
  font-size: 1.5rem;
  color: var(--primary-color);
}

.sidebar-content {
  flex: 1;
  padding: 1rem 0;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 1rem;
  color: var(--text-light);
  text-decoration: none;
  gap: 0.75rem;
  transition: background-color var(--transition-speed);
  border-left: 3px solid transparent;
}

.nav-link:hover {
  background-color: rgba(52, 152, 219, 0.1);
}

.nav-link.active {
  background-color: rgba(52, 152, 219, 0.15);
  border-left-color: var(--primary-color);
}

.nav-icon {
  font-size: 1.25rem;
  min-width: 20px;
  text-align: center;
}

.sidebar-footer {
  padding: 1rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.theme-toggle {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.toggle-btn {
  position: relative;
  width: 40px;
  height: 20px;
  background-color: var(--background-dark);
  border-radius: 20px;
  cursor: pointer;
  padding: 0;
  border: none;
}

.toggle-slider {
  position: absolute;
  top: 2px;
  left: 2px;
  width: 16px;
  height: 16px;
  background-color: var(--primary-color);
  border-radius: 50%;
  transition: transform var(--transition-speed);
}

.toggle-btn.active .toggle-slider {
  transform: translateX(20px);
}

@media (max-width: 768px) {
  .sidebar {
    position: fixed;
    left: -250px;
  }

  .sidebar.open {
    left: 0;
  }
}
</style>
