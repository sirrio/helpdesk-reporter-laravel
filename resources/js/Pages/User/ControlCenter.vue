<script setup>
import Authenticated from '@/Layouts/Authenticated'
import CheckboxComponent from '@/Components/Checkbox'
import LabelComponent from '@/Components/Label'
import ButtonComponent from '@/Components/Button'
import { Inertia } from '@inertiajs/inertia'

defineProps(['users'])

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
          <div class="bg-white grid grid-cols-8 shadow-md rounded-xl p-6">
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
            <div class="flex justify-end">
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
