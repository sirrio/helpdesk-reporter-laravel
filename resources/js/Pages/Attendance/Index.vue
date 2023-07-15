<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { defineProps } from 'vue'
import ButtonComponent from '@/Components/Button.vue'
import LabelComponent from '@/Components/Label.vue'
import SelectComponent from '@/Components/Select.vue'
import InputComponent from '@/Components/Input.vue'
import CheckboxButtonComponent from '@/Components/CheckboxButton.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps(['attendance', 'semesters', 'faculties', 'degrees',])

const form = useForm({
  date: props.attendance.date,
  startTime: props.attendance.startTime,
  endTime: props.attendance.endTime,
  semester: props.attendance.semester,
  degree: props.attendance.degree,
  faculty: props.attendance.faculty,
  mathBasic: props.attendance.mathBasic === 1,
  mathFractions: props.attendance.mathFractions === 1,
  mathLow: props.attendance.mathLow === 1,
  mathHigh: props.attendance.mathHigh === 1,
  programming: props.attendance.programming === 1,
  physics: props.attendance.physics === 1,
  chemistry: props.attendance.chemistry === 1,
  organization: props.attendance.organization === 1,
})

const submit = () => {
  form.post(route('attendance', route().params ))
}

const remove = () => {
  if (confirm('Wirklich löschen?')) {
    router.delete(route('attendance.delete'), { data: { id: route().params.attendance } })
  }
}
</script>

<template>
  <Head title="Dashboard" />

  <BreezeAuthenticatedLayout>
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
          <p class="font-bold text-2xl mb-2">
            Eintrag ändern
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
                      v-model:checked="form.mathFractions"
                    >
                      Mathe Bruchrechnung
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
              <div class="flex justify-between">
                <button-component
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                >
                  Aktualisieren
                </button-component>
                <button-component
                  class="bg-red-500"
                  @click.prevent="remove()"
                >
                  Löschen
                </button-component>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
