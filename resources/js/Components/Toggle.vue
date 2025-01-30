<script setup>
import { defineProps, defineEmits } from 'vue'

defineProps({
  checked: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:checked'])

const toggle = (event) => {
  emit('update:checked', event.target.checked)
}
</script>

<template>
  <label class="relative inline-block w-14 h-8 cursor-pointer">
    <input
      type="checkbox"
      class="opacity-0 w-0 h-0 peer"
      :checked="checked"
      @change="toggle"
    >
    <span
      class="absolute inset-0
             bg-white
             rounded-md
             border border-gray-300
             shadow-sm
             transition-colors duration-300"
    />
    <span
      class="absolute left-1 top-1
             w-6 h-6
             bg-gray-100
             border border-gray-300
             rounded-md
             transition-transform duration-300
             transform
             peer-checked:translate-x-6
             shadow"
    />
  </label>
</template>
