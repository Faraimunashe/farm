<template>
  <Head title="Get Insurance" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Get Insurance Coverage</h1>
        <p class="text-gray-600">Protect your farm with comprehensive insurance coverage</p>
      </div>
      <a href="/farmer/insurance" class="text-blue-600 hover:text-blue-700 font-medium">
        ← Back to Insurance
      </a>
    </div>

    <!-- Step Indicator -->
    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex items-center justify-center space-x-8">
        <div class="flex items-center" :class="{ 'text-blue-600': currentStep >= 1, 'text-gray-400': currentStep < 1 }">
          <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center mr-2" :class="{ 'border-blue-600 bg-blue-600 text-white': currentStep >= 1, 'border-gray-300': currentStep < 1 }">
            <span v-if="currentStep > 1">✓</span>
            <span v-else>1</span>
          </div>
          <span class="text-sm font-medium">Select Farm & Insurer</span>
        </div>

        <div class="flex items-center" :class="{ 'text-blue-600': currentStep >= 2, 'text-gray-400': currentStep < 2 }">
          <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center mr-2" :class="{ 'border-blue-600 bg-blue-600 text-white': currentStep >= 2, 'border-gray-300': currentStep < 2 }">
            <span v-if="currentStep > 2">✓</span>
            <span v-else>2</span>
          </div>
          <span class="text-sm font-medium">Choose Plan</span>
        </div>

        <div class="flex items-center" :class="{ 'text-blue-600': currentStep >= 3, 'text-gray-400': currentStep < 3 }">
          <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center mr-2" :class="{ 'border-blue-600 bg-blue-600 text-white': currentStep >= 3, 'border-gray-300': currentStep < 3 }">
            <span v-if="currentStep > 3">✓</span>
            <span v-else>3</span>
          </div>
          <span class="text-sm font-medium">Payment & Submit</span>
        </div>
      </div>
    </div>

    <div v-if="form.errors && form.errors.error" class="bg-red-50 border border-red-200 rounded-lg p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-red-800">{{ form.errors.error }}</p>
        </div>
      </div>
    </div>

    <!-- Step 1: Farm & Insurer Selection -->
    <div v-if="currentStep === 1" class="bg-white rounded-lg shadow p-6">
      <h2 class="text-lg font-semibold text-gray-900 mb-4">Step 1: Select Your Farm and Insurer</h2>

      <div class="space-y-6">
        <!-- Farm Selection -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Select Farm *</label>
          <select
            v-model="form.farm_id"
            @change="onFarmChange"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.farm_id }"
          >
            <option value="">Choose your farm...</option>
            <option v-for="farm in farms" :key="farm.id" :value="farm.id">
              {{ farm.name }} - {{ farm.location }}
            </option>
          </select>
          <div v-if="form.errors.farm_id" class="text-sm text-red-500 mt-1">{{ form.errors.farm_id }}</div>
        </div>

        <!-- Insurer Selection -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Select Insurer *</label>
          <select
            v-model="form.insurer_id"
            @change="onInsurerChange"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': form.errors.insurer_id }"
          >
            <option value="">Choose an insurer...</option>
            <option v-for="insurer in insurers" :key="insurer.id" :value="insurer.id">
              {{ insurer.name }} - {{ insurer.active_insurance_plans?.length || 0 }} plans available
            </option>
          </select>
          <div v-if="form.errors.insurer_id" class="text-sm text-red-500 mt-1">{{ form.errors.insurer_id }}</div>
        </div>

        <!-- Coverage Type Filter -->
        <div v-if="form.farm_id && form.insurer_id">
          <label class="block text-sm font-semibold text-gray-700 mb-2">Coverage Type (Optional)</label>
          <select
            v-model="form.coverage_type"
            @change="loadPlans"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
          >
            <option value="">All coverage types</option>
            <option value="crop">Crop Insurance</option>
            <option value="livestock">Livestock Insurance</option>
            <option value="equipment">Equipment Insurance</option>
            <option value="property">Property Insurance</option>
          </select>
        </div>

        <!-- Next Button -->
        <div class="flex justify-end">
          <button
            @click="nextStep"
            :disabled="!form.farm_id || !form.insurer_id || loading"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
          >
            <span>Next: Choose Plan</span>
            <i class="fa fa-arrow-right"></i>
          </button>
        </div>

        <!-- Debug Button -->
        <div v-if="form.farm_id && form.insurer_id" class="mt-4">
          <button
            @click="testLoadPlans"
            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm"
          >
            🐛 Debug: Test Load Plans
          </button>
        </div>
      </div>
    </div>

    <!-- Step 2: Plan Selection -->
    <div v-if="currentStep === 2" class="bg-white rounded-lg shadow p-6">
      <h2 class="text-lg font-semibold text-gray-900 mb-4">Step 2: Choose Your Insurance Plan</h2>

      <div v-if="loading" class="text-center py-8">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
        <p class="text-gray-600">Loading available plans...</p>
      </div>

      <div v-else-if="availablePlans.length === 0" class="text-center py-8">
        <div class="text-6xl mb-4">📋</div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No plans available</h3>
        <p class="text-gray-600 mb-6">No insurance plans are currently available for your selection.</p>
        <button @click="previousStep" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
          Go Back
        </button>
      </div>

      <div v-else class="space-y-4">
        <div v-for="plan in availablePlans" :key="plan.id" class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
          <div class="flex items-center justify-between">
            <div class="flex-1">
              <h3 class="text-lg font-semibold text-gray-900">{{ plan.name }}</h3>
              <p class="text-sm text-gray-600 mb-2">{{ plan.description }}</p>

              <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                  <span class="text-gray-500">Coverage:</span>
                  <div class="font-medium">${{ parseFloat(plan.calculated_coverage).toLocaleString() }}</div>
                </div>
                <div>
                  <span class="text-gray-500">Premium:</span>
                  <div class="font-medium">${{ parseFloat(plan.calculated_premium).toLocaleString() }}</div>
                </div>
                <div>
                  <span class="text-gray-500">Duration:</span>
                  <div class="font-medium">{{ plan.duration_months }} months</div>
                </div>
                <div>
                  <span class="text-gray-500">Type:</span>
                  <div class="font-medium capitalize">{{ plan.coverage_type }}</div>
                </div>
              </div>
            </div>

            <button
              @click="selectPlan(plan)"
              class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
              Select Plan
            </button>
          </div>
        </div>

        <!-- Navigation -->
        <div class="flex justify-between pt-4">
          <button @click="previousStep" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
            ← Previous
          </button>
        </div>
      </div>
    </div>

    <!-- Step 3: Payment & Submit -->
    <div v-if="currentStep === 3" class="bg-white rounded-lg shadow p-6">
      <h2 class="text-lg font-semibold text-gray-900 mb-4">Step 3: Complete Your Application</h2>

      <div v-if="selectedPlan" class="space-y-6">
        <!-- Plan Summary -->
        <div class="bg-blue-50 p-4 rounded-lg">
          <h3 class="font-medium text-blue-900 mb-2">Selected Plan: {{ selectedPlan.name }}</h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
              <span class="text-blue-600">Coverage Amount:</span>
              <div class="font-medium text-blue-900">${{ parseFloat(selectedPlan.calculated_coverage).toLocaleString() }}</div>
            </div>
            <div>
              <span class="text-blue-600">Premium Amount:</span>
              <div class="font-medium text-blue-900">${{ parseFloat(selectedPlan.calculated_premium).toLocaleString() }}</div>
            </div>
            <div>
              <span class="text-blue-600">Duration:</span>
              <div class="font-medium text-blue-900">{{ selectedPlan.duration_months }} months</div>
            </div>
            <div>
              <span class="text-blue-600">Coverage Type:</span>
              <div class="font-medium text-blue-900 capitalize">{{ selectedPlan.coverage_type }}</div>
            </div>
          </div>
        </div>

        <!-- Policy Details Form -->
        <div class="space-y-6">
          <!-- Start and End Dates -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date *</label>
              <input
                type="date"
                v-model="form.start_date"
                :min="new Date().toISOString().split('T')[0]"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                :class="{ 'border-red-500': form.errors.start_date }"
              />
              <div v-if="form.errors.start_date" class="text-sm text-red-500 mt-1">{{ form.errors.start_date }}</div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">End Date *</label>
              <input
                type="date"
                v-model="form.end_date"
                :min="form.start_date"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                :class="{ 'border-red-500': form.errors.end_date }"
              />
              <div v-if="form.errors.end_date" class="text-sm text-red-500 mt-1">{{ form.errors.end_date }}</div>
            </div>
          </div>

          <!-- Payment Method -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Payment Method *</label>
            <select
              v-model="form.payment_method"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.payment_method }"
            >
              <option value="">Select payment method...</option>
              <option value="paynow">PayNow</option>
              <option value="ecocash">EcoCash</option>
              <option value="bank_transfer">Bank Transfer</option>
            </select>
            <div v-if="form.errors.payment_method" class="text-sm text-red-500 mt-1">{{ form.errors.payment_method }}</div>
          </div>

          <!-- Ecocash Number Input -->
          <div v-if="form.payment_method === 'ecocash'">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Ecocash Number *</label>
            <input
              type="tel"
              v-model="form.ecocash_number"
              placeholder="Enter your Ecocash number"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': form.errors.ecocash_number }"
            />
            <div v-if="form.errors.ecocash_number" class="text-sm text-red-500 mt-1">{{ form.errors.ecocash_number }}</div>
          </div>

          <!-- Final Summary -->
          <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="font-medium text-gray-900 mb-3">Application Summary</h4>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-600">Farm:</span>
                <span class="font-medium">{{ getFarmName() }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Insurer:</span>
                <span class="font-medium">{{ getInsurerName() }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Plan:</span>
                <span class="font-medium">{{ selectedPlan.name }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Coverage:</span>
                <span class="font-medium">${{ parseFloat(selectedPlan.calculated_coverage).toLocaleString() }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Premium:</span>
                <span class="font-medium">${{ parseFloat(selectedPlan.calculated_premium).toLocaleString() }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Payment Method:</span>
                <span class="font-medium capitalize">{{ form.payment_method }}</span>
              </div>
              <div v-if="form.payment_method === 'ecocash'" class="flex justify-between">
                <span class="text-gray-600">Ecocash Number:</span>
                <span class="font-medium">{{ form.ecocash_number }}</span>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-between">
            <button @click="previousStep" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
              ← Previous
            </button>

            <button
              @click="submitApplication"
              :disabled="!canSubmit || submitting"
              class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
            >
              <div v-if="submitting" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
              <span v-if="!submitting">Submit Application</span>
              <span v-else>Submitting...</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import DashboardLayout from '../../Shared/DashboardLayout.vue'

defineOptions({
  layout: DashboardLayout
})

const props = defineProps({
  farms: Array,
  insurers: Array
})

const currentStep = ref(1)
const loading = ref(false)
const submitting = ref(false)
const availablePlans = ref([])
const selectedPlan = ref(null)

const form = useForm({
  farm_id: '',
  insurer_id: '',
  coverage_type: '',
  insurance_plan_id: '',
  coverage_amount: '',
  premium_amount: '',
  start_date: '',
  end_date: '',
  payment_method: '',
  ecocash_number: ''
})

const canSubmit = computed(() => {
  const basicValidation = form.farm_id &&
         form.insurer_id &&
         selectedPlan.value &&
         form.start_date &&
         form.end_date &&
         form.payment_method

  // If Ecocash is selected, require ecocash_number
  if (form.payment_method === 'ecocash') {
    return basicValidation && form.ecocash_number
  }

  return basicValidation
})

const onFarmChange = () => {
  if (form.insurer_id) {
    loadPlans()
  }
}

const onInsurerChange = () => {
  if (form.farm_id) {
    loadPlans()
  }
}

const testLoadPlans = () => {
  console.log('🧪 Testing loadPlans function...')
  console.log('Current form state:', {
    farm_id: form.farm_id,
    insurer_id: form.insurer_id,
    coverage_type: form.coverage_type
  })
  loadPlans()
}

const loadPlans = async () => {
  if (!form.farm_id || !form.insurer_id) return

  loading.value = true
  try {
    console.log('Loading plans for insurer:', form.insurer_id, 'farm:', form.farm_id)
    const response = await fetch(`/farmer/insurance/plans?insurer_id=${form.insurer_id}&farm_id=${form.farm_id}&coverage_type=${form.coverage_type}`)

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const data = await response.json()
    console.log('Plans response:', data)

    if (data.plans && Array.isArray(data.plans)) {
      availablePlans.value = data.plans
      console.log('Available plans loaded:', availablePlans.value.length)
    } else {
      console.warn('No plans data or invalid format:', data)
      availablePlans.value = []
    }
  } catch (error) {
    console.error('Error loading plans:', error)
    availablePlans.value = []
  } finally {
    loading.value = false
  }
}

const selectPlan = (plan) => {
  selectedPlan.value = plan
  form.insurance_plan_id = plan.id
  form.coverage_amount = plan.calculated_coverage
  form.premium_amount = plan.calculated_premium
  form.coverage_type = plan.coverage_type

  // Set default dates
  const startDate = new Date()
  const endDate = new Date()
  endDate.setMonth(endDate.getMonth() + plan.duration_months)

  form.start_date = startDate.toISOString().split('T')[0]
  form.end_date = endDate.toISOString().split('T')[0]

  nextStep()
}

const nextStep = () => {
  if (currentStep.value < 3) {
    currentStep.value++
  }
}

const previousStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--
  }
}

const getFarmName = () => {
  const farm = props.farms.find(f => f.id == form.farm_id)
  return farm ? farm.name : ''
}

const getInsurerName = () => {
  const insurer = props.insurers.find(i => i.id == form.insurer_id)
  return insurer ? insurer.name : ''
}

const submitApplication = () => {
  if (!canSubmit.value) return

  // Debug: Log form data before submission
  console.log('Submitting form with data:', {
    farm_id: form.farm_id,
    insurer_id: form.insurer_id,
    coverage_type: form.coverage_type,
    insurance_plan_id: form.insurance_plan_id,
    coverage_amount: form.coverage_amount,
    premium_amount: form.premium_amount,
    start_date: form.start_date,
    end_date: form.end_date,
    payment_method: form.payment_method,
    ecocash_number: form.ecocash_number
  })

  submitting.value = true
  form.post('/farmer/insurance', {
    onFinish: () => {
      submitting.value = false
    }
  })
}

onMounted(() => {
  // Set default start date to today
  form.start_date = new Date().toISOString().split('T')[0]

  // Debug: Check what data we received
  console.log('Page mounted with data:', {
    farms: props.farms,
    insurers: props.insurers
  })

  // Test: Check if insurers have plans
  if (props.insurers && props.insurers.length > 0) {
    props.insurers.forEach((insurer, index) => {
      console.log(`Insurer ${index}:`, insurer.name, 'Plans:', insurer.activeInsurancePlans?.length || 0)
    })
  }
})
</script>
