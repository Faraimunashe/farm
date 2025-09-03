<template>
  <Head title="Profile & Settings" />

  <div class="space-y-6">
    <!-- Header -->
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Profile & Settings</h1>
      <p class="text-gray-600">Manage your lender profile and account settings</p>
    </div>

    <!-- Profile Information -->
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Information</h3>
      <form @submit.prevent="updateProfile" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
            <input
              v-model="profileForm.name"
              type="text"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
            <input
              v-model="profileForm.email"
              type="email"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
            >
          </div>
        </div>

        <div class="flex justify-end">
          <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
            <i class="fa fa-save mr-2"></i>Update Profile
          </button>
        </div>
      </form>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h3>
      <form @submit.prevent="updatePassword" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
          <input
            v-model="passwordForm.current_password"
            type="password"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            required
          >
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
            <input
              v-model="passwordForm.password"
              type="password"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
              minlength="8"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
            <input
              v-model="passwordForm.password_confirmation"
              type="password"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
              minlength="8"
            >
          </div>
        </div>

        <div class="flex justify-end">
          <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600">
            <i class="fa fa-key mr-2"></i>Change Password
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import LenderLayout from '../../Shared/LenderLayout.vue'
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

defineOptions({
  layout: LenderLayout
})

const props = defineProps({
  lender: Object,
  user: Object
})

const profileForm = ref({
  name: '',
  email: ''
})

const passwordForm = ref({
  current_password: '',
  password: '',
  password_confirmation: ''
})

onMounted(() => {
  if (props.lender) {
    profileForm.value = {
      name: props.lender.name || '',
      email: props.user?.email || ''
    }
  }
})

const updateProfile = () => {
  router.post('/lender/profile', profileForm.value)
}

const updatePassword = () => {
  if (passwordForm.value.password !== passwordForm.value.password_confirmation) {
    alert('Passwords do not match')
    return
  }

  router.post('/lender/password', {
    current_password: passwordForm.value.current_password,
    password: passwordForm.value.password,
    password_confirmation: passwordForm.value.password_confirmation
  })

  // Reset form
  passwordForm.value = {
    current_password: '',
    password: '',
    password_confirmation: ''
  }
}
</script>
