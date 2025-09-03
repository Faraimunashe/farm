<template>
  <Head title="Create Financial Plan" />

  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Create Financial Plan</h1>
        <p class="text-gray-600">Plan your farm's income and expenses</p>
      </div>
      <a href="/farmer/plans" class="text-blue-600 hover:text-blue-700">← Back to Plans</a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <form @submit.prevent="submitForm" class="space-y-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Select Farm</label>
          <select v-model="form.farm_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
            <option value="">Choose a farm...</option>
            <option v-for="farm in farms" :key="farm.id" :value="farm.id">
              {{ farm.name }}
            </option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Plan Description</label>
          <textarea v-model="form.description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg"></textarea>
        </div>

        <div class="grid grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date</label>
            <input type="date" v-model="form.period_start" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
            <input type="date" v-model="form.period_end" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
          </div>
        </div>

        <div>
          <div class="flex justify-between items-center mb-4">
            <label class="block text-sm font-semibold text-gray-700">Financial Entries</label>
            <button type="button" @click="addEntry" class="text-blue-600 hover:text-blue-700">+ Add Entry</button>
          </div>

          <div v-for="(entry, index) in form.financials" :key="index" class="border border-gray-200 rounded-lg p-4 mb-4">
            <div class="flex justify-between mb-4">
              <h4 class="text-sm font-medium">Entry #{{ index + 1 }}</h4>
              <button type="button" @click="removeEntry(index)" class="text-red-600">Remove</button>
            </div>
            <div class="grid grid-cols-3 gap-4">
              <select v-model="entry.type" class="px-3 py-2 border border-gray-300 rounded-md">
                <option value="income">Income</option>
                <option value="expense">Expense</option>
              </select>
              <input type="number" v-model="entry.amount" placeholder="Amount" class="px-3 py-2 border border-gray-300 rounded-md">
              <input type="text" v-model="entry.description" placeholder="Description" class="px-3 py-2 border border-gray-300 rounded-md">
            </div>
          </div>
        </div>

        <div class="flex justify-end space-x-4">
          <a href="/farmer/plans" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg">Cancel</a>
          <button type="submit" :disabled="loading" class="px-6 py-3 bg-blue-600 text-white rounded-lg">
            {{ loading ? 'Creating...' : 'Create Plan' }}
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
  farms: Array
})

const loading = ref(false)

const form = useForm({
  farm_id: '',
  description: '',
  period_start: '',
  period_end: '',
  financials: []
})

const addEntry = () => {
  form.financials.push({
    type: 'income',
    description: '',
    amount: ''
  })
}

const removeEntry = (index) => {
  form.financials.splice(index, 1)
}

const submitForm = () => {
  loading.value = true
  form.post('/farmer/plans', {
    onFinish: () => {
      loading.value = false
    }
  })
}
</script>
