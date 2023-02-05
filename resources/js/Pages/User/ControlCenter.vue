<script setup>
import Authenticated from '@/Layouts/Authenticated'
import CheckboxComponent from '@/Components/Checkbox'
import LabelComponent from '@/Components/Label'
import InputComponent from '@/Components/Input'
import ButtonComponent from '@/Components/Button'
import { Inertia } from '@inertiajs/inertia'
import {useForm} from '@inertiajs/inertia-vue3'

defineProps(['users'])

const form = useForm({
  newPassword: null,
  newPasswordConfirmation: null,
})

const changePassword = (id) => {
  if (form.newPassword === form.newPasswordConfirmation){
    Inertia.post(route('user.password'), { data: { id: id, password: form.newPassword}})
  }else{
    alert('Ungleiche Passwörter!')
  }
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

<script>
import ModalComponent from '@/Components/Modal.vue'

export default {
  components: {
    ModalComponent
  },
  data() {
    return {
      showModal: false
    }
  }
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
              <button-component @click="showModal = true">
                Passwort ändern
              </button-component>

              <modal-component v-if="showModal" @close="showModal = false">
                <template #header>
                  Passwort ändern
                </template>
                <template #body>
                  <form @submit.prevent="changePassword">
                    <div class="grid gap-3">
                      <label-component for="new_password">
                        Passwort
                      </label-component>
                      <input-component
                        id="new_password"
                        v-model="form.newPassword"
                        class="w-full"
                        type="password"
                      />
                      <label-component for="new_password_confirmation">
                        Passwort wiederholen
                      </label-component>
                      <input-component
                        id="new_password_confirmation"
                        v-model="form.newPasswordConfirmation"
                        class="w-full"
                        type="password"
                      />
                    </div>
                  </form>
                </template>
                <template #footer>
                  <div class="flex items-center gap-3 col-span-4 justify-end">
                    <button-component @click="showModal = false">
                      Zurück
                    </button-component>

                    <button-component @click="changePassword(user.id)">
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
