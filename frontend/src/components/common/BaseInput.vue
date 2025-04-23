<template>
  <div class="form-group">
    <!-- Rótulo do campo de entrada -->
    <label v-if="label" :for="id" class="form-label">{{ label }}</label>

    <!-- Grupo do campo de entrada -->
    <div class="input-group" :class="{ 'input-error': hasError }">
      <!-- Ícone opcional (se fornecido) -->
      <font-awesome-icon v-if="icon" :icon="icon" class="input-icon" />

      <!-- Campo de entrada -->
      <input
        :id="id"
        :type="type"
        :value="modelValue"
        :placeholder="placeholder"
        :required="required"
        :disabled="disabled"
        class="form-control"
        :class="{ 'has-icon': icon }"
        @input="$emit('update:modelValue', $event.target.value)"
      />
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
  Este componente é um campo de entrada reutilizável chamado `BaseInput`. Ele foi projetado para ser flexível e estilizado,
  permitindo que seja usado em diferentes partes do sistema com suporte para rótulos, ícones, mensagens de erro e texto de ajuda.

  **Props (Propriedades):**
  - `modelValue`: O valor do campo de entrada. É usado para vinculação bidirecional (v-model).
  - `label`: O rótulo exibido acima do campo de entrada. Opcional.
  - `type`: O tipo do campo de entrada (ex.: "text", "password", "email"). O padrão é "text".
  - `placeholder`: Um texto de espaço reservado exibido dentro do campo de entrada. Opcional.
  - `required`: Um booleano que indica se o campo é obrigatório. O padrão é `false`.
  - `disabled`: Um booleano que desativa o campo, impedindo a interação. O padrão é `false`.
  - `id`: O identificador único do campo. Se não for fornecido, será gerado automaticamente.
  - `error`: Uma mensagem de erro exibida abaixo do campo. Opcional.
  - `helpText`: Um texto de ajuda exibido abaixo do campo. Opcional.
  - `icon`: Um ícone exibido dentro do campo, à esquerda. Opcional.

  **Emits (Eventos):**
  - `update:modelValue`: Emite o valor atualizado do campo de entrada para o componente pai.

  **Comportamento Dinâmico:**
  - O campo exibe um ícone à esquerda, se a prop `icon` for fornecida.
  - Se a prop `error` for definida, o campo será estilizado com um estado de erro.
  - O texto de ajuda (`helpText`) é exibido abaixo do campo, se fornecido.

  **Estilo e Classes Dinâmicas:**
  - A classe base do campo é sempre `form-control`.
  - A classe `has-icon` é adicionada dinamicamente se a prop `icon` for fornecida, ajustando o preenchimento interno do campo.
  - A classe `input-error` é aplicada ao grupo de entrada se houver uma mensagem de erro.

  **Acessibilidade:**
  - O rótulo (`label`) é associado ao campo de entrada por meio do atributo `for`, garantindo que o campo seja acessível.
.
*/

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: "",
  },
  label: {
    type: String,
    default: "",
  },
  type: {
    type: String,
    default: "text",
  },
  placeholder: {
    type: String,
    default: "",
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
    default: () => `input-${Math.random().toString(36).substring(2, 9)}`,
  },
  error: {
    type: String,
    default: "",
  },
  helpText: {
    type: String,
    default: "",
  },
  icon: {
    type: String,
    default: "",
  },
});

defineEmits(["update:modelValue"]);

const hasError = computed(() => props.error && props.error.length > 0);
</script>

<style scoped>
/* Grupo do campo de entrada */
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

/* Grupo de entrada */
.input-group {
  position: relative;
}

/* Ícone dentro do campo */
.input-icon {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: var(--gray-light);
}

/* Campo de entrada */
.form-control {
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
}

/* Campo com ícone */
.form-control.has-icon {
  padding-left: 2.5rem;
}

/* Foco no campo */
.form-control:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.25);
}

/* Campo com erro */
.input-error .form-control {
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
