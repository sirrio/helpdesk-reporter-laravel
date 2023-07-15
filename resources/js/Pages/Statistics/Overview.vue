<script setup>
import Authenticated from '@/Layouts/Authenticated.vue'
import SelectComponent from '@/Components/Select.vue'
import Chart from 'chart.js/auto'
import { onMounted, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import jsPDF from 'jspdf'

const props = defineProps([
  'attendancesByWeek',
  'attendancesByFaculty',
  'attendancesByDegree',
  'attendancesByTopic',
  'semesters',
  'currentSem'
])

const semester = ref(props.currentSem)

const changeSemester = (sem) => {
  // eslint-disable-next-line no-undef
  router.visit(route('statistics'), { data: { 'semester': sem } })
}

const objectMap = (obj, fn) =>
  Object.fromEntries(
    Object.entries(obj).map(
      ([k, v], i) => [k, fn(v, k, i)]
    )
  )

onMounted(() => {
  for (const week in props.attendancesByWeek) {
    const ctx = document.getElementById('week' + week).getContext('2d')

    const prepedData = objectMap(props.attendancesByWeek[week], array => array.length)


    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
        datasets: [{
          data: prepedData,
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 1,
          minBarLength: 10
        }]
      },
      options: {
        responsive: false,
        maintainAspectRatio: false,
        aspectRatio: 0,
        scales: {
          y: {
            min: 0,
            max: 20,
          }
        },
        plugins: {
          legend: {
            display: false
          },
          title: {
            display: true,
            text: `KW ${week}`
          }
        }
      }
    })
  }


  let data1 = [],
    data2 = []

  for (let property in props.attendancesByFaculty) {
    data1.push(property)
    data2.push(props.attendancesByFaculty[property])
  }
  const ctxFaculty = document.getElementById('attendancesByFaculty').getContext('2d')
  new Chart(ctxFaculty, {
    type: 'polarArea',
    data: {
      labels: data1,
      datasets: [{
        data: data2,
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
        borderWidth: 1,
      }]
    },
  })

  let data3 = [],
    data4 = []
  for (let property in props.attendancesByDegree) {
    data3.push(property)
    data4.push(props.attendancesByDegree[property])
  }
  console.log(props.attendancesByDegree)
  const ctxDegree = document.getElementById('attendancesByDegree').getContext('2d')
  new Chart(ctxDegree, {
    type: 'bar',
    data: {
      labels: data3,
      datasets: [{
        data: data4,
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
        borderWidth: 1,
      }]
    },
    options: {
      plugins: {
        legend: {
          display: false
        },
      }
    }
  })

  let data5 = [],
    data6 = []

  for (let property in props.attendancesByTopic) {
    data5.push(property)
    data6.push(props.attendancesByTopic[property])
  }
  const ctxTopic = document.getElementById('attendancesByTopic').getContext('2d')
  new Chart(ctxTopic,{
    type: 'pie',
    data: {
      labels: data5,
      datasets: [{
        data: data6,
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
        borderWidth: 1,
      }]
    },
    options: {
      plugins: {
        legend: {
          display: true
        },
      }
    }
  })

})

function addAttendancesToPdf(pdf) {


  const canvasSize = 50
  const spacing = 15

  let currentRow = 0
  let currentColumn = 0
  let currentPage = 0

  pdf.text('Wochenübersicht', spacing, 10)
  for (const week in props.attendancesByWeek) {
    const canvas = document.getElementById('week' + week)
    const imgData = canvas.toDataURL('image/png')

    const x = spacing + currentColumn * (canvasSize + spacing)
    const y = spacing + currentRow * (canvasSize + spacing) + (currentPage * pdf.internal.pageSize.getHeight())

    const aspectRatio = canvas.width / canvas.height
    const adjustedWidth = canvasSize
    const adjustedHeight = canvasSize / aspectRatio

    pdf.addImage(imgData, 'PNG', x, y, adjustedWidth, adjustedHeight)

    currentColumn++
    if (x + adjustedWidth >= pdf.internal.pageSize.getWidth()) {
      currentRow++
      currentColumn = 0
    }
    if (y + adjustedHeight >= pdf.internal.pageSize.getHeight()) {
      currentRow = 0
      currentColumn = 0
      pdf.addPage()
      currentPage++
    }
  }
}

function pdfTest() {
  const pdf = new jsPDF()
  addAttendancesToPdf(pdf)
  pdf.save('canvas.pdf')
}











</script>

<template>
  <authenticated>
    <div class="pt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="flex justify-between">
        <div class="flex items-center">
          <p class="text-3xl font-bold mr-4">
            Statistik
          </p>
          <a
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
            @click="pdfTest"
          >
            Download {{ currentSem }} als PDF
          </a>
        </div>
        <div>
          <select-component
            v-model="semester"
            @change="changeSemester(semester)"
          >
            <option
              v-for="(sem, semKey) in semesters"
              :key="semKey"
            >
              {{ sem.semester }}
            </option>
          </select-component>
        </div>
      </div>
      <p class="text-2xl font-bold mb-2 mt-4 mr-4">
        Wochenübersicht
      </p>
      <div class="flex flex-wrap gap-4">
        <div
          v-for="(week, weekKey) in attendancesByWeek"
          :key="weekKey"
          class="p-6 flex-grow bg-white rounded-xl shadow-md flex justify-center"
        >
          <canvas
            :id="'week' + weekKey"
            height="400"
          />
        </div>
      </div>
      <p class="text-2xl font-bold mt-4 mb-2 mr-4">
        Verteilung Fachbereiche
      </p>
      <div class="p-6 bg-white shadow-md rounded-xl">
        <canvas
          id="attendancesByFaculty"
          height="400"
          width="400"
        />
      </div>
      <p class="text-2xl font-bold mt-4 mb-2 mr-4">
        Verteilung Studiengänge
      </p>
      <div class="p-6 bg-white shadow-md rounded-xl">
        <canvas
          id="attendancesByDegree"
          height="400"
          width="400"
        />
      </div>
      <p class="text-2xl font-bold mt-4 mb-2 mr-4">
        Verteilung Themen
      </p>
      <div class="p-6 bg-white shadow-md rounded-xl">
        <canvas
          id="attendancesByTopic"
          height="400"
          width="400"
        />
      </div>
    </div>
  </authenticated>
</template>

