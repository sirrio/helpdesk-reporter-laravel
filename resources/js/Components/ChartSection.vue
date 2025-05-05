<template>
  <div class="mb-8">
    <div class="flex items-center mb-2">
      <h2 class="text-xl font-bold mr-2">{{ title }}</h2>
      <span v-if="info" class="text-xs text-gray-500">{{ info }}</span>
    </div>
    <slot />
  </div>
</template>
<script setup>
// Props: title, info (optional)
const props = defineProps(['title', 'info'])
</script>
<!--
ChartSection.vue: Wrapper für Diagramm + Titel + Infotext
-->
