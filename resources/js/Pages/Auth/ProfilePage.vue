<template>
  <Head title="Profile" />
  <div class="p-6 min-h-screen bg-gray-100 flex flex-col items-center">
    <!-- Breadcrumb -->
    <div class="w-full max-w-7xl flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
          <i class="fas fa-user-circle text-blue-600"></i> Profile Settings
        </h1>
        <nav class="mt-1 text-sm text-gray-500 flex items-center gap-2">
          <a href="#" class="hover:text-blue-600 flex items-center gap-1">
            <i class="fas fa-home"></i> Home
          </a>
          <span>/</span>
          <span class="text-gray-800 font-medium flex items-center gap-1">
            <i class="fas fa-user"></i> Profile
          </span>
        </nav>
      </div>
    </div>

    <div class="w-full max-w-7xl bg-white rounded-2xl shadow-xl p-8 border border-blue-100">
  <div class="flex flex-col lg:flex-row gap-10">

    <!-- Profile Info -->
    <div class="flex flex-col items-center text-center border-b lg:border-b-0 lg:border-r pb-6 lg:pb-0 pr-0 lg:pr-10 w-full lg:w-1/3 relative bg-gradient-to-b from-blue-50 to-white rounded-xl shadow-inner">
        <!-- Profile Avatar with Badge -->
        <div class="relative">
            <img
            class="w-32 h-32 rounded-full border-4 border-blue-500 shadow-xl object-cover"
            src="../../../assets/userr.jpg"
            alt="Profile Picture"
            />
            <span class="absolute bottom-0 right-1 transform translate-y-1/2 bg-green-500 border-2 border-white w-4 h-4 rounded-full shadow"></span>
        </div>

        <!-- Name -->
        <h2 class="text-2xl font-bold text-gray-800 mt-4 tracking-wide">
            {{ user.name }}
        </h2>

        <!-- Email -->
        <p class="text-gray-600 mt-2 flex items-center justify-center gap-2 text-sm">
            <i class="fa fa-envelope text-blue-400"></i>
            {{ user.email }}
        </p>

        <!-- Role (optional add-on) -->
        <p class="mt-2 text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-medium shadow-sm">
            {{ user.roles[0].display_name }}
        </p>
    </div>


    <!-- Change Password Form -->
    <div class="w-full lg:w-2/3">
      <h3 class="text-xl font-semibold mb-4 flex items-center text-blue-700">
        <i class="fa fa-lock text-blue-500 mr-2"></i> Change Password
      </h3>

      <div v-if="form.wasSuccessful" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Success! </strong>
        <span class="block sm:inline">Password was changed successfully</span>
      </div>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-gray-700 font-medium mb-1 flex items-center">
            <i class="fa fa-key text-gray-500 mr-2"></i> Current Password
          </label>
          <input type="password" v-model="form.current_password" :class="{'border-red-500': form.errors.current_password}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required />
          <div v-if="form.errors.current_password" class="text-red-500 text-sm mt-1">{{ form.errors.current_password }}</div>
          <div v-if="form.errors.error" class="text-red-500 text-sm mt-1">{{ form.errors.error }}</div>
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-1 flex items-center">
            <i class="fa fa-lock text-gray-500 mr-2"></i> New Password
          </label>
          <input type="password" v-model="form.new_password" :class="{'border-red-500': form.errors.new_password}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required />
          <div v-if="form.errors.new_password" class="text-red-500 text-sm mt-1">{{ form.errors.new_password }}</div>
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-1 flex items-center">
            <i class="fa fa-lock text-gray-500 mr-2"></i> Confirm Password
          </label>
          <input type="password" v-model="form.password_confirmation" :class="{'border-red-500': form.errors.password_confirmation}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required />
          <div v-if="form.errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ form.errors.password_confirmation }}</div>
        </div>

        <div class="flex justify-end">
          <button :disabled="loading" type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2 shadow-md">
            <span v-if="loading" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
            <span v-if="!loading"><i class="fa fa-save"></i> Save Changes</span>
            <span v-if="loading">Changing...</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

  </div>
</template>

<script>
import Layout from "../../Shared/Layout.vue";
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

export default {
  layout: Layout,
  props: {
    user: Object
  },
  setup() {
    const form = useForm({
      current_password: '',
      new_password: '',
      password_confirmation: ''
    });

    const loading = ref(false);

    const submit = () => {
      loading.value = true;
      form.post('/profiles', {
        onFinish: () => {
          loading.value = false;
        }
      });
    };

    watch(() => form.errors.error, (error) => {
      if (error) {
        console.log(error);
      }
    });

    return {
      form,
      submit,
      loading
    };
  },
};
</script>
