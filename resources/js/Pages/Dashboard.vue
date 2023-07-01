<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3'
import { defineProps, ref } from 'vue'
import TopicComponent from '@/Pages/TopicComponent.vue'
import CreateAttendanceComponent from '@/Pages/CreateAttendanceComponent.vue'
import SelectComponent from '@/Components/Select.vue'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps([
  'attendancesByDay',
  'semesters',
  'currentSem',
  'faculties',
  'degrees'
])

const semester = ref(props.currentSem)

const changeSemester = (sem) => {
  console.log(sem)
  Inertia.visit(route('dashboard'),{ data: { 'semester': sem } })
}

console.log(props.attendancesByDay)
</script>

<template>
  <Head title="Dashboard" />

  <BreezeAuthenticatedLayout>
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <create-attendance-component
          class="mb-12"
          :semesters="semesters"
          :faculties="faculties"
          :degrees="degrees"
        />
        <div class="flex justify-between mb-6">
          <div class="flex items-center">
            <p class="text-3xl font-bold mr-4">
              Alle Einträge aus {{ currentSem }}
            </p>
            <a
              class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
              :href="route('csv', { semester: semesters.find(sem=> sem.semester ===currentSem).id})"
            >
              Download {{ currentSem }} als CSV
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
        <div
          v-for="(day, dayKey) in attendancesByDay"
          :key="dayKey"
          class="mb-6"
        >
          <div class="mb-2">
            <span class="text-2xl font-bold mr-4">
              {{
                new Date(dayKey).toLocaleDateString([], {
                  weekday: 'long',
                  year: 'numeric',
                  month: 'long',
                  day: 'numeric'
                })
              }}
            </span>
            <span class="text-slate-700">Besucher: {{ day.length }}</span>
          </div>
          <div
            v-for="(attendance, attendenceKey) in day"
            :key="attendenceKey"
            class="bg-white hover:bg-slate-50 hover:cursor-pointer rounded-xl shadow-md border mb-2 p-6"
            @click="$inertia.visit(route('attendance', { attendance : attendance.id }) )"
          >
            <div class="flex justify-between mb-1">
              <div class="text-xl font-semibold">
                {{ attendance.startTime.substring(0, attendance.startTime.length - 3) }} -
                {{ attendance.endTime.substring(0, attendance.endTime.length - 3) }}
              </div>
              <div class="text-slate-600">
                {{ attendance.user.name }} - {{ attendance.semester }}
              </div>
            </div>
            <div class="mb-1">
              {{ attendance.degree }} - {{ attendance.faculty }}
            </div>
            <div class="flex flex-wrap text-sm text-slate-700">
              <topic-component :topic="attendance.mathBasic">
                Mathe Schulwissen
              </topic-component>
              <topic-component :topic="attendance.mathFractions">
                Mathe Bruchrechnen
              </topic-component>
              <topic-component :topic="attendance.mathLow">
                Mathe Semester 1 und 2
              </topic-component>
              <topic-component :topic="attendance.mathHigh">
                Mathe Semester 3+
              </topic-component>
              <topic-component :topic="attendance.programming">
                Programm
              </topic-component>
              <topic-component :topic="attendance.physics">
                Physik
              </topic-component>
              <topic-component :topic="attendance.chemistry">
                Chemie
              </topic-component>
              <topic-component :topic="attendance.organization">
                Orga.
              </topic-component>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
