<script setup>
import Authenticated from '@/Layouts/Authenticated.vue'
import CheckboxComponent from '@/Components/Checkbox.vue'
import LabelComponent from '@/Components/Label.vue'
import InputComponent from '@/Components/Input.vue'
import ButtonComponent from '@/Components/Button.vue'
import ModalComponent from '@/Components/Modal.vue'
import { router } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  users: {
    type: Array,
    required: true
  },
  errors: Object
})

// State management
const showPasswordModal = ref(false)
const showDeleteModal = ref(false)
const selectedUserId = ref(null)
const isLoading = ref({
  admin: null,
  mod: null,
  delete: null
})
const notification = ref({
  show: false,
  type: 'success',
  message: ''
})

// User being currently modified
const selectedUser = computed(() => {
  return props.users.find(user => user.id === selectedUserId.value)
})

const passwordForm = useForm({
  id: null,
  password: null,
  password_confirmation: null,
})

// Show notification for 3 seconds
function showNotification(type, message) {
  notification.value = {
    show: true,
    type,
    message
  }
  
  setTimeout(() => {
    notification.value.show = false
  }, 3000)
}

// Password change handling
function openPasswordModal(userId) {
  selectedUserId.value = userId
  passwordForm.id = userId
  showPasswordModal.value = true
}

function closePasswordModal() {
  showPasswordModal.value = false
  passwordForm.reset().clearErrors()
}

function changePassword() {
  // eslint-disable-next-line no-undef
  passwordForm.post(route('user.password'), {
    onSuccess: () => {
      closePasswordModal()
      showNotification('success', 'Passwort wurde erfolgreich geändert')
    },
    onError: () => {
      showNotification('error', 'Passwort konnte nicht geändert werden')
    }
  })
}

// User deletion handling
function openDeleteModal(userId) {
  selectedUserId.value = userId
  showDeleteModal.value = true
}

function closeDeleteModal() {
  showDeleteModal.value = false
}

function removeUser() {
  isLoading.value.delete = selectedUserId.value

  // eslint-disable-next-line no-undef
  router.delete(route('user'), { 
    data: { id: selectedUserId.value },
    onSuccess: () => {
      closeDeleteModal()
      showNotification('success', 'Benutzer wurde erfolgreich gelöscht')
      isLoading.value.delete = null
    },
    onError: () => {
      closeDeleteModal()
      showNotification('error', 'Benutzer konnte nicht gelöscht werden')
      isLoading.value.delete = null
    }
  })
}

// Admin status toggle
function changeAdmin(id) {
  isLoading.value.admin = id

  // eslint-disable-next-line no-undef
  router.post(route('user.admin'), { 
    id: id 
  }, {
    onSuccess: () => {
      isLoading.value.admin = null
      showNotification('success', 'Administratorrechte wurden aktualisiert')
    },
    onError: () => {
      isLoading.value.admin = null
      showNotification('error', 'Administratorrechte konnten nicht aktualisiert werden')
    }
  })
}

// Moderator status toggle
function changeMod(id) {
  isLoading.value.mod = id

  // eslint-disable-next-line no-undef
  router.post(route('user.mod'), { 
    id: id 
  }, {
    onSuccess: () => {
      isLoading.value.mod = null
      showNotification('success', 'Tutorrechte wurden aktualisiert')
    },
    onError: () => {
      isLoading.value.mod = null
      showNotification('error', 'Tutorrechte konnten nicht aktualisiert werden')
    }
  })
}
</script>


