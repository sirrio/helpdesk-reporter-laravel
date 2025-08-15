<script setup>
import Authenticated from '@/Layouts/Authenticated.vue'
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { exportStatisticsPdf } from '@/Pages/Statistics/statisticsPdf'

import KPIBar from '@/Components/KPIBar.vue'
import FilterBar from '@/Components/FilterBar.vue'
import ChartSection from '@/Components/ChartSection.vue'
import BarChart from '@/Components/BarChart.vue'
import PieChart from '@/Components/PieChart.vue'
import PolarAreaChart from '@/Components/PolarAreaChart.vue'

const props = defineProps([
  'attendancesByWeek',
  'attendancesByFaculty',
  'attendancesByDegree',
  'attendancesByTopic',
  'semesters',
  'currentSem'
])


// Filter-State
const selectedSemester = ref(props.currentSem)
const selectedFaculty = ref('')
const selectedTopic = ref('')

// Translations and color palettes
const topicTranslations = {
  mathBasic: 'Mathe Schulwissen',
  mathFractions: 'Mathe Bruchrechnen',
  mathLow: 'Mathe Semester 1 und 2',
  mathHigh: 'Mathe Semester 3+',
  programming: 'Programmieren',
  physics: 'Physik',
  chemistry: 'Chemie',
  organization: 'Orga.'
}
const dayLabels = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag']
const defaultBgColors = [
  'rgba(255, 99, 132, .2)',
  'rgba(54, 162, 235, .2)',
  'rgba(255, 206, 86, .2)',
  'rgba(75, 192, 192, .2)',
  'rgba(153, 102, 255, .2)',
  'rgba(255, 159, 64, .2)'
]
const defaultBorderColors = [
  'rgba(255, 99, 132, 1)',
  'rgba(54, 162, 235, 1)',
  'rgba(255, 206, 86, 1)',
  'rgba(75, 192, 192, 1)',
  'rgba(153, 102, 255, 1)',
  'rgba(255, 159, 64, 1)'
]

// KPIs dynamisch berechnen
const total = computed(() => {
  return Object.values(filteredAttendancesByWeek.value).reduce((sum, week) => {
    return sum + Object.values(week).reduce((wSum, arr) => wSum + arr.length, 0)
  }, 0)
})

const avgPerWeek = computed(() => {
  const weeks = Object.keys(filteredAttendancesByWeek.value).length
  return weeks > 0 ? Math.round(total.value / weeks) : 0
})

const topFaculty = computed(() => {
  const faculties = filteredAttendancesByFaculty.value
  let max = 0
  let top = ''
  for (const [faculty, count] of Object.entries(faculties)) {
    if (count > max) {
      max = count
      top = faculty
    }
  }
  return top
})

// Filterdaten dynamisch berechnen
const faculties = computed(() => {
  // Liefere Array mit id und name für Dropdown-Kompatibilität
  return Object.entries(props.attendancesByFaculty).map(([name], idx) => ({ id: idx, name }))
})

const topics = computed(() => Object.keys(props.attendancesByTopic).map(k => topicTranslations[k] || k))

const changeSemester = (sem) => {
  // eslint-disable-next-line no-undef
  router.visit(route('statistics'), { data: { 'semester': sem } })
}

// Filter-Handler
const handleSemester = (val) => {
  selectedSemester.value = val
  changeSemester(val)
}
const handleFaculty = (val) => { selectedFaculty.value = val }
const handleTopic = (val) => { selectedTopic.value = val }

// Gefilterte Daten für alle KPIs und Charts
const filteredAttendancesByWeek = computed(() => {
  if (!selectedFaculty.value && !selectedTopic.value) return props.attendancesByWeek
  const filtered = {}
  for (const [weekKey, week] of Object.entries(props.attendancesByWeek)) {
    filtered[weekKey] = {}
    for (const [day, arr] of Object.entries(week)) {
      filtered[weekKey][day] = arr.filter(a => {
        const facultyMatch = !selectedFaculty.value || a.faculty === selectedFaculty.value
        const topicMatch = !selectedTopic.value || a.topics?.includes(selectedTopic.value) || a[selectedTopic.value]
        return facultyMatch && topicMatch
      })
    }
  }
  return filtered
})

const filteredAttendancesByFaculty = computed(() => {
  if (!selectedTopic.value) return props.attendancesByFaculty
  const result = {}
  for (const faculty of Object.keys(props.attendancesByFaculty)) {
    result[faculty] = 0
    for (const week of Object.values(props.attendancesByWeek)) {
      for (const arr of Object.values(week)) {
        result[faculty] += arr.filter(a =>
          a.faculty === faculty &&
          (a.topics?.includes(selectedTopic.value) || a[selectedTopic.value])
        ).length
      }
    }
  }
  return result
})

const filteredAttendancesByDegree = computed(() => {
  return props.attendancesByDegree
})

const filteredAttendancesByTopic = computed(() => {
  if (!selectedFaculty.value) return props.attendancesByTopic
  const result = {}
  for (const topic of Object.keys(props.attendancesByTopic)) {
    result[topic] = 0
    for (const week of Object.values(props.attendancesByWeek)) {
      for (const arr of Object.values(week)) {
        result[topic] += arr.filter(a =>
          a.faculty === selectedFaculty.value &&
          (a.topics?.includes(topic) || a[topic])
        ).length
      }
    }
  }
  return result
})

