<script setup>
    import { Link, router, useForm, usePage } from '@inertiajs/vue3';
    import { onMounted, computed } from 'vue';
    import Layout from '../Shared/Layout.vue';
    import { useToast } from 'vue-toastification';
    import TableComponent from '../../Components/TableComponent.vue';

 
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

//=========Use Data Table==========================
  const columns = [
     { key: 'id', label: 'ID', slot: 'id-slot', searchable: true, sortable: true },
      { key: 'name', label: 'Name', searchable: true, sortable: true },
      { key: 'image', label: 'Image', slot: 'image-slot', searchable: false, sortable: false },
      { key: 'actions', label: 'Actions', slot: 'actions-slot', searchable: false, sortable: false }
  ];


</script>

<template>
    <Layout>

    <main class="ml-64 p-8  w-[1600px] sm:min-w-[600px] md:min-w-[768px]">
    <div class="flex justify-between items-center mb-6 ">
      <h2 class="text-2xl font-bold">Brands</h2>
      <Link href="/brands/create" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add Brand</Link>
    </div>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">

     <TableComponent :data="brands" :columns="columns" :page-size="10">

        <template #id-slot="{ row }">
            <span class="font-bold text-gray-700">{{ row.decrypted_id }}</span>
        </template>
        
        <template #image-slot="{ row }">
              <img :src="row.image" alt="Brand Image" class="w-10 h-10 rounded-full">
        </template>

        <template #actions-slot="{ row }">
            <div class="space-x-2">
                <Link :href="`brands/${row.encrypted_id}/edit`" class="bg-yellow-500 text-white px-2 py-1 rounded-md">
                  Edit
                </Link>
                <button @click="deleteBrand(row.encrypted_id)" class="bg-red-500 text-white px-2 py-1 rounded-md">
                    Delete
                </button>
            </div>
        </template>

     </TableComponent>

    </div>
    </main>

    </Layout>
</template>