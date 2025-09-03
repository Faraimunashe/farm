<template>
  <Head title="Create Insurance Plan" />

  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Create Insurance Plan</h1>
        <p class="text-gray-600">Add a new comprehensive insurance plan offering</p>
      </div>
      <a href="/insurer/plans" class="text-blue-600 hover:text-blue-700">← Back to Plans</a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <form @submit.prevent="submitForm" class="space-y-6">
        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Plan Name *</label>
            <input
              type="text"
              v-model="form.name"
              placeholder="e.g., Basic Crop Insurance, Livestock Protection"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.name }"
            />
            <div v-if="form.errors.name" class="text-sm text-red-500 mt-1">{{ form.errors.name }}</div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Coverage Type *</label>
            <select
              v-model="form.coverage_type"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.coverage_type }"
            >
              <option value="">Select coverage type</option>
              <option value="crop">Crop Insurance</option>
              <option value="livestock">Livestock Insurance</option>
              <option value="equipment">Equipment Insurance</option>
              <option value="property">Property Insurance</option>
            </select>
            <div v-if="form.errors.coverage_type" class="text-sm text-red-500 mt-1">{{ form.errors.coverage_type }}</div>
          </div>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
          <textarea
            v-model="form.description"
            placeholder="Describe the coverage and benefits of this plan..."
            rows="4"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.description }"
          ></textarea>
          <div v-if="form.errors.description" class="text-sm text-red-500 mt-1">{{ form.errors.description }}</div>
        </div>

        <!-- Coverage Details -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Coverage Percentage (%) *</label>
            <input
              type="number"
              v-model="form.coverage_percentage"
              placeholder="0-100"
              min="0"
              max="100"
              step="0.1"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.coverage_percentage }"
            />
            <div v-if="form.errors.coverage_percentage" class="text-sm text-red-500 mt-1">{{ form.errors.coverage_percentage }}</div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Premium Rate (%) *</label>
            <input
              type="number"
              v-model="form.premium_rate"
              placeholder="0-100"
              min="0"
              max="100"
              step="0.1"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.premium_rate }"
            />
            <div v-if="form.errors.premium_rate" class="text-sm text-red-500 mt-1">{{ form.errors.premium_rate }}</div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Duration (Months) *</label>
            <input
              type="number"
              v-model="form.duration_months"
              placeholder="1-120"
              min="1"
              max="120"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.duration_months }"
            />
            <div v-if="form.errors.duration_months" class="text-sm text-red-500 mt-1">{{ form.errors.duration_months }}</div>
          </div>
        </div>

        <!-- Coverage Limits -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Minimum Coverage ($) *</label>
            <input
              type="number"
              v-model="form.min_coverage"
              placeholder="Enter minimum coverage amount"
              min="0"
              step="0.01"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.min_coverage }"
            />
            <div v-if="form.errors.min_coverage" class="text-sm text-red-500 mt-1">{{ form.errors.min_coverage }}</div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Maximum Coverage ($) *</label>
            <input
              type="number"
              v-model="form.max_coverage"
              placeholder="Enter maximum coverage amount"
              min="0"
              step="0.01"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.max_coverage }"
            />
            <div v-if="form.errors.max_coverage" class="text-sm text-red-500 mt-1">{{ form.errors.max_coverage }}</div>
          </div>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Deductible Amount ($) *</label>
          <input
            type="number"
            v-model="form.deductible_amount"
            placeholder="Enter deductible amount"
            min="0"
            step="0.01"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.deductible_amount }"
          />
          <div v-if="form.errors.deductible_amount" class="text-sm text-red-500 mt-1">{{ form.errors.deductible_amount }}</div>
        </div>

        <!-- Terms and Conditions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Terms & Conditions</label>
            <textarea
              v-model="form.terms_conditions"
              placeholder="Enter terms and conditions..."
              rows="4"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.terms_conditions }"
            ></textarea>
            <div v-if="form.errors.terms_conditions" class="text-sm text-red-500 mt-1">{{ form.errors.terms_conditions }}</div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Exclusions</label>
            <textarea
              v-model="form.exclusions"
              placeholder="Enter what is not covered..."
              rows="4"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.exclusions }"
            ></textarea>
            <div v-if="form.errors.exclusions" class="text-sm text-red-500 mt-1">{{ form.errors.exclusions }}</div>
          </div>
        </div>

        <div class="flex justify-end space-x-4">
          <a href="/insurer/plans" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            Cancel
          </a>
          <button
            type="submit"
            :disabled="loading"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
          >
            <div v-if="loading" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
            <span v-if="!loading">Create Plan</span>
            <span v-else>Creating...</span>
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

const loading = ref(false)

const form = useForm({
  name: '',
  description: '',
  coverage_type: '',
  coverage_percentage: '',
  premium_rate: '',
  min_coverage: '',
  max_coverage: '',
  duration_months: '',
  deductible_amount: '',
  terms_conditions: '',
  exclusions: ''
})

const submitForm = () => {
  loading.value = true
  form.post('/insurer/plans', {
    onFinish: () => {
      loading.value = false
    }
  })
}
</script>
