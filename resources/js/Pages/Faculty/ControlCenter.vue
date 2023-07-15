<script setup>
import Authenticated from '@/Layouts/Authenticated.vue'
import InputComponent from '@/Components/Input.vue'
import ButtonComponent from '@/Components/Button.vue'
import LabelComponent from '@/Components/Label.vue'
import { useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

defineProps(['faculties'])

const removeFaculty = (id) => {
  // eslint-disable-next-line no-undef
  router.delete(route('faculty'), { data: { id: id } })
}

const createFaculty = () => {
  // eslint-disable-next-line no-undef
  form.post(route('faculty'))
}

const form = useForm({
  name: '',
})
</script>

<template>
  <authenticated>
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <p class="text-3xl font-bold mb-2 mr-4">
          Fachbereiche verwalten
        </p>
        <div
          v-for="(faculty, facultyKey) in faculties"
          :key="facultyKey"
          class="mb-2"
        >
          <div class="bg-white grid grid-cols-2 shadow-md rounded-xl p-6">
            <div class="flex items-center">
              <span class="text-slate-600">Name:</span>
              <span class="font-bold ml-1">{{ faculty.name }}</span>
            </div>
            <div class="flex justify-end">
              <button-component @click="removeFaculty(faculty.id)">
                Löschen
              </button-component>
            </div>
          </div>
        </div>
        <div class="bg-white shadow-md rounded-xl p-6">
          <form @submit.prevent="createFaculty">
            <div class="flex items-end gap-2">
              <div>
                <label-component
                  for="newFaculty"
                  value="Neuer Studiengang"
                />
                <input-component
                  id="newFaculty"
                  v-model="form.name"
                  required
                  type="text"
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
