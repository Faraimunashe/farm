<template>
  <Head title="Loan Details" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Loan Details</h1>
        <p class="text-gray-600">View your loan application details and status</p>
      </div>
      <div class="flex space-x-3">
        <a href="/farmer/loans" class="text-blue-600 hover:text-blue-700 font-medium">
          ← Back to Loans
        </a>
        <a v-if="loan.status === 'pending'" :href="`/farmer/loans/${loan.id}/edit`" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
          Edit Loan
        </a>
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

    <!-- Error Message -->
    <div v-if="$page.props.flash && $page.props.flash.error" class="bg-red-50 border border-red-200 rounded-lg p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-red-800">{{ $page.props.flash.error }}</p>
        </div>
      </div>
    </div>

    <!-- Loan Details Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <h2 class="text-xl font-semibold text-gray-900">Loan #{{ loan.id }}</h2>
          <span :class="[
            'text-sm px-3 py-1 rounded-full font-medium',
            loan.status === 'approved' ? 'bg-green-100 text-green-800' :
            loan.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
            loan.status === 'rejected' ? 'bg-red-100 text-red-800' :
            'bg-gray-100 text-gray-800'
          ]">
            {{ loan.status }}
          </span>
        </div>
      </div>

      <div class="p-6 space-y-6">
        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Loan Information</h3>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">Lender</dt>
                <dd class="text-sm text-gray-900">{{ loan.lender.name }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Amount</dt>
                <dd class="text-lg font-semibold text-gray-900">${{ loan.amount.toLocaleString() }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Interest Rate</dt>
                <dd class="text-sm text-gray-900">{{ loan.interest_rate }}%</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Term</dt>
                <dd class="text-sm text-gray-900">{{ loan.term_months }} months</dd>
              </div>
            </dl>
          </div>

          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Application Details</h3>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">Purpose</dt>
                <dd class="text-sm text-gray-900">{{ loan.purpose }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Applied On</dt>
                <dd class="text-sm text-gray-900">{{ new Date(loan.created_at).toLocaleDateString() }}</dd>
              </div>
              <div v-if="loan.updated_at !== loan.created_at">
                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                <dd class="text-sm text-gray-900">{{ new Date(loan.updated_at).toLocaleDateString() }}</dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- Actions -->
        <div class="border-t border-gray-200 pt-6">
          <div class="flex justify-end space-x-3">
            <a href="/farmer/loans" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
              Back to Loans
            </a>
            <a v-if="loan.status === 'pending'" :href="`/farmer/loans/${loan.id}/edit`" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
              Edit Loan
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import DashboardLayout from '../../Shared/DashboardLayout.vue'

defineOptions({
  layout: DashboardLayout
})

defineProps({
  loan: Object
})
</script>

