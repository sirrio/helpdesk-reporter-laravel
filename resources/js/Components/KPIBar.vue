<template>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <!-- KPI: Gesamtanzahl -->
    <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center">
      <div class="text-2xl font-bold">{{ total }}</div>
      <div class="text-sm text-gray-500">Gesamtanzahl</div>
    </div>
    <!-- KPI: Durchschnitt pro Woche -->
    <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center">
      <div class="text-2xl font-bold">{{ avgPerWeek }}</div>
      <div class="text-sm text-gray-500">Durchschnitt/Woche</div>
    </div>
    <!-- KPI: Top-Fakultät -->
    <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center">
      <div class="text-2xl font-bold">{{ topFaculty }}</div>
      <div class="text-sm text-gray-500">Top-Fakultät</div>
    </div>
  </div>
</template>
<script setup>
// Props: total, avgPerWeek, topFaculty
const props = defineProps(['total', 'avgPerWeek', 'topFaculty'])
</script>
<!--
KPIBar.vue: Zeigt die wichtigsten Kennzahlen an. Props: total, avgPerWeek, topFaculty
-->
