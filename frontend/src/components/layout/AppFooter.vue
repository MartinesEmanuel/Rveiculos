<template>
  <footer class="footer">
    <div class="container">
      <p>
        &copy; {{ currentYear }} Sistema de Revisões de Veículos | Todos os
        direitos reservados
      </p>
    </div>
  </footer>
</template>

<script setup>
import { computed } from "vue";

const currentYear = computed(() => new Date().getFullYear());
</script>

<style scoped>
.footer {
  margin-top: 2rem;
  padding: 1rem 0;
  text-align: center;
  color: var(--gray-light);
  font-size: 0.875rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}
</style>
