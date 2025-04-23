<template>
  <button
    class="btn"
    :class="[
      `btn-${type}`,
      {
        'btn-block': block,
        'btn-sm': size === 'small',
        'btn-lg': size === 'large',
      },
    ]"
    :disabled="disabled || loading"
    @click="$emit('click', $event)"
  >
    <font-awesome-icon v-if="loading" icon="spinner" spin />
    <font-awesome-icon v-else-if="icon" :icon="icon" />
    <span v-if="$slots.default"><slot /></span>
  </button>
</template>

<script setup>
/*
  Este componente é um botão reutilizável chamado `BaseButton`. Ele foi projetado para ser altamente configurável e estilizado, 
  permitindo que seja usado em diferentes partes do sistema com variações de estilo, tamanho e comportamento.

  **Props (Propriedades):**
  - `type`: Define o estilo do botão. Pode ser "primary", "secondary", "danger" ou "outline". O padrão é "primary".
  - `size`: Define o tamanho do botão. Pode ser "small", "medium" ou "large". O padrão é "medium".
  - `block`: Um booleano que, quando verdadeiro, faz o botão ocupar toda a largura do contêiner.
  - `disabled`: Um booleano que desativa o botão, impedindo cliques e alterando o estilo visual para indicar que está desativado.
  - `loading`: Um booleano que exibe um ícone de carregamento (spinner) no botão, útil para indicar que uma ação está em andamento.
  - `icon`: Permite adicionar um ícone ao botão, usando o Font Awesome.

  **Emits (Eventos):**
  - `click`: Emite um evento de clique quando o botão é pressionado. O evento é passado para o componente pai.

  **Estilo e Classes Dinâmicas:**
  - A classe base do botão é sempre `btn`.
  - Classes adicionais são adicionadas dinamicamente com base nas props:
    - `btn-${type}`: Aplica o estilo correspondente ao tipo do botão.
    - `btn-block`: Faz o botão ocupar toda a largura do contêiner, se `block` for verdadeiro.
    - `btn-sm` ou `btn-lg`: Ajusta o tamanho do botão com base na prop `size`.
  - O botão é desativado (`disabled`) se a prop `disabled` ou `loading` for verdadeira.

  **Conteúdo do Botão:**
  - Se `loading` for verdadeiro, exibe um ícone de carregamento (spinner).
  - Caso contrário, se `icon` for fornecido, exibe o ícone correspondente.
  - Se houver conteúdo no slot padrão, ele será exibido como o texto ou conteúdo principal do botão.

  **Estilos (CSS):**
  - O botão é estilizado para ser flexível e responsivo, com diferentes tamanhos e estados (normal, desativado, etc.).
  - Inclui transições suaves para mudanças de estado.
  - Classes como `btn-sm` e `btn-lg` ajustam o tamanho do botão.
  - A classe `btn-block` faz o botão ocupar 100% da largura do contêiner.

 */
defineProps({
  type: {
    type: String,
    default: "primary",
    validator: (value) =>
      ["primary", "secondary", "danger", "outline"].includes(value),
  },
  size: {
    type: String,
    default: "medium",
    validator: (value) => ["small", "medium", "large"].includes(value),
  },
  block: {
    type: Boolean,
    default: false,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  loading: {
    type: Boolean,
    default: false,
  },
  icon: {
    type: String,
    default: "",
  },
});

defineEmits(["click"]);
</script>

<style scoped>
/* Estilos base para o botão */
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  font-size: 1rem;
  font-weight: 500;
  border-radius: 4px;
  cursor: pointer;
  transition: all var(--transition-speed);
  border: none;
}

/* Estilo para botões desativados */
.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* Estilo para botões que ocupam toda a largura */
.btn-block {
  display: flex;
  width: 100%;
}

/* Estilo para botões pequenos */
.btn-sm {
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
}

/* Estilo para botões grandes */
.btn-lg {
  padding: 1rem 2rem;
  font-size: 1.125rem;
}
</style>
