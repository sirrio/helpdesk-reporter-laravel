<script setup>
import { computed } from 'vue'
import BreezeButton from '@/Components/Button.vue'
import BreezeGuestLayout from '@/Layouts/Guest.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  status: String,
})

const form = useForm(null)

const submit = () => {
  // eslint-disable-next-line no-undef
  form.post(route('verification.send'))
}

const verificationLinkSent = computed(() => props.status === 'verification-link-sent')
</script>

<template>
  <BreezeGuestLayout>
    <Head><title>Email Verification</title></Head>

    <div class="mb-4 text-sm text-gray-600">
      Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
    </div>

    <div
      v-if="verificationLinkSent"
      class="mb-4 font-medium text-sm text-green-600"
    >
      A new verification link has been sent to the email address you provided during registration.
    </div>

    <form @submit.prevent="submit">
      <div class="mt-4 flex items-center justify-between">
        <BreezeButton
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Resend Verification Email
        </BreezeButton>

        <Link
          :href="route('logout')"
          method="post"
          as="object"
          class="underline text-sm text-gray-600 hover:text-gray-900"
        >
          Log Out
        </Link>
      </div>
    </form>
  </BreezeGuestLayout>
</template>
