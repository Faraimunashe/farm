<template>
  <Head title="Insurance Plan Details" />

  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Insurance Plan Details</h1>
        <p class="text-gray-600">View comprehensive plan information and active policies</p>
      </div>
      <div class="flex space-x-3">
        <a href="/insurer/plans" class="text-blue-600 hover:text-blue-700">← Back to Plans</a>
        <a :href="`/insurer/plans/${plan.id}/edit`" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Edit Plan</a>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-start">
          <div>
            <h2 class="text-xl font-semibold text-gray-900">{{ plan.name }}</h2>
            <p class="text-gray-600">{{ plan.description }}</p>
            <div class="mt-2 flex items-center space-x-4">
              <span :class="[
                'text-xs px-2 py-1 rounded-full',
                plan.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
              ]">
                {{ plan.is_active ? 'Active' : 'Inactive' }}
              </span>
              <span class="text-sm text-gray-500">{{ plan.coverage_type }} Insurance</span>
            </div>
          </div>
        </div>
      </div>

      <div class="p-6 space-y-6">
        <!-- Plan Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-3">Coverage Details</h3>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">Coverage Type</dt>
                <dd class="text-sm font-semibold text-gray-900 capitalize">{{ plan.coverage_type }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Coverage Percentage</dt>
                <dd class="text-sm font-semibold text-gray-900">{{ plan.coverage_percentage }}%</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Premium Rate</dt>
                <dd class="text-sm font-semibold text-gray-900">{{ plan.premium_rate }}%</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Duration</dt>
                <dd class="text-sm font-semibold text-gray-900">{{ plan.duration_months }} months</dd>
              </div>
            </dl>
          </div>

          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-3">Financial Information</h3>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">Minimum Coverage</dt>
                <dd class="text-lg font-semibold text-gray-900">${{ parseFloat(plan.min_coverage || 0).toLocaleString() }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Maximum Coverage</dt>
                <dd class="text-lg font-semibold text-gray-900">${{ parseFloat(plan.max_coverage || 0).toLocaleString() }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Deductible Amount</dt>
                <dd class="text-sm font-semibold text-gray-900">${{ parseFloat(plan.deductible_amount || 0).toLocaleString() }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Active Policies</dt>
                <dd class="text-sm text-gray-900">{{ plan.insurances?.length || 0 }}</dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- Terms and Conditions -->
        <div v-if="plan.terms_conditions || plan.exclusions" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div v-if="plan.terms_conditions">
            <h3 class="text-lg font-medium text-gray-900 mb-3">Terms & Conditions</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
              <p class="text-sm text-gray-700 whitespace-pre-line">{{ plan.terms_conditions }}</p>
            </div>
          </div>

          <div v-if="plan.exclusions">
            <h3 class="text-lg font-medium text-gray-900 mb-3">Exclusions</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
              <p class="text-sm text-gray-700 whitespace-pre-line">{{ plan.exclusions }}</p>
            </div>
          </div>
        </div>

        <!-- Plan Statistics -->
        <div class="bg-gray-50 p-4 rounded-lg">
          <h3 class="text-lg font-medium text-gray-900 mb-3">Plan Statistics</h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
              <div class="text-2xl font-bold text-blue-600">{{ plan.insurances?.length || 0 }}</div>
              <div class="text-sm text-gray-600">Total Policies</div>
            </div>
            <div class="text-center">
              <div class="text-2xl font-bold text-green-600">{{ plan.insurances?.filter(p => p.status === 'active').length || 0 }}</div>
              <div class="text-sm text-gray-600">Active Policies</div>
            </div>
            <div class="text-center">
              <div class="text-2xl font-bold text-yellow-600">{{ plan.insurances?.filter(p => p.status === 'pending').length || 0 }}</div>
              <div class="text-sm text-gray-600">Pending Policies</div>
            </div>
            <div class="text-center">
              <div class="text-2xl font-bold text-red-600">{{ plan.insurances?.filter(p => p.status === 'expired').length || 0 }}</div>
              <div class="text-sm text-gray-600">Expired Policies</div>
            </div>
          </div>
        </div>

        <!-- Active Policies -->
        <div v-if="plan.insurances && plan.insurances.length > 0">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Active Policies</h3>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Policy #</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Farmer</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Farm</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Coverage</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Premium</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="policy in plan.insurances" :key="policy.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ policy.policy_number || 'N/A' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ policy.user?.name || 'N/A' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ policy.farm?.name || 'N/A' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${{ parseFloat(policy.coverage_amount || 0).toLocaleString() }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${{ parseFloat(policy.premium_amount || 0).toLocaleString() }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="[
                      'text-xs px-2 py-1 rounded-full',
                      policy.status === 'active' ? 'bg-green-100 text-green-800' :
                      policy.status === 'expired' ? 'bg-red-100 text-red-800' :
                      policy.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                      'bg-gray-100 text-gray-800'
                    ]">
                      {{ policy.status }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="border-t border-gray-200 pt-6">
          <div class="flex justify-end space-x-3">
            <a href="/insurer/plans" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg">Back to Plans</a>
            <a :href="`/insurer/plans/${plan.id}/edit`" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Edit Plan</a>
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
  plan: Object
})
</script>
