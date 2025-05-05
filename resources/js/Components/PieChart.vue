<template>
  <div>
    <canvas ref="canvas" :height="height" :width="width" />
  </div>
</template>
<script setup>
import { onMounted, ref } from 'vue'
import Chart from 'chart.js/auto'
const props = defineProps({
  chartData: Object,
  chartOptions: Object,
  height: { type: [String, Number], default: 400 },
  width: { type: [String, Number], default: 400 },
})
const canvas = ref(null)
onMounted(() => {
  new Chart(canvas.value.getContext('2d'), {
    type: 'pie',
    data: props.chartData,
    options: props.chartOptions
  })
})
</script>
<!-- PieChart.vue: Wiederverwendbare Pie-Chart-Komponente -->
