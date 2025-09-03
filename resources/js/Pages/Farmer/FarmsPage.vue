<template>
  <Head title="My Farms" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">My Farms</h1>
        <p class="text-gray-600">Manage your farm operations and details</p>
      </div>
      <a href="/farmer/farms/create" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
        Add New Farm
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

    <!-- Farms Grid -->
    <div v-if="farms.length === 0" class="text-center py-12">
      <div class="text-6xl mb-4">
        <i class="fa fa-leaf text-gray-300"></i>
      </div>
      <h3 class="text-lg font-medium text-gray-900 mb-2">No farms yet</h3>
      <p class="text-gray-500 mb-6">Get started by creating your first farm</p>
      <a href="/farmer/farms/create" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
        <i class="fa fa-plus mr-2"></i>
        Create Farm
      </a>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="farm in farms" :key="farm.id" class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
        <div class="p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ farm.name }}</h3>
            <span :class="[
              'text-xs px-2 py-1 rounded-full',
              farm.type === 'crops' ? 'bg-green-100 text-green-800' :
              farm.type === 'livestock' ? 'bg-blue-100 text-blue-800' :
              'bg-purple-100 text-purple-800'
            ]">
              {{ farm.type }}
            </span>
          </div>

          <div class="space-y-3">
            <div class="flex items-center text-sm text-gray-600">
              <span class="mr-2">📏</span>
              <span>{{ farm.size }} hectares</span>
            </div>

            <div class="flex items-center text-sm text-gray-600">
              <span class="mr-2">📍</span>
              <span class="truncate">{{ farm.address }}</span>
            </div>

            <div class="flex items-center text-sm text-gray-600">
              <span class="mr-2">📞</span>
              <span>{{ farm.contact }}</span>
            </div>
          </div>

          <div class="mt-6 flex space-x-2">
            <a :href="`/farmer/farms/${farm.id}`" class="flex-1 bg-gray-100 text-gray-700 px-3 py-2 rounded text-center text-sm hover:bg-gray-200 transition-colors">
              View Details
            </a>
            <a :href="`/farmer/farms/${farm.id}/edit`" class="flex-1 bg-blue-100 text-blue-700 px-3 py-2 rounded text-center text-sm hover:bg-blue-200 transition-colors">
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
  farms: Array
})
</script>
