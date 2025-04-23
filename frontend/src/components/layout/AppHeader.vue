<template>
  <header class="header">
    <div class="header-left">
      <button class="btn-icon" @click="$emit('toggle-sidebar')">
        <font-awesome-icon icon="bars" />
      </button>
      <h1 class="app-title">Sistema de Revisões de Veículos</h1>
    </div>
    <div class="header-right">
      <div class="search-box">
        <font-awesome-icon icon="search" class="search-icon" />
        <input type="text" placeholder="Pesquisar..." class="search-input" />
      </div>
      <div class="user-menu">
        <div class="user-avatar">AD</div>
        <span class="user-name">Admin</span>
      </div>
    </div>
  </header>
</template>

<script setup>
// Emite o evento toggle-sidebar para o componente pai
</script>

<style scoped>
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background-color: var(--background-light);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  margin-bottom: 2rem;
  border-radius: 8px;
}

.header-left,
.header-right {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.app-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin-left: 1rem;
}

.btn-icon {
  background: none;
  border: none;
  color: var(--text-light);
  font-size: 1.25rem;
  cursor: pointer;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: background-color var(--transition-speed);
}

.btn-icon:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.search-box {
  position: relative;
  width: 250px;
}

.search-icon {
  position: absolute;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--gray-light);
}

.search-input {
  width: 100%;
  padding: 0.5rem 1rem 0.5rem 2.5rem;
  border-radius: 50px;
  border: none;
  background-color: var(--background-dark);
  color: var(--text-light);
}

.search-input:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.25);
}

.user-menu {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 50px;
  transition: background-color var(--transition-speed);
}

.user-menu:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.user-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background-color: var(--primary-color);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
}

.user-name {
  font-weight: 500;
}

@media (max-width: 768px) {
  .app-title {
    display: none;
  }

  .search-box {
    width: 150px;
  }
}
</style>
