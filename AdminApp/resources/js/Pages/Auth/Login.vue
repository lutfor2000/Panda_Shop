
<script setup>
    import { onMounted, computed } from 'vue';
    import Layout from '../Shared/Layout.vue';
    import { useForm, usePage } from '@inertiajs/vue3';
    import { useToast } from 'vue-toastification';
    const toast = useToast();

    const flash = computed(() => usePage().props.flash);

    const form = useForm({
        email: '',
        password: ''
    });

    const login = () =>{

        if(form.email.length === 0){
             toast.warning("Email is required");
        }
        else if(form.password.length === 0){
             toast.warning("Password is required");
        }
        else{

            form.post('/login',{
              onSuccess: () =>{
                flash.value.success && toast.success(flash.value.success);
                flash.value.error && toast.error(flash.value.error); 
              }

            })

        }
       
    }

</script>

<template>
    <!-- Login Form -->
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
      <h2 class="text-2xl font-bold mb-6 text-center">Login to Your Account</h2>
      <form @submit.prevent ="login">
        <div class="mb-4">
          <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
          <input type="email" id="email" class="w-full p-2 border rounded-md"  v-model="form.email" placeholder="Enter your email" >
        </div>
        <div class="mb-6">
          <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
          <input type="password" id="password" class="w-full p-2 border rounded-md"  v-model="form.password" placeholder="Enter your password">
        </div>
        <button type="submit" class="bg-blue-500 text-white w-full py-2 rounded-md hover:bg-blue-600">Login</button>
      </form>
      <p class="text-center mt-4 text-gray-600">
        Don't have an account? 
        <a href="/register.html" class="text-blue-500 hover:text-blue-700">Register here</a>
      </p>
    </div>
  </div>
</template>

