/*
Neues Seitenkonzept für Statistik (Overview.vue)

# Layout (Grid-basiert)
- Header mit Titel, Export-Button, FilterBar (Semester, Fakultät, Thema)
- KPIBar: Kompakte Übersicht (Gesamtanzahl, Durchschnitt pro Woche, Top-Fakultät)
- ChartSection: Gruppierte Diagramme (Wochenübersicht, Fachbereiche, Studiengänge, Themen)
- Zu jedem Chart: Infotext/Erklärung
- Responsive & barrierefrei

# Komponentenstruktur
- FilterBar.vue: Filter für Semester, Fakultät, Thema
- KPIBar.vue: Zeigt KPIs an
- ChartSection.vue: Wrapper für Diagramm + Titel + Infotext
- Chart-Komponenten (z.B. BarChart.vue, PieChart.vue, PolarAreaChart.vue)

# PDF-Export
- Exportiert KPIs und Charts in logischer Reihenfolge
- Einheitliche Chartgrößen, Titel, Datum, Logo
- Seitenumbrüche und Ränder
*/

<script setup>
import Authenticated from '@/Layouts/Authenticated.vue'
import Chart from 'chart.js/auto'
import { onMounted, ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import jsPDF from 'jspdf'

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

const semester = ref(props.currentSem)

// Filter-State
const selectedSemester = ref(props.currentSem)
const selectedFaculty = ref('')
const selectedTopic = ref('')

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

const topics = computed(() => {
  const translations = {
    mathBasic: 'Mathe Schulwissen',
    mathFractions: 'Mathe Bruchrechnen',
    mathLow: 'Mathe Semester 1 und 2',
    mathHigh: 'Mathe Semester 3+',
    programming: 'Programmieren',
    physics: 'Physik',
    chemistry: 'Chemie',
    organization: 'Orga.'
  }
  return Object.keys(props.attendancesByTopic).map(k => translations[k] || k)
})

const changeSemester = (sem) => {
  router.visit(route('statistics'), { data: { 'semester': sem } })
}

// Filter-Handler
const handleSemester = (val) => {
  selectedSemester.value = val
  changeSemester(val)
}
const handleFaculty = (val) => { selectedFaculty.value = val }
const handleTopic = (val) => { selectedTopic.value = val }

const objectMap = (obj, fn) =>
  Object.fromEntries(
    Object.entries(obj).map(
      ([k, v], i) => [k, fn(v, k, i)]
    )
  )

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
  for (const [faculty, count] of Object.entries(props.attendancesByFaculty)) {
    result[faculty] = 0
    for (const [weekKey, week] of Object.entries(props.attendancesByWeek)) {
      for (const arr of Object.values(week)) {
        result[faculty] += arr.filter(a => a.faculty === faculty && (a.topics?.includes(selectedTopic.value) || a[selectedTopic.value])).length
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
    for (const [weekKey, week] of Object.entries(props.attendancesByWeek)) {
      for (const arr of Object.values(week)) {
        result[topic] += arr.filter(a => a.faculty === selectedFaculty.value && (a.topics?.includes(topic) || a[topic])).length
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
        labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
        datasets: [{
          data: prepedData,
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)'
          ],
          borderWidth: 1,
          minBarLength: 10
        }]
      },
      chartOptions: {
        responsive: true,
        plugins: {
          legend: { display: false },
          title: { display: true, text: `KW ${weekKey}` }
        },
        scales: { y: { min: 0, max: 20 } }
      }
    }
  })
})

