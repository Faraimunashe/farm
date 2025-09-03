<template>
  <Head title="Add Transaction" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Add Transaction</h1>
        <p class="text-gray-600">Record your income or expense for financial tracking</p>
      </div>
      <a href="/farmer/transactions" class="text-blue-600 hover:text-blue-700 font-medium">
        ← Back to Transactions
      </a>
    </div>

    <!-- Transaction Form -->
    <div class="bg-white rounded-lg shadow p-6">
      <form @submit.prevent="submitForm" class="space-y-6">
        <!-- Farm Selection -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Select Farm</label>
          <select
            v-model="form.farm_id"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.farm_id }"
          >
            <option value="">Choose a farm...</option>
            <option v-for="farm in farms" :key="farm.id" :value="farm.id">
              {{ farm.name }} ({{ farm.type }})
            </option>
          </select>
          <div v-if="form.errors.farm_id" class="text-sm text-red-500 mt-1">{{ form.errors.farm_id }}</div>
        </div>

        <!-- Transaction Type -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Transaction Type</label>
          <div class="grid grid-cols-2 gap-4">
            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
              <input
                type="radio"
                v-model="form.type"
                value="income"
                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300"
              />
              <div class="ml-3">
                <div class="text-sm font-medium text-gray-900">Income</div>
                <div class="text-sm text-gray-500">Money received</div>
              </div>
            </label>
            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
              <input
                type="radio"
                v-model="form.type"
                value="expense"
                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300"
              />
              <div class="ml-3">
                <div class="text-sm font-medium text-gray-900">Expense</div>
                <div class="text-sm text-gray-500">Money spent</div>
              </div>
            </label>
          </div>
          <div v-if="form.errors.type" class="text-sm text-red-500 mt-1">{{ form.errors.type }}</div>
        </div>

        <!-- Amount -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Amount ($)</label>
          <input
            type="number"
            v-model="form.amount"
            placeholder="Enter amount"
            step="0.01"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.amount }"
          />
          <div v-if="form.errors.amount" class="text-sm text-red-500 mt-1">{{ form.errors.amount }}</div>
        </div>

        <!-- Description -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
          <textarea
            v-model="form.description"
            placeholder="Describe the transaction (e.g., Crop sales, Equipment purchase, Feed costs)"
            rows="3"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.description }"
          ></textarea>
          <div v-if="form.errors.description" class="text-sm text-red-500 mt-1">{{ form.errors.description }}</div>
        </div>

        <!-- Date -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Transaction Date</label>
          <input
            type="date"
            v-model="form.date"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.date }"
          />
          <div v-if="form.errors.date" class="text-sm text-red-500 mt-1">{{ form.errors.date }}</div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
          <a href="/farmer/transactions" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            Cancel
          </a>
          <button
            type="submit"
            :disabled="loading"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
          >
            <div v-if="loading" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
            <span v-if="!loading">Record Transaction</span>
            <span v-else>Recording...</span>
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
  type: '',
  amount: '',
  description: '',
  date: new Date().toISOString().split('T')[0] // Today's date as default
})

const submitForm = () => {
  loading.value = true
  form.post('/farmer/transactions', {
    onFinish: () => {
      loading.value = false
    }
  })
}
</script>