<template>
  <authenticated>
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-3xl font-bold">
            Mitglieder verwalten
          </h1>
          <div class="text-sm text-gray-500">{{ users.length }} Benutzer</div>
        </div>

        <!-- User cards -->
        <div class="space-y-4">
          <div
            v-for="(user, userKey) in users"
            :key="userKey"
            class="bg-white shadow-md rounded-xl p-4 sm:p-6 hover:shadow-lg transition-shadow duration-200"
          >
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
              <!-- User info -->
              <div class="sm:col-span-8 space-y-2">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                  <div class="min-w-[80px] text-slate-600">Name:</div>
                  <div class="font-bold">{{ user.name }}</div>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                  <div class="min-w-[80px] text-slate-600">E-Mail:</div>
                  <div class="font-bold break-all">{{ user.email }}</div>
                </div>
                <div class="flex flex-wrap gap-6 mt-3">
                  <div v-if="user.name !== 'admin'" class="flex items-center gap-2">
                    <div class="text-slate-600">Admin:</div>
                    <div class="relative">
                      <checkbox-component
                        :checked="user.isAdmin"
                        :disabled="isLoading.admin === user.id"
                        @change="changeAdmin(user.id)"
                      />
                      <div v-if="isLoading.admin === user.id" class="absolute -top-1 -right-6">
                        <svg class="animate-spin h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div v-if="user.name !== 'admin'" class="flex items-center gap-2">
                    <div class="text-slate-600">Tutor:</div>
                    <div class="relative">
                      <checkbox-component
                        :checked="user.isMod"
                        :disabled="isLoading.mod === user.id"
                        @change="changeMod(user.id)"
                      />
                      <div v-if="isLoading.mod === user.id" class="absolute -top-1 -right-6">
                        <svg class="animate-spin h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Action buttons -->
              <div class="sm:col-span-4 flex flex-wrap sm:justify-end gap-3 items-center mt-4 sm:mt-0">
                <button-component
                  class="w-full sm:w-auto"
                  type="button"
                  @click="openPasswordModal(user.id)"
                >
                  Passwort ändern
                </button-component>

                <button-component
                  class="w-full sm:w-auto"
                  type="button"
                  :disabled="user.name === 'admin'"
                  :class="{'opacity-50 cursor-not-allowed': user.name === 'admin'}"
                  @click="user.name !== 'admin' && openDeleteModal(user.id)"
                >
                  Löschen
                </button-component>
              </div>
            </div>
          </div>
        </div>

        <!-- Password change modal -->
        <modal-component v-if="showPasswordModal && selectedUser">
          <template #header>
            <span class="font-bold">Passwort ändern für {{ selectedUser.name }}</span>
          </template>
          <template #body>
            <form @submit.prevent="changePassword">
              <div class="grid gap-4">
                <div>
                  <label-component for="password">Passwort</label-component>
                  <input-component
                    id="password"
                    v-model="passwordForm.password"
                    class="w-full mt-1"
                    :class="{'border-red-500': passwordForm.errors.password}"
                    type="password"
                    autocomplete="new-password"
                  />
                </div>

                <div>
                  <label-component for="password_confirmation">Passwort wiederholen</label-component>
                  <input-component
                    id="password_confirmation"
                    v-model="passwordForm.password_confirmation"
                    class="w-full mt-1"
                    :class="{'border-red-500': passwordForm.errors.password_confirmation}"
                    type="password"
                    autocomplete="new-password"
                  />
                </div>
                
                <div v-if="passwordForm.errors.password" class="text-red-500 text-sm">
                  {{ passwordForm.errors.password }}
                </div>
                <div v-if="passwordForm.errors.password_confirmation" class="text-red-500 text-sm">
                  {{ passwordForm.errors.password_confirmation }}
                </div>
              </div>
            </form>
          </template>
          <template #footer>
            <div class="flex justify-end gap-3">
              <button-component
                type="button"
                @click="closePasswordModal"
              >
                Abbrechen
              </button-component>

              <button-component
                type="submit"
                :disabled="passwordForm.processing"
                class="bg-indigo-600 hover:bg-indigo-700"
                @click="changePassword"
              >
                <svg v-if="passwordForm.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Speichern
              </button-component>
            </div>
          </template>
        </modal-component>

        <!-- Delete confirmation modal -->
        <modal-component v-if="showDeleteModal && selectedUser">
          <template #header>
            <span class="font-bold text-red-600">Benutzer löschen</span>
          </template>
          <template #body>
            <div class="text-center">
              <svg class="mx-auto h-12 w-12 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              <h3 class="mt-5 text-lg font-medium text-gray-900">Benutzer löschen bestätigen</h3>
              <div class="mt-2 text-sm text-gray-500">
                <p>Möchten Sie wirklich den Benutzer <strong>{{ selectedUser.name }}</strong> löschen?</p>
                <p class="mt-2">Diese Aktion kann nicht rückgängig gemacht werden!</p>
              </div>
            </div>
          </template>
          <template #footer>
            <div class="flex justify-end gap-3">
              <button-component
                type="button"
                @click="closeDeleteModal"
              >
                Abbrechen
              </button-component>

              <button-component
                type="button"
                class="bg-red-600 hover:bg-red-700"
                :disabled="isLoading.delete === selectedUserId"
                @click="removeUser"
              >
                <svg v-if="isLoading.delete === selectedUserId" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Löschen
              </button-component>
            </div>
          </template>
        </modal-component>

        <!-- Notification toast -->
        <div 
          v-if="notification.show" 
          class="fixed bottom-4 right-4 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-y-0"
          :class="{
            'bg-green-100 border-l-4 border-green-600 text-green-800': notification.type === 'success',
            'bg-red-100 border-l-4 border-red-600 text-red-800': notification.type === 'error'
          }"
        >
          <div class="flex items-center">
            <div v-if="notification.type === 'success'" class="mr-3">
              <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <div v-if="notification.type === 'error'" class="mr-3">
              <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </div>
            <p>{{ notification.message }}</p>
          </div>
        </div>
      </div>
    </div>
  </authenticated>
</template>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeOut {
  from { opacity: 1; transform: translateY(0); }
  to { opacity: 0; transform: translateY(10px); }
}

.notification {
  animation: fadeIn 0.3s ease-out forwards, fadeOut 0.5s ease-in forwards 2.5s;
  z-index: 50;
}
</style>
