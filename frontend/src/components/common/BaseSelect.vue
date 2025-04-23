<template>
  <div class="form-group">
    <!-- Rótulo do campo de seleção -->
    <label v-if="label" :for="id" class="form-label">{{ label }}</label>

    <!-- Grupo do campo de seleção -->
    <div class="select-group" :class="{ 'select-error': hasError }">
      <!-- Campo de seleção -->
      <select
        :id="id"
        :value="modelValue"
        class="form-select"
        :required="required"
        :disabled="disabled"
        @change="$emit('update:modelValue', $event.target.value)"
      >
        <!-- Placeholder opcional -->
        <option v-if="placeholder" value="" disabled selected>
          {{ placeholder }}
        </option>
        <!-- Opções do campo de seleção -->
        <option
          v-for="option in options"
          :key="option.value"
          :value="option.value"
        >
          {{ option.label }}
        </option>
      </select>
      <!-- Ícone de seta para baixo -->
      <font-awesome-icon icon="chevron-down" class="select-icon" />
    </div>

    <!-- Mensagem de erro (se houver) -->
    <div class="form-error" v-if="hasError">{{ error }}</div>

    <!-- Texto de ajuda (se fornecido) -->
    <div class="form-help" v-if="helpText">{{ helpText }}</div>
  </div>
</template>

<script setup>
import { computed } from "vue";

/*
  Este componente é um campo de seleção reutilizável chamado `BaseSelect`. Ele foi projetado para ser flexível e estilizado,
  permitindo que seja usado em diferentes partes do sistema com suporte para rótulos, mensagens de erro e texto de ajuda.

  **Props (Propriedades):**
  - `modelValue`: O valor selecionado no campo. É usado para vinculação bidirecional (v-model).
  - `options`: Um array de objetos que define as opções disponíveis no campo. Cada objeto deve ter as propriedades `value` e `label`.
  - `label`: O rótulo exibido acima do campo de seleção. Opcional.
  - `placeholder`: Um texto exibido como a primeira opção do campo, desabilitado e selecionado por padrão. O padrão é "Selecione uma opção".
  - `required`: Um booleano que indica se o campo é obrigatório. O padrão é `false`.
  - `disabled`: Um booleano que desativa o campo, impedindo a interação. O padrão é `false`.
  - `id`: O identificador único do campo. Se não for fornecido, será gerado automaticamente.
  - `error`: Uma mensagem de erro exibida abaixo do campo. Opcional.
  - `helpText`: Um texto de ajuda exibido abaixo do campo. Opcional.

  **Emits (Eventos):**
  - `update:modelValue`: Emite o valor selecionado no campo para o componente pai.

  **Comportamento Dinâmico:**
  - O campo exibe um placeholder como a primeira opção, se a prop `placeholder` for fornecida.
  - Se a prop `error` for definida, o campo será estilizado com um estado de erro.
  - O texto de ajuda (`helpText`) é exibido abaixo do campo, se fornecido.

  **Estilo e Classes Dinâmicas:**
  - A classe base do campo é sempre `form-select`.
  - A classe `select-error` é aplicada ao grupo de seleção se houver uma mensagem de erro.
  - Um ícone de seta para baixo é exibido à direita do campo, estilizado com a classe `select-icon`.

  **Acessibilidade:**
  - O rótulo (`label`) é associado ao campo de seleção por meio do atributo `for`, garantindo que o campo seja acessível..
*/

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: "",
  },
  options: {
    type: Array,
    required: true,
  },
  label: {
    type: String,
    default: "",
  },
  placeholder: {
    type: String,
    default: "Selecione uma opção",
  },
  required: {
    type: Boolean,
    default: false,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  id: {
    type: String,
    default: () => `select-${Math.random().toString(36).substring(2, 9)}`,
  },
  error: {
    type: String,
    default: "",
  },
  helpText: {
    type: String,
    default: "",
  },
});

defineEmits(["update:modelValue"]);

const hasError = computed(() => props.error && props.error.length > 0);
</script>

<style scoped>
/* Grupo do campo de seleção */
.form-group {
  margin-bottom: 1.25rem;
}

/* Rótulo do campo */
.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: var(--text-light);
}

/* Grupo de seleção */
.select-group {
  position: relative;
}

/* Campo de seleção */
.form-select {
  width: 100%;
  padding: 0.75rem;
  font-size: 1rem;
  border: 1px solid rgba(255, 255, 255, 0.1);
  background-color: var(--background-dark);
  border-radius: 4px;
  color: var(--text-light);
  transition:
    border-color var(--transition-speed),
    box-shadow var(--transition-speed);
  appearance: none;
  padding-right: 2.5rem;
}

/* Foco no campo */
.form-select:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.25);
}

/* Ícone de seta para baixo */
.select-icon {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: var(--gray-light);
  pointer-events: none;
}

/* Campo com erro */
.select-error .form-select {
  border-color: var(--accent-color);
}

/* Mensagem de erro */
.form-error {
  margin-top: 0.5rem;
  color: var(--accent-color);
  font-size: 0.875rem;
}

/* Texto de ajuda */
.form-help {
  margin-top: 0.5rem;
  color: var(--gray-light);
  font-size: 0.875rem;
}
</style>
