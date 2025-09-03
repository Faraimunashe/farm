<template>
  <Head title="Insurer Profile" />

  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Insurer Profile</h1>
        <p class="text-gray-600">Manage your company information</p>
      </div>
    </div>

    <!-- Success Message -->
    <div v-if="$page.props.flash && $page.props.flash.success" class="bg-green-50 border border-green-200 rounded-lg p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-green-800">{{ $page.props.flash.success }}</p>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <form @submit.prevent="submitForm" class="space-y-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Company Name</label>
          <input
            type="text"
            v-model="form.name"
            placeholder="Enter your company name"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.name }"
          />
          <div v-if="form.errors.name" class="text-sm text-red-500 mt-1">{{ form.errors.name }}</div>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Company Address</label>
          <textarea
            v-model="form.address"
            placeholder="Enter your company address"
            rows="3"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.address }"
          ></textarea>
          <div v-if="form.errors.address" class="text-sm text-red-500 mt-1">{{ form.errors.address }}</div>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Information</label>
          <input
            type="text"
            v-model="form.contact"
            placeholder="Enter contact phone or email"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.contact }"
          />
          <div v-if="form.errors.contact" class="text-sm text-red-500 mt-1">{{ form.errors.contact }}</div>
        </div>

        <div class="flex justify-end">
          <button
            type="submit"
            :disabled="loading"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
          >
            <div v-if="loading" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
            <span v-if="!loading">Update Profile</span>
            <span v-else>Updating...</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import InsurerLayout from '../../Shared/InsurerLayout.vue'

defineOptions({
  layout: InsurerLayout
})

const props = defineProps({
  insurer: Object
})

const loading = ref(false)

const form = useForm({
  name: props.insurer?.name || '',
  address: props.insurer?.address || '',
  contact: props.insurer?.contact || ''
})

const submitForm = () => {
  loading.value = true
  form.post('/insurer/profile', {
    onFinish: () => {
      loading.value = false
    }
  })
}
</script>
