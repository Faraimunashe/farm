<template>
  <Head title="Financial Plans" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Financial Plans</h1>
        <p class="text-gray-600">Create and manage seasonal financial plans for your farms</p>
      </div>
      <a href="/farmer/plans/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
        Create Plan
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

    <!-- Plans Grid -->
    <div v-if="plans.length === 0" class="text-center py-12">
      <div class="text-6xl mb-4">📋</div>
      <h3 class="text-lg font-medium text-gray-900 mb-2">No financial plans yet</h3>
      <p class="text-gray-600 mb-6">Create your first financial plan to budget your farm operations and project profitability.</p>
      <a href="/farmer/plans/create" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
        Create Your First Plan
      </a>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="plan in plans" :key="plan.id" class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
        <div class="p-6">
          <!-- Plan Header -->
          <div class="flex justify-between items-start mb-4">
            <div>
              <h3 class="text-lg font-semibold text-gray-900">{{ plan.farm.name }}</h3>
              <p class="text-sm text-gray-600">{{ plan.description }}</p>
            </div>
          </div>

          <!-- Plan Period -->
          <div class="mb-4">
            <p class="text-sm text-gray-600">
              {{ new Date(plan.period_start).toLocaleDateString() }} - {{ new Date(plan.period_end).toLocaleDateString() }}
            </p>
          </div>

          <!-- Financial Summary -->
          <div class="space-y-2 mb-4">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Total Income:</span>
              <span class="font-medium text-green-600">
                ${{ plan.financials.filter(f => f.type === 'income').reduce((sum, f) => sum + parseFloat(f.amount), 0).toLocaleString() }}
              </span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Total Expenses:</span>
              <span class="font-medium text-red-600">
                ${{ plan.financials.filter(f => f.type === 'expense').reduce((sum, f) => sum + parseFloat(f.amount), 0).toLocaleString() }}
              </span>
            </div>
            <div class="border-t pt-2">
              <div class="flex justify-between text-sm font-semibold">
                <span class="text-gray-900">Projected Profit:</span>
                <span :class="[
                  'font-bold',
                  (plan.financials.filter(f => f.type === 'income').reduce((sum, f) => sum + parseFloat(f.amount), 0) -
                   plan.financials.filter(f => f.type === 'expense').reduce((sum, f) => sum + parseFloat(f.amount), 0)) >= 0
                    ? 'text-green-600' : 'text-red-600'
                ]">
                  ${{ (plan.financials.filter(f => f.type === 'income').reduce((sum, f) => sum + parseFloat(f.amount), 0) -
                       plan.financials.filter(f => f.type === 'expense').reduce((sum, f) => sum + parseFloat(f.amount), 0)).toLocaleString() }}
                </span>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex space-x-2">
            <a :href="`/farmer/plans/${plan.id}`" class="flex-1 text-center px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm">
              View Details
            </a>
            <a :href="`/farmer/plans/${plan.id}/edit`" class="flex-1 text-center px-3 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors text-sm">
              Edit
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
  plans: Array
})
</script>
