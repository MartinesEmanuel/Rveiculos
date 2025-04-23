<template>
  <div class="app-layout">
    <AppSidebar :collapsed="sidebarCollapsed" />
    <div
      class="main-content"
      :class="{ 'sidebar-expanded': !sidebarCollapsed }"
    >
      <AppHeader @toggle-sidebar="toggleSidebar" />
      <main>
        <router-view v-slot="{ Component }">
          <transition name="fade" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </main>
      <AppFooter />
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import AppSidebar from "./components/layout/AppSidebar.vue";
import AppHeader from "./components/layout/AppHeader.vue";
import AppFooter from "./components/layout/AppFooter.vue";

const sidebarCollapsed = ref(false);

const toggleSidebar = () => {
  sidebarCollapsed.value = !sidebarCollapsed.value;
};
</script>

<style>
@import "./assets/styles/main.css";
@import "animate.css";
</style>
