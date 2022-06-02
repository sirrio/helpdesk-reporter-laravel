<script setup>
import { computed } from 'vue'

const emit = defineEmits(['update:checked'])

const props = defineProps({
  checked: {
    type: [Array, Boolean],
    default: false,
  },
  value: {
    default: null,
  },
})

const proxyChecked = computed({
  get() {
    return props.checked
  },

  set(val) {
    emit('update:checked', val)
  },
})
</script>

<template>
  <div class="pb-4 pr-4">
    <label class="checkmark">
      <input
        v-model="proxyChecked"
        type="checkbox"
        :value="value"
        class="rounded w-full h-12 border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
      >
      <span class="checkmark-text text-sm truncate"><slot /></span>
    </label>
  </div>
</template>

<style scoped>
.checkmark {
  position: relative;
}

.checkmark input:checked {
  background: #1f2937;
}

.checkmark input:checked + span {
  color: white;
}

.checkmark span {
  color: black;
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
}
</style>
