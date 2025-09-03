<template>
  <Head title="Insurance Plans" />

  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Insurance Plans</h1>
        <p class="text-gray-600">Manage your comprehensive insurance plan offerings</p>
      </div>
      <a href="/insurer/plans/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
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
      <h3 class="text-lg font-medium text-gray-900 mb-2">No insurance plans yet</h3>
      <p class="text-gray-600 mb-6">Create your first comprehensive insurance plan to start offering coverage to farmers.</p>
      <a href="/insurer/plans/create" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
        Create Your First Plan
      </a>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="plan in plans" :key="plan.id" class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
        <div class="p-6">
          <div class="flex justify-between items-start mb-4">
            <div class="flex-1">
              <div class="flex items-center space-x-2 mb-2">
                <h3 class="text-lg font-semibold text-gray-900">{{ plan.name }}</h3>
                <span :class="[
                  'text-xs px-2 py-1 rounded-full',
                  plan.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                ]">
                  {{ plan.is_active ? 'Active' : 'Inactive' }}
                </span>
              </div>
              <p class="text-sm text-gray-600 mb-2">{{ plan.description }}</p>
              <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full capitalize">
                {{ plan.coverage_type }} Insurance
              </span>
            </div>
          </div>

          <div class="space-y-3 mb-4">
            <div class="grid grid-cols-2 gap-4 text-sm">
              <div>
                <span class="text-gray-600">Coverage Range:</span>
                <div class="font-medium text-gray-900">
                  ${{ parseFloat(plan.min_coverage || 0).toLocaleString() }} - ${{ parseFloat(plan.max_coverage || 0).toLocaleString() }}
                </div>
              </div>
              <div>
                <span class="text-gray-600">Premium Rate:</span>
                <div class="font-medium text-gray-900">{{ plan.premium_rate }}%</div>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
              <div>
                <span class="text-gray-600">Coverage %:</span>
                <div class="font-medium text-gray-900">{{ plan.coverage_percentage }}%</div>
              </div>
              <div>
                <span class="text-gray-600">Duration:</span>
                <div class="font-medium text-gray-900">{{ plan.duration_months }} months</div>
              </div>
            </div>

            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Active Policies:</span>
              <span class="font-medium text-blue-600">{{ plan.insurances_count || 0 }}</span>
            </div>
          </div>

          <div class="flex space-x-2">
            <a :href="`/insurer/plans/${plan.id}`" class="flex-1 text-center px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm">
              View Details
            </a>
            <a :href="`/insurer/plans/${plan.id}/edit`" class="flex-1 text-center px-3 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors text-sm">
              Edit
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import InsurerLayout from '../../Shared/InsurerLayout.vue'

defineOptions({
  layout: InsurerLayout
})

defineProps({
  plans: Array
})
</script>
