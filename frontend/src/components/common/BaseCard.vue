<template>
  <div class="card" :class="{ 'no-hover': noHover }">
    <div v-if="title || $slots.header" class="card-header">
      <h3 class="card-title" v-if="title">{{ title }}</h3>
      <slot name="header" v-else></slot>
      <div class="card-actions" v-if="$slots.actions">
        <slot name="actions"></slot>
      </div>
    </div>
    <div class="card-body">
      <slot></slot>
    </div>
    <div class="card-footer" v-if="$slots.footer">
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script setup>
/*
  Este componente é um cartão reutilizável chamado `BaseCard`. Ele foi projetado para ser usado em várias partes do sistema,
  fornecendo uma estrutura consistente para exibir conteúdo com cabeçalho, corpo e rodapé opcionais.

  **Props (Propriedades):**
  - `title`: Uma string que define o título do cartão. Se fornecida, será exibida no cabeçalho do cartão.
  - `noHover`: Um booleano que, quando verdadeiro, desativa o efeito de "hover" (elevação) no cartão.

  **Slots (Espaços Personalizáveis):**
  - `header`: Um slot opcional para substituir o cabeçalho padrão. Se usado, o título (`title`) será ignorado.
  - `actions`: Um slot opcional para adicionar ações (botões, links, etc.) no cabeçalho do cartão.
  - `default`: O slot principal para o conteúdo do corpo do cartão.
  - `footer`: Um slot opcional para adicionar conteúdo no rodapé do cartão.

  **Estilo e Classes Dinâmicas:**
  - A classe base do cartão é sempre `card`.
  - A classe `no-hover` é adicionada dinamicamente se a prop `noHover` for verdadeira, desativando o efeito de elevação ao passar o mouse.
  - O cartão é estilizado com bordas arredondadas, sombra e transições suaves para interações visuais.

  **Estrutura do Cartão:**
  - `card-header`: Contém o título ou o slot `header`, além de ações opcionais no slot `actions`.
  - `card-body`: Contém o conteúdo principal do cartão, definido pelo slot padrão.
  - `card-footer`: Contém o conteúdo do rodapé, definido pelo slot `footer`.

  **Comportamento de Hover:**
  - Por padrão, o cartão se eleva levemente ao passar o mouse, criando um efeito visual de destaque.
  - Esse comportamento pode ser desativado com a prop `noHover`.
.
*/
defineProps({
  title: {
    type: String,
    default: "",
  },
  noHover: {
    type: Boolean,
    default: false,
  },
});
</script>

<style scoped>
/* Estilo base do cartão */
.card {
  background-color: var(--background-light);
  border-radius: 8px;
  overflow: hidden;
  box-shadow: var(--card-shadow);
  transition:
    transform var(--transition-speed),
    box-shadow var(--transition-speed);
  margin-bottom: 1.5rem;
}

/* Efeito de hover (elevação) */
.card:not(.no-hover):hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
}

/* Cabeçalho do cartão */
.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.25rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

/* Título do cartão */
.card-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0;
}

/* Corpo do cartão */
.card-body {
  padding: 1.5rem;
}

/* Rodapé do cartão */
.card-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  background-color: rgba(0, 0, 0, 0.05);
}

/* Ações no cabeçalho */
.card-actions {
  display: flex;
  gap: 0.5rem;
}
</style>