const facultyChartData = computed(() => {
  const labels = Object.keys(filteredAttendancesByFaculty.value)
  const data = Object.values(filteredAttendancesByFaculty.value)
  return {
    labels,
    datasets: [{
      data,
      backgroundColor: [
        'rgba(255, 99, 132, .2)',
        'rgba(54, 162, 235, .2)',
        'rgba(255, 206, 86, .2)',
        'rgba(75, 192, 192, .2)',
        'rgba(153, 102, 255, .2)',
        'rgba(255, 159, 64, .2)'
      ],
      borderColor: [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
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
      backgroundColor: [
        'rgba(255, 99, 132, .2)',
        'rgba(54, 162, 235, .2)',
        'rgba(255, 206, 86, .2)',
        'rgba(75, 192, 192, .2)',
        'rgba(153, 102, 255, .2)',
        'rgba(255, 159, 64, .2)'
      ],
      borderColor: [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 1
    }]
  }
})
const topicChartData = computed(() => {
  const translations = {
    mathBasic: 'Mathe Schulwissen',
    mathFractions: 'Mathe Bruchrechnen',
    mathLow: 'Mathe Semester 1 und 2',
    mathHigh: 'Mathe Semester 3+',
    programming: 'Programmieren',
    physics: 'Physik',
    chemistry: 'Chemie',
    organization: 'Orga.'
  }
  const labels = Object.keys(filteredAttendancesByTopic.value).map(k => translations[k] || k)
  const data = Object.values(filteredAttendancesByTopic.value)
  return {
    labels,
    datasets: [{
      data,
      backgroundColor: [
        'rgba(255, 99, 132, .2)',
        'rgba(54, 162, 235, .2)',
        'rgba(255, 206, 86, .2)',
        'rgba(75, 192, 192, .2)',
        'rgba(153, 102, 255, .2)',
        'rgba(255, 159, 64, .2)'
      ],
      borderColor: [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 1
    }]
  }
})

onMounted(() => {
  // Existing onMounted logic
})

function toDataURL(img, saturationPercentage) {
  const canvas = document.createElement('canvas')
  const ctx = canvas.getContext('2d')
  canvas.width = img.width
  canvas.height = img.height
  ctx.drawImage(img, 0, 0)
  ctx.filter = `saturate(${saturationPercentage}%)`
  ctx.drawImage(img, 0, 0)
  return canvas.toDataURL('image/jpg')
}

function createStatisticsPdf() {
  const pdf = new jsPDF()
  const now = new Date()
  const dateString = now.toLocaleDateString('de-DE')
  let y = 15

  // Header mit Titel, Semester, Datum
  pdf.setFontSize(18)
  pdf.text(`Statistik`, 10, y)
  pdf.setFontSize(12)
  pdf.text(`Semester: ${props.currentSem}`, 10, y + 8)
  pdf.text(`Erstellt am: ${dateString}`, 10, y + 16)
  y += 28

  // KPIs als Text
  pdf.setFontSize(12)
  pdf.text(`Gesamtanzahl: ${total.value}`, 10, y)
  pdf.text(`Durchschnitt pro Woche: ${avgPerWeek.value}`, 10, y + 8)
  pdf.text(`Top-Fakultät: ${topFaculty.value}`, 10, y + 16)
  y += 28

  // Diagramme: Reihenfolge und Titel wie auf der Seite
  // 1. Wochenübersicht
  pdf.setFontSize(14)
  pdf.text('Wochenübersicht', 10, y)
  y += 6
  weekCharts.value.forEach((weekChart, idx) => {
    const chartRef = document.querySelectorAll('canvas')[idx]
    if (chartRef) {
      if (y + 60 > pdf.internal.pageSize.getHeight()) {
        pdf.addPage()
        y = 15
      }
      pdf.addImage(chartRef.toDataURL('image/png'), 'PNG', 10, y, 90, 40)
      pdf.text(`KW ${weekChart.weekKey}`, 105, y + 20)
      y += 50
    }
  })

  // 2. Verteilung Fachbereiche
  if (y + 60 > pdf.internal.pageSize.getHeight()) {
    pdf.addPage()
    y = 15
  }
  pdf.setFontSize(14)
  pdf.text('Verteilung Fachbereiche', 10, y)
  const facultyCanvas = document.querySelectorAll('canvas')[weekCharts.value.length]
  if (facultyCanvas) {
    pdf.addImage(facultyCanvas.toDataURL('image/png'), 'PNG', 10, y + 5, 90, 40)
  }
  y += 50

  // 3. Verteilung Studiengänge
  if (y + 60 > pdf.internal.pageSize.getHeight()) {
    pdf.addPage()
    y = 15
  }
  pdf.setFontSize(14)
  pdf.text('Verteilung Studiengänge', 10, y)
  const degreeCanvas = document.querySelectorAll('canvas')[weekCharts.value.length + 1]
  if (degreeCanvas) {
    pdf.addImage(degreeCanvas.toDataURL('image/png'), 'PNG', 10, y + 5, 90, 40)
  }
  y += 50

  // 4. Verteilung Themen
  if (y + 60 > pdf.internal.pageSize.getHeight()) {
    pdf.addPage()
    y = 15
  }
  pdf.setFontSize(14)
  pdf.text('Verteilung Themen', 10, y)
  const topicCanvas = document.querySelectorAll('canvas')[weekCharts.value.length + 2]
  if (topicCanvas) {
    pdf.addImage(topicCanvas.toDataURL('image/png'), 'PNG', 10, y + 5, 90, 40)
  }

  return pdf
}

function statisticsExport() {
  const pdf = createStatisticsPdf()
  var pdfBlob = pdf.output('blob')
  var blobURL = URL.createObjectURL(pdfBlob)
  window.open(blobURL, '_blank')
}

</script>

<template>
  <authenticated>
    <div class="pt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="flex justify-between items-center mb-4">
        <div class="flex items-center">
          <p class="text-3xl font-bold mr-4">Statistik</p>
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
          :selectedSemester="selectedSemester"
          :selectedFaculty="selectedFaculty"
          :selectedTopic="selectedTopic"
          @update:semester="handleSemester"
          @update:faculty="handleFaculty"
          @update:topic="handleTopic"
        />
      </div>
      <KPIBar :total="total" :avgPerWeek="avgPerWeek" :topFaculty="topFaculty" />
      <ChartSection title="Wochenübersicht" info="Anzahl der Teilnahmen pro Woche">
        <div class="flex flex-wrap gap-4">
          <div
            v-for="weekChart in weekCharts"
            :key="weekChart.weekKey"
            class="p-6 flex-grow bg-white rounded-xl shadow-md flex justify-center"
          >
            <BarChart :chart-data="weekChart.chartData" :chart-options="weekChart.chartOptions" />
          </div>
        </div>
      </ChartSection>
      <ChartSection title="Verteilung Fachbereiche" info="Teilnahmen nach Fakultät">
        <div class="p-6 bg-white shadow-md rounded-xl">
          <PolarAreaChart :chart-data="facultyChartData" :chart-options="facultyChartOptions" />
        </div>
      </ChartSection>
      <ChartSection title="Verteilung Studiengänge" info="Teilnahmen nach Studiengang">
        <div class="p-6 bg-white shadow-md rounded-xl">
          <BarChart :chart-data="degreeChartData" :chart-options="{ responsive: true, plugins: { legend: { display: false } } }" />
        </div>
      </ChartSection>
      <ChartSection title="Verteilung Themen" info="Teilnahmen nach Thema">
        <div class="p-6 bg-white shadow-md rounded-xl">
          <PieChart :chart-data="topicChartData" :chart-options="topicChartOptions" />
        </div>
      </ChartSection>
    </div>
  </authenticated>
</template>
