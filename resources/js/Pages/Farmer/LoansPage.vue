<template>
  <Head title="My Loans" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">My Loans</h1>
        <p class="text-gray-600">Manage your loan applications and active loans</p>
      </div>
      <a href="/farmer/loans/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
        Apply for Loan
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

    <!-- Empty State -->
        <div v-if="!loans || loans.length === 0" class="text-center py-12">
          <div class="text-6xl mb-4">
            <i class="fa fa-dollar-sign text-gray-300"></i>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No loans yet</h3>
          <p class="text-gray-500 mb-6">Apply for your first agricultural loan</p>
          <a href="/farmer/loans/create" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i class="fa fa-plus mr-2"></i>
            Apply for Loan
          </a>
        </div>

    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lender</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Term</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="loan in loans" :key="loan.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ loan.lender.name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold text-gray-900">${{ loan.amount.toLocaleString() }}</div>
                <div class="text-sm text-gray-500">{{ loan.interest_rate }}% interest</div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-900">{{ loan.purpose }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'text-xs px-2 py-1 rounded-full',
                  loan.status === 'approved' ? 'bg-green-100 text-green-800' :
                  loan.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                  loan.status === 'rejected' ? 'bg-red-100 text-red-800' :
                  'bg-gray-100 text-gray-800'
                ]">
                  {{ loan.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ loan.term_months }} months</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <a :href="`/farmer/loans/${loan.id}`" class="text-blue-600 hover:text-blue-900">View</a>
                  <a v-if="loan.status === 'pending'" :href="`/farmer/loans/${loan.id}/edit`" class="text-green-600 hover:text-green-900">Edit</a>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
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
  loans: Array
})
</script>
