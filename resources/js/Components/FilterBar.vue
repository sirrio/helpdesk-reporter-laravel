<template>
  <div class="flex flex-wrap gap-4 mb-6">
    <!-- Semester-Filter -->
    <select v-model="selectedSemester" @change="$emit('update:semester', selectedSemester)" class="rounded border p-2">
      <option v-for="sem in semesters" :key="sem.id" :value="sem.semester">{{ sem.semester }}</option>
    </select>
    <!-- Fakultät-Filter -->
    <select v-model="selectedFaculty" @change="$emit('update:faculty', selectedFaculty)" class="rounded border p-2">
      <option value="">Alle Fakultäten</option>
      <option v-for="faculty in faculties" :key="faculty.id" :value="faculty.name">{{ faculty.name }}</option>
    </select>
    <!-- Thema-Filter -->
    <select v-model="selectedTopic" @change="$emit('update:topic', selectedTopic)" class="rounded border p-2">
      <option value="">Alle Themen</option>
      <option v-for="topic in topics" :key="topic">{{ topic }}</option>
    </select>
  </div>
</template>
<script setup>
import { ref, watch } from 'vue'
const props = defineProps({
  semesters: Array,
  faculties: Array,
  topics: Array,
  selectedSemester: String,
  selectedFaculty: String,
  selectedTopic: String
})
const emit = defineEmits(['update:semester', 'update:faculty', 'update:topic'])

const selectedSemester = ref(props.selectedSemester || props.semesters[0]?.semester || '')
const selectedFaculty = ref(props.selectedFaculty || '')
const selectedTopic = ref(props.selectedTopic || '')

watch(() => props.selectedSemester, (val) => {
  selectedSemester.value = val
})
watch(() => props.selectedFaculty, (val) => {
  selectedFaculty.value = val
})
watch(() => props.selectedTopic, (val) => {
  selectedTopic.value = val
})

// Watch for prop changes to reset filters (z.B. bei neuem Semester)
watch(() => props.semesters, (newVal) => {
  selectedSemester.value = newVal[0]?.semester || ''
  emit('update:semester', selectedSemester.value)
})
</script>
<!--
FilterBar.vue: Filter für Semester, Fakultät, Thema. Gibt Events bei Änderung aus.
-->
