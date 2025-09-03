<template>
  <Head title="Edit Loan" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Loan Application</h1>
        <p class="text-gray-600">Update your loan application details</p>
      </div>
      <a href="/farmer/loans" class="text-blue-600 hover:text-blue-700 font-medium">
        ← Back to Loans
      </a>
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

    <!-- Error Message -->
    <div v-if="$page.props.flash && $page.props.flash.error" class="bg-red-50 border border-red-200 rounded-lg p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-red-800">{{ $page.props.flash.error }}</p>
        </div>
      </div>
    </div>

    <!-- Loan Edit Form -->
    <div class="bg-white rounded-lg shadow p-6">
      <form @submit.prevent="submitForm" class="space-y-6">
        <!-- Lender Selection -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Select Lender</label>
          <select
            v-model="form.lender_id"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.lender_id }"
          >
            <option value="">Choose a lender...</option>
            <option v-for="lender in lenders" :key="lender.id" :value="lender.id">
              {{ lender.name }}
            </option>
          </select>
          <div v-if="form.errors.lender_id" class="text-sm text-red-500 mt-1">{{ form.errors.lender_id }}</div>
        </div>

        <!-- Loan Amount -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Loan Amount ($)</label>
          <input
            type="number"
            v-model="form.amount"
            placeholder="Enter loan amount"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.amount }"
          />
          <div v-if="form.errors.amount" class="text-sm text-red-500 mt-1">{{ form.errors.amount }}</div>
        </div>

        <!-- Purpose -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Loan Purpose</label>
          <textarea
            v-model="form.purpose"
            placeholder="Describe what you plan to use this loan for..."
            rows="4"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.purpose }"
          ></textarea>
          <div v-if="form.errors.purpose" class="text-sm text-red-500 mt-1">{{ form.errors.purpose }}</div>
        </div>

        <!-- Term and Interest Rate -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Loan Term (months)</label>
            <input
              type="number"
              v-model="form.term_months"
              placeholder="e.g., 12"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.term_months }"
            />
            <div v-if="form.errors.term_months" class="text-sm text-red-500 mt-1">{{ form.errors.term_months }}</div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Interest Rate (%)</label>
            <input
              type="number"
              v-model="form.interest_rate"
              placeholder="e.g., 5.5"
              step="0.1"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.interest_rate }"
            />
            <div v-if="form.errors.interest_rate" class="text-sm text-red-500 mt-1">{{ form.errors.interest_rate }}</div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
          <a href="/farmer/loans" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            Cancel
          </a>
          <button
            type="submit"
            :disabled="loading"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
          >
            <div v-if="loading" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
            <span v-if="!loading">Update Application</span>
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
  loan: Object,
  lenders: Array
})

const loading = ref(false)

const form = useForm({
  lender_id: props.loan.lender_id,
  amount: props.loan.amount,
  purpose: props.loan.purpose,
  term_months: props.loan.term_months,
  interest_rate: props.loan.interest_rate
})

const submitForm = () => {
  loading.value = true
  form.put(`/farmer/loans/${props.loan.id}`, {
    onFinish: () => {
      loading.value = false
    }
  })
}
</script>
