<script setup>
import Authenticated from '@/Layouts/Authenticated.vue'
import SelectComponent from '@/Components/Select.vue'
import Chart from 'chart.js/auto'
import { onMounted, defineProps, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'

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
  console.log(sem)
  Inertia.visit(route('statistics'), { data: { 'semester': sem } })
}

const objectMap = (obj, fn) =>
  Object.fromEntries(
    Object.entries(obj).map(
      ([k, v], i) => [k, fn(v, k, i)]
    )
  )

onMounted(() => {
  for (const week in props.attendancesByWeek) {
    console.log(week, props.attendancesByWeek[week])
    const ctx = document.getElementById('week' + week).getContext('2d')

    const prepedData = objectMap(props.attendancesByWeek[week], array => array.length)

    console.log(prepedData)

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

</script>

<template>
  <authenticated>
    <div class="pt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="flex justify-between">
        <p class=" text-3xl font-bold mr-4">
          Statistik
        </p>
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

