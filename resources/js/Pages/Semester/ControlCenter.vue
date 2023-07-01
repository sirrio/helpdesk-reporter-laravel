<script setup>
import Authenticated from '@/Layouts/Authenticated.vue'
import InputComponent from '@/Components/Input.vue'
import ButtonComponent from '@/Components/Button.vue'
import LabelComponent from '@/Components/Label.vue'
import { defineProps } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

defineProps(['semesters', 'degrees', 'faculties'])

const removeSemester = (id) => {
  router.delete(route('semester'), { data: { id: id } })
}

const createSemester = () => {
  form.post(route('semester'))
}

const form = useForm({
  semester: '',
  start: '',
  end: ''
})
</script>

<template>
  <authenticated>
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <p class="text-3xl font-bold mb-2 mr-4">
          Semester verwalten
        </p>
        <div
          v-for="(semester, semesterKey) in semesters"
          :key="semesterKey"
          class="mb-2"
        >
          <div class="bg-white grid grid-cols-5 shadow-md rounded-xl p-6">
            <div class="flex items-center">
              <span class="text-slate-600">Name:</span>
              <span class="font-bold ml-1">{{ semester.semester }}</span>
            </div>
            <div class="flex items-center">
              <span class="text-slate-600">Startdatum:</span>
              <span class="font-bold ml-1">{{ new Date(semester.start).toLocaleDateString() }}</span>
            </div>
            <div class="flex items-center">
              <span class="text-slate-600">Enddatum:</span>
              <span class="font-bold ml-1">{{ new Date(semester.end).toLocaleDateString() }}</span>
            </div>
            <div class="flex justify-end">
              <!--                <button-component>Bearbeiten</button-component>-->
            </div>
            <div class="flex justify-end">
              <button-component @click="removeSemester(semester.id)">
                Löschen
              </button-component>
            </div>
          </div>
        </div>
        <div class="bg-white shadow-md rounded-xl p-6">
          <form @submit.prevent="createSemester">
            <div class="flex items-end gap-2">
              <div>
                <label-component
                  for="newSemester"
                  value="Neues Semester"
                />
                <input-component
                  id="newSemester"
                  v-model="form.semester"
                  required
                  type="text"
                />
              </div>
              <div>
                <label-component
                  for="newSemesterStart"
                  value="Startdatum"
                />
                <input-component
                  id="newSemesterStart"
                  v-model="form.start"
                  type="date"
                  required
                />
              </div>
              <div>
                <label-component
                  for="newSemesterEnd"
                  value="Enddatum"
                />
                <input-component
                  id="newSemesterEnd"
                  v-model="form.end"
                  type="date"
                  required
                />
              </div>
              <button-component
                class="h-fit"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
              >
                Erstellen
              </button-component>
            </div>
          </form>
        </div>
      </div>
    </div>
  </authenticated>
</template>
