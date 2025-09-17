<script setup>
import { defineProps, computed, onMounted } from 'vue';
import { Link, router, usePage  } from '@inertiajs/vue3';
import Layout from '../Shared/Layout.vue';

import { useToast } from 'vue-toastification';
const toast = useToast();

import TableComponent from '../../Components/TableComponent.vue';

const props = defineProps({
    products: Array,
});

const columns = [
    { key: 'image', label: 'Image', slot: 'image-slot', searchable: false },
    { key: 'title', label: 'Title', searchable: true, sortable: true },
    { key: 'price', label: 'Price', searchable: false, sortable: true },
    { key: 'stock', label: 'Stock', searchable: false, sortable: true },
    { key: 'category', label: 'Category', slot: 'category-slot', searchale: true, sortable: true },
    { key: 'brand', label: 'Brand', slot: 'brand-slot', searchable: true, sortable: true },
    { key: 'actions', label: 'Actions', slot: 'actions-slot', searchable: false },
];


const deleteProduct = (encrypted_id) => {

        router.delete(`/products/${encrypted_id}`, {
            onSuccess: () => {
                toast.success('Product deleted successfully');
            },
            onError: () => {
                toast.error('Failed to delete product');
            },
        });
    
}


</script>

<template>
   <Layout>
        <!-- Main Content -->
        <main class="ml-64 p-8 w-[1600px] sm:min-w-[600px] md:min-w-[768px]">
            <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Products</h2>
            <Link href="/products/create" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add Product</Link>
            </div>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <TableComponent :data="products" :columns="columns" :page-size="5"> 

                    <template #image-slot="{row}">
                    <img :src="row.image" alt="" class="h-10 w-10">
                    </template>

                    <template #category-slot="{row}">
                    <span>{{ row.category_name }}</span>
                    </template>

                    <template #brand-slot="{row}">
                    <span>{{ row.brand_name }}</span>
                    </template>

                    <template #actions-slot="{row}">
                    <div class="space-x-2">
                        <Link :href="`/products/${row.encrypted_id}/edit`"
                            class="bg-yellow-500 text-white px-2 py-1 rounded-md">
                            Edit
                        </Link>
                        <button  class="bg-red-500 text-white px-2 py-1 rounded-md cursor-pointer" @click="deleteProduct(row.encrypted_id)" >
                            Delete
                        </button>
                    </div>
                    </template>

                </TableComponent>
            </div>
        </main>
   </Layout>
</template>