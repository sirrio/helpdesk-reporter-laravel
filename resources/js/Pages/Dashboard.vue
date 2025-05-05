<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import TopicComponent from '@/Pages/TopicComponent.vue'
import CreateAttendanceComponent from '@/Pages/CreateAttendanceComponent.vue'
import SelectComponent from '@/Components/Select.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  attendancesByDay: {
    type: Object,
    required: true
  },
  semesters: {
    type: Array,
    required: true
  },
  currentSem: {
    type: String,
    required: true
  },
  faculties: {
    type: Array,
    required: true
  },
  degrees: {
    type: Array,
    required: true
  }
})

const semester = ref(props.currentSem)
const isLoading = ref(false)
const hasAttendances = computed(() => {
  return Object.keys(props.attendancesByDay).length > 0
})

const currentSemesterId = computed(() => {
  const currentSemObj = props.semesters.find(sem => sem.semester === props.currentSem)
  return currentSemObj ? currentSemObj.id : null
})

const changeSemester = (sem) => {
  if (sem === props.currentSem) return
  
  isLoading.value = true
  // eslint-disable-next-line no-undef
  router.visit(route('dashboard'), {
    data: { 'semester': sem },
    preserveState: false,
    onFinish: () => {
      isLoading.value = false
    }
  })
}

const formatTime = (time) => {
  if (!time) return ''
  return time.substring(0, time.length - 3)
}

const navigateToAttendance = (attendanceId) => {
  // eslint-disable-next-line no-undef
  router.visit(route('attendance', { attendance: attendanceId }))
}
</script>

<template>
  <Head><title>Dashboard</title></Head>

  <BreezeAuthenticatedLayout>
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Create attendance form -->
        <create-attendance-component
          class="mb-12"
          :semesters="semesters"
          :faculties="faculties"
          :degrees="degrees"
        />

        <!-- Header section with semester selector -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
          <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <h1 class="text-2xl sm:text-3xl font-bold">
              Einträge aus {{ currentSem }}
            </h1>
            <a
              v-if="currentSemesterId"
              class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 w-full sm:w-auto"
              :href="route('csv', { semester: currentSemesterId })"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              Download als CSV
            </a>
          </div>

          <div class="flex items-center gap-2">
            <label for="semester-selector" class="text-sm font-medium text-gray-700">Semester:</label>
            <div class="relative">
              <select-component
                id="semester-selector"
                v-model="semester"
                :disabled="isLoading"
                @change="changeSemester(semester)"
                class="min-w-[150px]"
              >
                <option
                  v-for="(sem, semKey) in semesters"
                  :key="semKey"
                >
                  {{ sem.semester }}
                </option>
              </select-component>
              <div v-if="isLoading" class="absolute right-10 top-2">
                <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Loading state -->
        <div v-if="isLoading" class="flex justify-center items-center py-12">
          <svg class="animate-spin h-10 w-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>

        <!-- Empty state -->
        <div v-else-if="!hasAttendances" class="bg-white rounded-xl shadow-md p-8 text-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h3 class="mt-4 text-lg font-medium text-gray-900">Keine Einträge gefunden</h3>
          <p class="mt-1 text-sm text-gray-500">
            Es sind keine Einträge für das ausgewählte Semester {{ currentSem }} vorhanden.
          </p>
        </div>

        <!-- Attendance list by day -->
        <div v-else>
          <div
            v-for="(day, dayKey) in attendancesByDay"
            :key="dayKey"
            class="mb-8"
          >
            <div class="mb-3 flex flex-col sm:flex-row justify-between sm:items-center gap-1 pb-2 border-b">
              <h2 class="text-xl sm:text-2xl font-bold">
                {{
                  new Date(dayKey).toLocaleDateString([], {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                  })
                }}
              </h2>
              <div class="flex items-center gap-2 text-sm sm:text-base">
                <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full font-medium">
                  {{ day.length }} {{ day.length === 1 ? 'Besucher' : 'Besucher' }}
                </span>
              </div>
            </div>

            <!-- Attendance cards -->
            <div class="space-y-3">
              <div
                v-for="(attendance, attendanceKey) in day"
                :key="attendanceKey"
                class="bg-white hover:bg-slate-50 hover:cursor-pointer rounded-xl shadow-md border p-4 sm:p-6 transition-all duration-200"
                @click="navigateToAttendance(attendance.id)"
              >
                <!-- Header with time and user info -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-3">
                  <div class="flex items-center gap-2">
                    <span
                      class="inline-block px-2 py-1 text-xs font-medium rounded-full"
                      :class="{
                        'bg-blue-100 text-blue-800': !attendance.remote,
                        'bg-green-100 text-green-800': attendance.remote
                      }"
                    >
                      {{ attendance.remote ? "Remote" : "Präsenz" }}
                    </span>
                    <div class="text-lg sm:text-xl font-semibold">
                      {{ formatTime(attendance.startTime) }} - {{ formatTime(attendance.endTime) }}
                    </div>
                  </div>

                  <div class="text-slate-600">
                    <span class="font-medium">{{ attendance.user.name }}</span> - {{ attendance.semester }}
                  </div>
                </div>

                <!-- Degree and faculty info -->
                <div class="flex flex-wrap gap-1 items-center mb-3 text-sm sm:text-base">
                  <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-md">
                    {{ attendance.degree }}
                  </span>
                  <span class="text-gray-500">-</span>
                  <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-md">
                    {{ attendance.faculty }}
                  </span>
                </div>

                <!-- Topics -->
                <div class="flex flex-wrap gap-2 text-sm">
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
                    Programmierung
                  </topic-component>
                  <topic-component :topic="attendance.physics">
                    Physik
                  </topic-component>
                  <topic-component :topic="attendance.chemistry">
                    Chemie
                  </topic-component>
                  <topic-component :topic="attendance.organization">
                    Organisation
                  </topic-component>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>

<style scoped>
.topic-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.25rem 0.5rem;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  font-weight: 500;
}

@media (max-width: 640px) {
  .topic-badge {
    font-size: 0.75rem;
  }
}
</style>
