<template>
  <Head title="Farm Details" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Farm Details</h1>
        <p class="text-gray-600">View your farm information and related data</p>
      </div>
      <div class="flex space-x-3">
        <a href="/farmer/farms" class="text-blue-600 hover:text-blue-700 font-medium">
          ← Back to Farms
        </a>
        <a :href="`/farmer/farms/${farm.id}/edit`" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
          Edit Farm
        </a>
      </div>
    </div>

    <!-- Farm Details Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <h2 class="text-xl font-semibold text-gray-900">{{ farm.name }}</h2>
          <span :class="[
            'text-sm px-3 py-1 rounded-full font-medium',
            farm.type === 'crops' ? 'bg-green-100 text-green-800' :
            farm.type === 'livestock' ? 'bg-blue-100 text-blue-800' :
            'bg-purple-100 text-purple-800'
          ]">
            {{ farm.type }}
          </span>
        </div>
      </div>

      <div class="p-6 space-y-6">
        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Farm Information</h3>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">Farm Name</dt>
                <dd class="text-sm text-gray-900">{{ farm.name }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Farm Type</dt>
                <dd class="text-sm text-gray-900">{{ farm.type }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Size</dt>
                <dd class="text-sm text-gray-900">{{ farm.size }} hectares</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Contact</dt>
                <dd class="text-sm text-gray-900">{{ farm.contact }}</dd>
              </div>
            </dl>
          </div>

          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Location</h3>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">Address</dt>
                <dd class="text-sm text-gray-900">{{ farm.address }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Created On</dt>
                <dd class="text-sm text-gray-900">{{ new Date(farm.created_at).toLocaleDateString() }}</dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- Related Data -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="font-medium text-gray-900 mb-2">Transactions</h4>
            <p class="text-2xl font-bold text-blue-600">{{ farm.transactions ? farm.transactions.length : 0 }}</p>
            <p class="text-sm text-gray-600">Total transactions</p>
          </div>

          <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="font-medium text-gray-900 mb-2">Plans</h4>
            <p class="text-2xl font-bold text-green-600">{{ farm.plans ? farm.plans.length : 0 }}</p>
            <p class="text-sm text-gray-600">Financial plans</p>
          </div>

          <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="font-medium text-gray-900 mb-2">Insurance</h4>
            <p class="text-2xl font-bold text-purple-600">{{ farm.insurances ? farm.insurances.length : 0 }}</p>
            <p class="text-sm text-gray-600">Active policies</p>
          </div>
        </div>

        <!-- Actions -->
        <div class="border-t border-gray-200 pt-6">
          <div class="flex justify-end space-x-3">
            <a href="/farmer/farms" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
              Back to Farms
            </a>
            <a :href="`/farmer/farms/${farm.id}/edit`" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
              Edit Farm
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
  farm: Object
})
</script>
