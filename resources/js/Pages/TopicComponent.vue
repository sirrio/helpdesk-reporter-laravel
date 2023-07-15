<script setup>
import IconSquareCheck from '@/Icons/IconSquareCheck.vue'

defineProps(['topic'])
</script>


<template>
  <div
    v-if="topic"
    class="mr-6"
  >
    <icon-square-check class="h-4 w-4 inline-block mb-1" />
    <span><slot /></span>
  </div>
</template>