// Charts dynamisch aus gefilterten Daten berechnen
const weekCharts = computed(() => {
  return Object.entries(filteredAttendancesByWeek.value).map(([weekKey, weekData]) => {
    const prepedData = Object.values(weekData).map(arr => arr.length)
    return {
      weekKey,
      chartData: {
        labels: dayLabels,
        datasets: [{
          data: prepedData,
          backgroundColor: defaultBgColors.slice(0, prepedData.length),
          borderColor: defaultBorderColors.slice(0, prepedData.length),
          borderWidth: 1,
          minBarLength: 10
        }]
      },
      chartOptions: {
        responsive: true,
        plugins: {
          legend: { display: false },
          title: { display: true, text: 'KW ' + weekKey }
        },
        scales: { y: { min: 0, max: 20 } }
      }
    }
  })
})

const facultyChartData = computed(() => {
  const rawLabels = Object.keys(filteredAttendancesByFaculty.value)
  const data = Object.values(filteredAttendancesByFaculty.value)
  const labels = rawLabels.map((name, idx) => name + ' (' + data[idx] + ')')
  return {
    labels,
    datasets: [{
      data,
      backgroundColor: defaultBgColors.slice(0, data.length),
      borderColor: defaultBorderColors.slice(0, data.length),
      borderWidth: 1
    }]
  }
})
const degreeChartData = computed(() => {
  const labels = Object.keys(filteredAttendancesByDegree.value)
  const data = Object.values(filteredAttendancesByDegree.value)
  return {
    labels,
    datasets: [{
      data,
      backgroundColor: defaultBgColors.slice(0, data.length),
      borderColor: defaultBorderColors.slice(0, data.length),
      borderWidth: 1
    }]
  }
})
const topicChartData = computed(() => {
  const rawKeys = Object.keys(filteredAttendancesByTopic.value)
  const data = Object.values(filteredAttendancesByTopic.value)
  const labels = rawKeys.map((k, idx) => (topicTranslations[k] || k) + ' (' + data[idx] + ')')
  return {
    labels,
    datasets: [{
      data,
      backgroundColor: defaultBgColors.slice(0, data.length),
      borderColor: defaultBorderColors.slice(0, data.length),
      borderWidth: 1
    }]
  }
})

// Chart options for distribution charts
const facultyChartOptions = {
  responsive: true,
  plugins: { legend: { position: 'right' } }
}
const degreeChartOptions = {
  responsive: true,
  plugins: { legend: { display: false } },
  scales: { y: { beginAtZero: true } }
}
const topicChartOptions = {
  responsive: true,
  plugins: { legend: { position: 'right' } }
}

function statisticsExport() {
  exportStatisticsPdf({
    currentSem: props.currentSem,
    total: total.value,
    avgPerWeek: avgPerWeek.value,
    topFaculty: topFaculty.value,
    weekCharts: weekCharts.value,
    facultyChartData: facultyChartData.value,
    facultyChartOptions,
    degreeChartData: degreeChartData.value,
    degreeChartOptions,
    topicChartData: topicChartData.value,
    topicChartOptions
  })
}

</script>

<template>
  <authenticated>
    <div class="pt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="flex justify-between items-center mb-4">
        <div class="flex items-center">
          <p class="text-3xl font-bold mr-4">
            Statistik
          </p>
          <a
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
            style="cursor: pointer"
            @click="statisticsExport"
          >
            Exportiere {{ currentSem }} als PDF
          </a>
        </div>
        <FilterBar
          :semesters="semesters"
          :faculties="faculties"
          :topics="topics"
          :selected-semester="selectedSemester"
          :selected-faculty="selectedFaculty"
          :selected-topic="selectedTopic"
          @update:semester="handleSemester"
          @update:faculty="handleFaculty"
          @update:topic="handleTopic"
        />
      </div>
      <KPIBar
        :total="total"
        :avg-per-week="avgPerWeek"
        :top-faculty="topFaculty"
      />
      <ChartSection
        title="Wochenübersicht"
        info="Anzahl der Teilnahmen pro Woche"
      >
        <div class="flex flex-wrap gap-4">
          <div
            v-for="weekChart in weekCharts"
            :key="weekChart.weekKey"
            class="p-6 flex-grow bg-white rounded-xl shadow-md flex justify-center"
          >
            <BarChart
              :chart-data="weekChart.chartData"
              :chart-options="weekChart.chartOptions"
            />
          </div>
        </div>
      </ChartSection>
      <ChartSection
        title="Verteilung Fachbereiche"
        info="Teilnahmen nach Fakultät"
      >
        <div class="p-6 bg-white shadow-md rounded-xl">
          <PolarAreaChart
            :chart-data="facultyChartData"
            :chart-options="facultyChartOptions"
          />
        </div>
      </ChartSection>
      <ChartSection
        title="Verteilung Studiengänge"
        info="Teilnahmen nach Studiengang"
      >
        <div class="p-6 bg-white shadow-md rounded-xl">
          <BarChart
            :chart-data="degreeChartData"
            :chart-options="degreeChartOptions"
          />
        </div>
      </ChartSection>
      <ChartSection
        title="Verteilung Themen"
        info="Teilnahmen nach Thema"
      >
        <div class="p-6 bg-white shadow-md rounded-xl">
          <PieChart
            :chart-data="topicChartData"
            :chart-options="topicChartOptions"
          />
        </div>
      </ChartSection>
    </div>
  </authenticated>
</template>
