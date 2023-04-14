<script setup>
import Authenticated from '@/Layouts/Authenticated'
import CheckboxComponent from '@/Components/Checkbox'
import LabelComponent from '@/Components/Label'
import InputComponent from '@/Components/Input'
import ButtonComponent from '@/Components/Button'
import ModalComponent from '@/Components/Modal.vue'
import { Inertia } from '@inertiajs/inertia'
import {useForm} from '@inertiajs/inertia-vue3'
import {ref} from 'vue'

defineProps(['users'])

let showModal = ref(false)

const form = useForm({
  id: null,
  password: null,
  password_confirmation: null,
})

const changePassword = (id) => {
  form.id = id
  form.post(route('user.password'), {
    onSuccess: () => {
      showModal.value = false
    },
  })
}

const removeUser = (id) => {
  Inertia.delete(route('user'), { data: { id: id } })
}

const changeAdmin = (id) => {
  Inertia.post(route('user.admin'), { id: id })
}

const changeMod = (id) => {
  Inertia.post(route('user.mod'), { id: id })
}

</script>


<template>
  <authenticated>
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <p class="text-3xl font-bold mb-2 mr-4">
          Mitglieder verwalten
        </p>
        <div
          v-for="(user, userKey) in users"
          :key="userKey"
          class="mb-2"
        >
          <div class="bg-white grid grid-cols-12 gap-3 shadow-md rounded-xl p-6">
            <div class="flex items-center col-span-2">
              <span class="text-slate-600">Name:</span>
              <span class="font-bold ml-1">{{ user.name }}</span>
            </div>
            <div class="flex items-center col-span-3">
              <span class="text-slate-600">E-Mail:</span>
              <span class="font-bold ml-1">{{ user.email }}</span>
            </div>
            <div class="flex items-center">
              <div v-if="user.name !== 'admin'">
                <span class="text-slate-600">Admin:</span>
                <span class="font-bold ml-1">
                  <checkbox-component
                    :checked="user.isAdmin"
                    @change="changeAdmin(user.id)"
                  />
                </span>
              </div>
            </div>
            <div class="flex items-center">
              <div v-if="user.name !== 'admin'">
                <span class="text-slate-600">Tutor:</span>
                <span class="font-bold ml-1">
                  <checkbox-component
                    :checked="user.isMod"
                    @change="changeMod(user.id)"
                  />
                </span>
              </div>
            </div>
            <div class="flex items-center gap-3 col-span-4 justify-end">
              <button-component
                @click="showModal = true"
              >
                Passwort ändern
              </button-component>

              <modal-component v-if="showModal">
                <template #header>
                  <span class="font-bold ml-1">Passwort ändern</span>
                </template>
                <template #body>
                  <form>
                    <div class="grid gap-3">
                      <label-component for="password">
                        Passwort
                      </label-component>
                      <input-component
                        v-model="form.password"
                        class="w-full"
                        :class="{'border-red-300': form.errors.password}"
                        type="password"
                      />

                      <label-component for="password_confirmation">
                        Passwort wiederholen
                      </label-component>
                      <input-component
                        v-model="form.password_confirmation"
                        class="w-full"
                        :class="{'border-red-300': form.errors.password}"
                        type="password"
                        @keyup.enter="changePassword(user.id)"
                      />
                      <span class="flex justify-start text-red-600 text-xs"> {{ form.errors.password }} </span>
                    </div>
                  </form>
                </template>
                <template #footer>
                  <div class="flex items-center gap-3 col-span-4 justify-end">
                    <button-component
                      @click="showModal = false,
                              form.reset().clearErrors()"
                    >
                      Zurück
                    </button-component>

                    <button-component
                      type="submit"
                      :disabled="form.processing"
                      @click="changePassword(user.id)"
                    >
                      Speichern
                    </button-component>
                  </div>
                </template>
              </modal-component>

              <button-component @click="removeUser(user.id)">
                Löschen
              </button-component>
            </div>
          </div>
        </div>
      </div>
    </div>
  </authenticated>
</template>
