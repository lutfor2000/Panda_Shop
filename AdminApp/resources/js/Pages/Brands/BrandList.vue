<script setup>
    import { Link, router, useForm, usePage } from '@inertiajs/vue3';
    import { onMounted, computed } from 'vue';
    import Layout from '../Shared/Layout.vue';
    import { useToast } from 'vue-toastification';

 
    const toast = useToast();
    const flash = computed(() => usePage().props.flash);

    const props = defineProps({
        brands: Object
    });

//============Brands Item Delete===============================
    const deleteBrand = (encrypted_id) =>{
       router.delete(`brands/${encrypted_id}`,{
          onSuccess: () =>{
              flash.value.success && toast.success(flash.value.success);
              flash.value.error && toast.error(flash.value.error);
          },
          onError: () =>{
             toast.error('Failed to delete brand. Please try again.');
          }
       })
    }


</script>

<template>
    <Layout>

    <main class="ml-64 p-8  w-[1600px] sm:min-w-[600px] md:min-w-[768px]">
    <div class="flex justify-between items-center mb-6 ">
      <h2 class="text-2xl font-bold">Brands</h2>
      <Link href="/brands/create" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add Brand</Link>
    </div>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">

      <table class="w-full">
        <thead class="bg-gray-100 ">
          <tr>
            <th class="p-3 text-left">Name</th>
            <th class="p-3 text-left">Image</th>
            <th class="p-3 text-left">Actions</th>
          </tr>
        </thead>

        <tbody>
          <tr class="border-b " v-for="(brand, index ) in brands" :key="index">
            <td class="p-3">{{brand.name}}</td>

            <td class="p-3"><img :src ="brand.image" alt="Brand Image" 
              class="w-10 h-10 rounded-full"></td>

            <td class="p-3 space-x-2">
              <Link :href="`brands/${brand.encrypted_id}/edit`" class="bg-yellow-500 text-white px-2 py-1 rounded-md">Edit</Link>
              <button class="bg-red-500 text-white px-2 py-1 rounded-md" @click="deleteBrand(brand.encrypted_id)">Delete</button>
            </td>
          </tr>
          <!-- Repeat for other brands -->
        </tbody>

      </table>

    </div>
    </main>

    </Layout>
</template>