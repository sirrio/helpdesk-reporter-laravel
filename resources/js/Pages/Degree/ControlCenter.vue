<script setup>
import Authenticated from '@/Layouts/Authenticated.vue'
import InputComponent from '@/Components/Input.vue'
import ButtonComponent from '@/Components/Button.vue'
import LabelComponent from '@/Components/Label.vue'
import { defineProps } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

defineProps(['degrees'])

const removeDegree = (id) => {
  Inertia.delete(route('degree'), { data: { id: id } })
}

const createDegree = () => {
  form.post(route('degree'))
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
          Studiengänge verwalten
        </p>
        <div
          v-for="(degree, degreeKey) in degrees"
          :key="degreeKey"
          class="mb-2"
        >
          <div class="bg-white grid grid-cols-2 shadow-md rounded-xl p-6">
            <div class="flex items-center">
              <span class="text-slate-600">Name:</span>
              <span class="font-bold ml-1">{{ degree.name }}</span>
            </div>
            <div class="flex justify-end">
              <button-component @click="removeDegree(degree.id)">
                Löschen
              </button-component>
            </div>
          </div>
        </div>
        <div class="bg-white shadow-md rounded-xl p-6">
          <form @submit.prevent="createDegree">
            <div class="flex items-end gap-2">
              <div>
                <label-component
                  for="newDegree"
                  value="Neuer Studiengang"
                />
                <input-component
                  id="newDegree"
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
