<script setup>
import { ref } from 'vue'
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue'
import BreezeDropdown from '@/Components/Dropdown.vue'
import BreezeDropdownLink from '@/Components/DropdownLink.vue'
import BreezeNavLink from '@/Components/NavLink.vue'
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import NavContainer from '@/Components/NavContainer.vue'
import { Link } from '@inertiajs/inertia-vue3'
import IconChevronDown from '@/Icons/IconChevronDown'

const showingNavigationDropdown = ref(false)
</script>

<template>
  <div
    v-if="!$page.props.auth.user.isMod"
    class="min-h-screen bg-gray-100 text-slate-700 flex items-center justify-center"
  >
    <div class="bg-white shadow-md rounded-xl p-6">
      <p class="text-xl text-center font-bold">
        Du hast dich erfolgreich registiert.
      </p>
      <p>Bitte warte, bis ein Administrator dir die Rechte zugewiesen hat.</p>
    </div>
  </div>
  <div v-else>
    <div class="min-h-screen bg-gray-100 text-slate-700">
      <nav class="bg-white border-b border-gray-100 shadow-md">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <!-- Logo -->
              <div class="shrink-0 flex items-center">
                <Link :href="route('dashboard')">
                  <BreezeApplicationLogo class="block h-9 w-auto" />
                </Link>
              </div>

              <!-- Navigation Links -->
              <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                <BreezeNavLink
                  :href="route('dashboard')"
                  :active="route().current('dashboard')"
                >
                  Anwesenheitsliste
                </BreezeNavLink>
                <BreezeNavLink
                  v-if="$page.props.auth.user.isAdmin"
                  :href="route('statistics')"
                  :active="route().current('statistics')"
                >
                  Statistik
                </BreezeNavLink>
                <BreezeNavLink
                  v-if="$page.props.auth.user.isAdmin"
                  :href="route('user')"
                  :active="route().current('user')"
                >
                  Mitgliederverwaltung
                </BreezeNavLink>
                <nav-container
                  v-if="$page.props.auth.user.isAdmin"
                  :active="route().current('semester') || route().current('faculty') || route().current('degree')"
                >
                  <BreezeDropdown
                    align="right"
                    width="48"
                  >
                    <template #trigger>
                      <span class="inline-flex rounded-md">
                        <button
                          type="button"
                          class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                        >
                          Einstellungen
                          <icon-chevron-down class="h-3 w-3 mb-1 ml-1" />
                        </button>
                      </span>
                    </template>

                    <template #content>
                      <BreezeDropdownLink
                        :href="route('semester')"
                        method="get"
                        as="button"
                      >
                        Semester
                      </BreezeDropdownLink>
                      <BreezeDropdownLink
                        :href="route('degree')"
                        method="get"
                        as="button"
                      >
                        Studiengang
                      </BreezeDropdownLink>
                      <BreezeDropdownLink
                        :href="route('faculty')"
                        method="get"
                        as="button"
                      >
                        Fachbereiche
                      </BreezeDropdownLink>
                    </template>
                  </BreezeDropdown>
                </nav-container>
                <!-- <BreezeNavLink
                  :href="route('contact')"
                  :active="route().current('contact')"
                >
                  Impressum
                </BreezeNavLink> -->
              </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
              <!-- Settings Dropdown -->
              <div class="ml-3 relative">
                <BreezeDropdown
                  align="right"
                  width="48"
                >
                  <template #trigger>
                    <span class="inline-flex rounded-md">
                      <button
                        type="button"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                      >
                        {{ $page.props.auth.user.name }}
                        <icon-chevron-down class="h-3 w-3 mb-1 ml-1" />

                      </button>
                    </span>
                  </template>

                  <template #content>
                    <BreezeDropdownLink
                      :href="route('logout')"
                      method="post"
                      as="button"
                    >
                      Log Out
                    </BreezeDropdownLink>
                  </template>
                </BreezeDropdown>
              </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
              <button
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                @click="showingNavigationDropdown = ! showingNavigationDropdown"
              >
                <svg
                  class="h-6 w-6"
                  stroke="currentColor"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <path
                    :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                  />
                  <path
                    :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
          :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}"
          class="sm:hidden"
        >
          <div class="pt-2 pb-3 space-y-1">
            <BreezeResponsiveNavLink
              :href="route('dashboard')"
              :active="route().current('dashboard')"
            >
              Dashboard
            </BreezeResponsiveNavLink>
          </div>

          <!-- Responsive Settings Options -->
          <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
              <div class="font-medium text-base text-gray-800">
                {{ $page.props.auth.user.name }}
              </div>
              <div class="font-medium text-sm text-gray-500">
                {{ $page.props.auth.user.email }}
              </div>
            </div>

            <div class="mt-3 space-y-1">
              <BreezeResponsiveNavLink
                :href="route('logout')"
                method="post"
                as="button"
              >
                Log Out
              </BreezeResponsiveNavLink>
            </div>
          </div>
        </div>
      </nav>

      <!-- Page Heading -->
      <header
        v-if="$slots.header"
        class="bg-white shadow"
      >
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>
