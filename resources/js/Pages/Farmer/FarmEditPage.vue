<template>
  <Head title="Edit Farm" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Farm</h1>
        <p class="text-gray-600">Update your farm information</p>
      </div>
      <a href="/farmer/farms" class="text-blue-600 hover:text-blue-700 font-medium">
        ← Back to Farms
      </a>
    </div>

    <!-- Farm Edit Form -->
    <div class="bg-white rounded-lg shadow p-6">
      <form @submit.prevent="submitForm" class="space-y-6">
        <!-- Farm Name -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Farm Name</label>
          <input
            type="text"
            v-model="form.name"
            placeholder="Enter farm name"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.name }"
          />
          <div v-if="form.errors.name" class="text-sm text-red-500 mt-1">{{ form.errors.name }}</div>
        </div>

        <!-- Farm Type -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Farm Type</label>
          <select
            v-model="form.type"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.type }"
          >
            <option value="">Select farm type...</option>
            <option value="crops">Crops</option>
            <option value="livestock">Livestock</option>
            <option value="mixed">Mixed</option>
          </select>
          <div v-if="form.errors.type" class="text-sm text-red-500 mt-1">{{ form.errors.type }}</div>
        </div>

        <!-- Address -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
          <textarea
            v-model="form.address"
            placeholder="Enter farm address"
            rows="3"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.address }"
          ></textarea>
          <div v-if="form.errors.address" class="text-sm text-red-500 mt-1">{{ form.errors.address }}</div>
        </div>

        <!-- Contact and Size -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Contact</label>
            <input
              type="text"
              v-model="form.contact"
              placeholder="Enter contact information"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.contact }"
            />
            <div v-if="form.errors.contact" class="text-sm text-red-500 mt-1">{{ form.errors.contact }}</div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Size (hectares)</label>
            <input
              type="number"
              v-model="form.size"
              placeholder="Enter farm size"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.size }"
            />
            <div v-if="form.errors.size" class="text-sm text-red-500 mt-1">{{ form.errors.size }}</div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
          <a href="/farmer/farms" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            Cancel
          </a>
          <button
            type="submit"
            :disabled="loading"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
          >
            <div v-if="loading" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
            <span v-if="!loading">Update Farm</span>
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
import DashboardLayout from '../../Shared/DashboardLayout.vue'

defineOptions({
  layout: DashboardLayout
})

const props = defineProps({
  farm: Object
})

const loading = ref(false)

const form = useForm({
  name: props.farm.name,
  type: props.farm.type,
  address: props.farm.address,
  contact: props.farm.contact,
  size: props.farm.size
})

const submitForm = () => {
  loading.value = true
  form.put(`/farmer/farms/${props.farm.id}`, {
    onFinish: () => {
      loading.value = false
    }
  })
}
</script>
