<template>
  <Head title="Edit Insurance" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Insurance Policy</h1>
        <p class="text-gray-600">Update your insurance policy details</p>
      </div>
      <a href="/farmer/insurance" class="text-blue-600 hover:text-blue-700 font-medium">
        ← Back to Insurance
      </a>
    </div>

    <!-- Insurance Edit Form -->
    <div class="bg-white rounded-lg shadow p-6">
      <form @submit.prevent="submitForm" class="space-y-6">
        <!-- Farm Selection -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Select Farm *</label>
          <select
            v-model="form.farm_id"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.farm_id }"
          >
            <option value="">Choose your farm...</option>
            <option v-for="farm in farms" :key="farm.id" :value="farm.id">
              {{ farm.name }} - {{ farm.location }}
            </option>
          </select>
          <div v-if="form.errors.farm_id" class="text-sm text-red-500 mt-1">{{ form.errors.farm_id }}</div>
        </div>

        <!-- Insurer Selection -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Select Insurer *</label>
          <select
            v-model="form.insurer_id"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.insurer_id }"
          >
            <option value="">Choose an insurer...</option>
            <option v-for="insurer in insurers" :key="insurer.id" :value="insurer.id">
              {{ insurer.name }}
            </option>
          </select>
          <div v-if="form.errors.insurer_id" class="text-sm text-red-500 mt-1">{{ form.errors.insurer_id }}</div>
        </div>

        <!-- Insurance Plan Selection -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Insurance Plan *</label>
          <select
            v-model="form.insurance_plan_id"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.insurance_plan_id }"
          >
            <option value="">Choose an insurance plan...</option>
            <option v-for="plan in availablePlans" :key="plan.id" :value="plan.id">
              {{ plan.name }} - {{ plan.coverage_type }} ({{ plan.duration_months }} months)
            </option>
          </select>
          <div v-if="form.errors.insurance_plan_id" class="text-sm text-red-500 mt-1">{{ form.errors.insurance_plan_id }}</div>
        </div>

        <!-- Coverage and Premium Amounts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Coverage Amount ($) *</label>
            <input
              type="number"
              v-model="form.coverage_amount"
              placeholder="Enter coverage amount"
              min="0"
              step="0.01"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.coverage_amount }"
            />
            <div v-if="form.errors.coverage_amount" class="text-sm text-red-500 mt-1">{{ form.errors.coverage_amount }}</div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Premium Amount ($) *</label>
            <input
              type="number"
              v-model="form.premium_amount"
              placeholder="Enter premium amount"
              min="0"
              step="0.01"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.premium_amount }"
            />
            <div v-if="form.errors.premium_amount" class="text-sm text-red-500 mt-1">{{ form.errors.premium_amount }}</div>
          </div>
        </div>

        <!-- Start and End Dates -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date *</label>
            <input
              type="date"
              v-model="form.start_date"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.start_date }"
            />
            <div v-if="form.errors.start_date" class="text-sm text-red-500 mt-1">{{ form.errors.start_date }}</div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">End Date *</label>
            <input
              type="date"
              v-model="form.end_date"
              :min="form.start_date"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.end_date }"
            />
            <div v-if="form.errors.end_date" class="text-sm text-red-500 mt-1">{{ form.errors.end_date }}</div>
          </div>
        </div>

        <!-- Current Policy Info -->
        <div class="bg-gray-50 p-4 rounded-lg">
          <h4 class="font-medium text-gray-900 mb-3">Current Policy Information</h4>
          <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
              <span class="text-gray-600">Policy Number:</span>
              <div class="font-medium">{{ insurance.policy_number }}</div>
            </div>
            <div>
              <span class="text-gray-600">Status:</span>
              <div class="font-medium capitalize">{{ insurance.status }}</div>
            </div>
            <div>
              <span class="text-gray-600">Payment Status:</span>
              <div class="font-medium capitalize">{{ insurance.payment_status || 'N/A' }}</div>
            </div>
            <div>
              <span class="text-gray-600">Created:</span>
              <div class="font-medium">{{ new Date(insurance.created_at).toLocaleDateString() }}</div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
          <a href="/farmer/insurance" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            Cancel
          </a>
          <button
            type="submit"
            :disabled="loading"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
          >
            <div v-if="loading" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
            <span v-if="!loading">Update Policy</span>
            <span v-else>Updating...</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import DashboardLayout from '../../Shared/DashboardLayout.vue'

defineOptions({
  layout: DashboardLayout
})

const props = defineProps({
  insurance: Object,
  farms: Array,
  insurers: Array
})

const loading = ref(false)
const availablePlans = ref([])

const form = useForm({
  farm_id: props.insurance.farm_id || '',
  insurer_id: props.insurance.insurer_id || '',
  insurance_plan_id: props.insurance.insurance_plan_id || '',
  coverage_amount: props.insurance.coverage_amount || '',
  premium_amount: props.insurance.premium_amount || '',
  start_date: props.insurance.start_date || '',
  end_date: props.insurance.end_date || ''
})

// Load available plans when insurer changes
const loadPlans = async () => {
  if (!form.insurer_id) return

  try {
    const response = await fetch(`/insurer/${form.insurer_id}/plans`)
    const data = await response.json()
    availablePlans.value = data.plans || []
  } catch (error) {
    console.error('Error loading plans:', error)
    availablePlans.value = []
  }
}

const submitForm = () => {
  loading.value = true
  form.put(`/farmer/insurance/${props.insurance.id}`, {
    onFinish: () => {
      loading.value = false
    }
  })
}

onMounted(() => {
  // Load plans for the current insurer
  if (form.insurer_id) {
    loadPlans()
  }
})
</script>
