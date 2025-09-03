<template>
  <Head title="My Insurance" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">My Insurance</h1>
        <p class="text-gray-600">Manage your insurance policies and coverage</p>
      </div>
      <a href="/farmer/insurance/create" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
        Get Insurance
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
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-red-800">{{ $page.props.flash.error }}</p>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="!insurances || insurances.length === 0" class="text-center py-12">
      <div class="text-6xl mb-4">
        <i class="fa fa-shield-alt text-gray-300"></i>
      </div>
      <h3 class="text-lg font-medium text-gray-900 mb-2">No insurance policies yet</h3>
      <p class="text-gray-500 mb-6">Protect your farm with comprehensive insurance coverage</p>
      <a href="/farmer/insurance/create" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
        <i class="fa fa-plus mr-2"></i>
        Get Insurance
      </a>
    </div>

    <!-- Insurance Policies Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="policy in insurances" :key="policy.id" class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
        <div class="p-6">
          <!-- Header with Status -->
          <div class="flex items-center justify-between mb-4">
            <div>
              <h3 class="text-lg font-semibold text-gray-900">{{ policy.insurer?.name || 'Unknown Insurer' }}</h3>
              <p class="text-sm text-gray-500">{{ policy.insurance_plan?.name || 'Standard Plan' }}</p>
            </div>
            <div class="flex flex-col items-end space-y-1">
              <span :class="[
                'text-xs px-2 py-1 rounded-full font-medium',
                policy.status === 'active' ? 'bg-green-100 text-green-800' :
                policy.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                policy.status === 'expired' ? 'bg-red-100 text-red-800' :
                policy.status === 'cancelled' ? 'bg-gray-100 text-gray-800' :
                'bg-blue-100 text-blue-800'
              ]">
                {{ policy.status.charAt(0).toUpperCase() + policy.status.slice(1) }}
              </span>
              <span :class="[
                'text-xs px-2 py-1 rounded-full',
                policy.payment_status === 'paid' ? 'bg-green-100 text-green-800' :
                policy.payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                'bg-red-100 text-red-800'
              ]">
                {{ policy.payment_status }}
              </span>
            </div>
          </div>

          <!-- Policy Information -->
          <div class="space-y-3 mb-4">
            <div class="flex items-center text-sm text-gray-600">
              <span class="mr-2">📋</span>
              <span>Policy: {{ policy.policy_number }}</span>
            </div>

            <div class="flex items-center text-sm text-gray-600">
              <span class="mr-2">🏡</span>
              <span>Farm: {{ policy.farm?.name || 'Unknown Farm' }}</span>
            </div>

            <div class="flex items-center text-sm text-gray-600">
              <span class="mr-2">💰</span>
              <span>Coverage: ${{ parseFloat(policy.coverage_amount || 0).toLocaleString() }}</span>
            </div>

            <div class="flex items-center text-sm text-gray-600">
              <span class="mr-2">💵</span>
              <span>Premium: ${{ parseFloat(policy.premium_amount || 0).toLocaleString() }}</span>
            </div>

            <div class="flex items-center text-sm text-gray-600">
              <span class="mr-2">🛡️</span>
              <span>{{ policy.insurance_plan?.coverage_type || 'General' }} Insurance</span>
            </div>

            <div class="flex items-center text-sm text-gray-600">
              <span class="mr-2">📅</span>
              <span>{{ new Date(policy.start_date).toLocaleDateString() }} - {{ new Date(policy.end_date).toLocaleDateString() }}</span>
            </div>

            <div v-if="policy.claims_count > 0" class="flex items-center text-sm text-gray-600">
              <span class="mr-2">⚠️</span>
              <span>{{ policy.claims_count }} claim(s) filed</span>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex space-x-2">
            <a :href="`/farmer/insurance/${policy.id}`" class="flex-1 bg-gray-100 text-gray-700 px-3 py-2 rounded text-center text-sm hover:bg-gray-200 transition-colors">
              View Details
            </a>

            <a v-if="policy.status === 'pending'" :href="`/farmer/insurance/${policy.id}/edit`" class="flex-1 bg-purple-100 text-purple-700 px-3 py-2 rounded text-center text-sm hover:bg-purple-200 transition-colors">
              Edit
            </a>

            <button v-if="policy.status === 'active'" @click="cancelPolicy(policy.id)" class="flex-1 bg-red-100 text-red-700 px-3 py-2 rounded text-center text-sm hover:bg-red-200 transition-colors">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import DashboardLayout from '../../Shared/DashboardLayout.vue'

defineOptions({
  layout: DashboardLayout
})

const props = defineProps({
  insurances: Array
})

const cancelForm = useForm({})

const cancelPolicy = (insuranceId) => {
  if (confirm('Are you sure you want to cancel this insurance policy?')) {
    cancelForm.patch(`/farmer/insurance/${insuranceId}/cancel`)
  }
}
</script>
