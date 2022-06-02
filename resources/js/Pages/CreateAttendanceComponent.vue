<script setup xmlns="http://www.w3.org/1999/html">
import LabelComponent from '@/Components/Label'
import InputComponent from '@/Components/Input'
import SelectComponent from '@/Components/Select'
import ButtonComponent from '@/Components/Button'
import CheckboxButtonComponent from '@/Components/CheckboxButton'
import { useForm } from '@inertiajs/inertia-vue3'

const props = defineProps(['semesters', 'faculties', 'degrees'])

const form = useForm({
  date: null,
  startTime: null,
  endTime: null,
  semester: props.semesters[0]?.semester ?? '',
  degree: '',
  faculty: '',
  mathBasic: false,
  mathLow: false,
  mathHigh: false,
  programming: false,
  physics: false,
  chemistry: false,
  organization: false,
})

const submit = () => {
  const curSem = props.semesters.find(sem => sem.semester === form.semester)
  const semStart = new Date(curSem.start).getTime()
  const semEnd = new Date(curSem.end).getTime()
  const tryDate = new Date(form.date).getTime()

  if ((semStart > tryDate) || (semEnd < tryDate)) {
    if (confirm('Du versuchst ein Termin außerhalb des Semesters zu erstellen. Bist du dir sicher?')) {
      form.post(route('dashboard'))
    }
  } else {
    form.post(route('dashboard'))
  }

  console.log(tryDate, semStart, semEnd)
}
</script>

<template>
  <div class="mb-6">
    <p class="font-bold text-2xl mb-2">
      Neuen Eintrag erstellen
    </p>
    <div class="bg-white rounded-xl shadow-md p-6 border">
      <form @submit.prevent="submit">
        <div class="flex mb-4 gap-4 pr-4 flex-wrap">
          <div class="flex-grow">
            <label-component for="date">
              Datum
            </label-component>
            <input-component
              id="date"
              v-model="form.date"
              type="date"
              class="w-full"
            />
          </div>
          <div class="flex-grow">
            <label-component for="start">
              Von
            </label-component>
            <input-component
              id="start"
              v-model="form.startTime"
              type="time"
              class="w-full"
            />
          </div>
          <div class="flex-grow">
            <label-component for="end">
              Bis
            </label-component>
            <input-component
              id="end"
              v-model="form.endTime"
              type="time"
              class="w-full"
            />
          </div>
          <div class="flex-grow">
            <label-component for="semester">
              Semester
            </label-component>
            <select-component
              id="semester"
              v-model="form.semester"
              class="w-full"
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
        <div class="flex gap-4 mb-4 pr-4">
          <div class="flex-1">
            <label-component for="degree">
              Studiengang
            </label-component>
            <select-component
              id="degree"
              v-model="form.degree"
              class="w-full"
            >
              <option
                v-for="(degree, degreeKey) in degrees"
                :key="degreeKey"
              >
                {{ degree.name }}
              </option>
            </select-component>
          </div>
          <div class="flex-1">
            <label-component for="faculty">
              Fachbereich
            </label-component>
            <select-component
              id="faculty"
              v-model="form.faculty"
              list="faculties"
              class="w-full"
            >
              <option
                v-for="(faculty, facultyKey) in faculties"
                :key="facultyKey"
              >
                {{ faculty.name }}
              </option>
            </select-component>
          </div>
        </div>
        <div class="mb-4">
          <span class="block font-medium text-sm text-gray-700">Themen</span>
          <div class="flex flex-wrap">
            <div class="w-full md:w-1/4">
              <checkbox-button-component
                v-model:checked="form.mathBasic"
              >
                Mathe Schulwissen
              </checkbox-button-component>
            </div>
            <div class="w-full md:w-1/4">
              <checkbox-button-component
                v-model:checked="form.mathLow"
              >
                Mathe Semester 1 und 2
              </checkbox-button-component>
            </div>
            <div class="w-full md:w-1/4">
              <checkbox-button-component
                v-model:checked="form.mathHigh"
              >
                Mathe Semester 3+
              </checkbox-button-component>
            </div>
            <div class="w-full md:w-1/4">
              <checkbox-button-component
                v-model:checked="form.programming"
              >
                Programm.
              </checkbox-button-component>
            </div>
            <div class="w-full md:w-1/4">
              <checkbox-button-component
                v-model:checked="form.physics"
              >
                Physik
              </checkbox-button-component>
            </div>
            <div class="w-full md:w-1/4">
              <checkbox-button-component
                v-model:checked="form.chemistry"
              >
                Chemie
              </checkbox-button-component>
            </div>
            <div class="w-full md:w-1/4">
              <checkbox-button-component
                v-model:checked="form.organization"
              >
                Orga.
              </checkbox-button-component>
            </div>
          </div>
        </div>
        <button-component
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Erstellen
        </button-component>
      </form>
    </div>
  </div>
</template>
