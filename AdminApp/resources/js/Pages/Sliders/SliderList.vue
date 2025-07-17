<script setup>
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, defineProps, onMounted } from 'vue';
import { useToast } from 'vue-toastification';
import Layout from '../Shared/Layout.vue';

import TableComponent from '../../Components/TableComponent.vue';

const flash = computed(() => usePage().props.flash);
const toast = useToast();

const props = defineProps({
        sliders: Object
});

const deleteSlide = (encrypted_id)=>{

        if (confirm('Are you sure you want to delete this slider?')) {
            router.delete(`/sliders/${encrypted_id}`,{
                onSuccess: ()=>{
                    flash.value.success && toast.success(flash.value.success);
                    flash.value.error && toast.error(flash.value.error);
                },

                onError: () => {
                toast.error('Failed to delete slide. Please try again.');
                }

            })
        }

}


</script>

<template>
     <Layout>
        <!-- Main Content -->
        <main class="ml-64 p-8 lg:min-w-[1460px] sm:min-w-[500px] md:min-w-[768px]">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Slides</h2>
                <a href="/sliders/create" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add Slide</a>
            </div>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Image</th>
                            <th class="p-3 text-left">Is Active</th>
                            <th class="p-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b" v-for="(item, index) in sliders" :key="index">
                            <td class="p-3"> <img :src="item.image"  class="w-10 h-10 rounded-full" /></td>
                            <td class="p-3">
                                <span v-if="item.active" class="bg-green-500 text-white px-2 py-1 rounded-md">Yes</span>
                                <span v-else class="bg-red-500 text-white px-2 py-1 rounded-md">No</span>
                            </td>
                            <td class="p-3 space-x-2">
                                <div class="space-x-2">
                                    <Link :href="`sliders/${item.encrypted_id}/edit`"
                                        class="bg-yellow-500 text-white px-2 py-1 rounded-md">
                                         Edit
                                    </Link>
                                    <button @click="deleteSlide(item.encrypted_id)"
                                        class="bg-red-500 text-white px-2 py-1 rounded-md cursor-pointer">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Repeat for other slides -->
                    </tbody>
                </table>
            </div>
        </main>
    </Layout>
</template>
