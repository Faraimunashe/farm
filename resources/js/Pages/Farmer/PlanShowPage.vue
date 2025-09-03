<template>
  <Head title="Financial Plan Details" />

  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Financial Plan Details</h1>
        <p class="text-gray-600">View your financial plan and projected profitability</p>
      </div>
      <div class="flex space-x-3">
        <a href="/farmer/plans" class="text-blue-600 hover:text-blue-700">← Back to Plans</a>
        <a :href="`/farmer/plans/${plan.id}/edit`" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Edit Plan</a>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-start">
          <div>
            <h2 class="text-xl font-semibold text-gray-900">{{ plan.farm.name }}</h2>
            <p class="text-gray-600">{{ plan.description }}</p>
          </div>
        </div>
      </div>

      <div class="p-6 space-y-6">
        <!-- Plan Period -->
        <div>
          <h3 class="text-lg font-medium text-gray-900 mb-3">Plan Period</h3>
          <p class="text-gray-600">
            {{ new Date(plan.period_start).toLocaleDateString() }} - {{ new Date(plan.period_end).toLocaleDateString() }}
          </p>
        </div>

        <!-- Financial Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-green-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-green-800 mb-1">Total Income</h4>
            <p class="text-2xl font-bold text-green-600">${{ totalIncome.toLocaleString() }}</p>
          </div>
          <div class="bg-red-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-red-800 mb-1">Total Expenses</h4>
            <p class="text-2xl font-bold text-red-600">${{ totalExpenses.toLocaleString() }}</p>
          </div>
          <div :class="[
            'rounded-lg p-4',
            profit >= 0 ? 'bg-green-50' : 'bg-red-50'
          ]">
            <h4 class="text-sm font-medium mb-1" :class="profit >= 0 ? 'text-green-800' : 'text-red-800'">
              Projected Profit
            </h4>
            <p class="text-2xl font-bold" :class="profit >= 0 ? 'text-green-600' : 'text-red-600'">
              ${{ profit.toLocaleString() }}
            </p>
          </div>
        </div>

        <!-- Financial Entries -->
        <div>
          <h3 class="text-lg font-medium text-gray-900 mb-4">Financial Entries</h3>

          <!-- Income -->
          <div v-if="incomeEntries.length > 0" class="mb-6">
            <h4 class="text-sm font-medium text-green-800 mb-3">Income Sources</h4>
            <div class="space-y-2">
              <div v-for="entry in incomeEntries" :key="entry.id" class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                <div>
                  <p class="font-medium text-gray-900">{{ entry.description }}</p>
                </div>
                <p class="font-semibold text-green-600">${{ parseFloat(entry.amount).toLocaleString() }}</p>
              </div>
            </div>
          </div>

          <!-- Expenses -->
          <div v-if="expenseEntries.length > 0">
            <h4 class="text-sm font-medium text-red-800 mb-3">Expenses</h4>
            <div class="space-y-2">
              <div v-for="entry in expenseEntries" :key="entry.id" class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                <div>
                  <p class="font-medium text-gray-900">{{ entry.description }}</p>
                </div>
                <p class="font-semibold text-red-600">${{ parseFloat(entry.amount).toLocaleString() }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="border-t border-gray-200 pt-6">
          <div class="flex justify-end space-x-3">
            <a href="/farmer/plans" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg">Back to Plans</a>
            <a :href="`/farmer/plans/${plan.id}/edit`" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Edit Plan</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import DashboardLayout from '../../Shared/DashboardLayout.vue'

defineOptions({
  layout: DashboardLayout
})

const props = defineProps({
  plan: Object,
  totalIncome: Number,
  totalExpenses: Number,
  profit: Number
})

const incomeEntries = computed(() => {
  return props.plan.financials.filter(entry => entry.type === 'income')
})

const expenseEntries = computed(() => {
  return props.plan.financials.filter(entry => entry.type === 'expense')
})
</script>
