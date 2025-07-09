<script setup>
    import { ref, computed,onMounted } from 'vue';
    import Layout from '../Shared/Layout.vue';

    import { Link, useForm, usePage } from '@inertiajs/vue3';
    import { useToast } from 'vue-toastification';

    const toast = useToast();
    const flash = computed(() => usePage().props.flash);

    const props = defineProps({
        category: Object
    });

    const CategoryImage = ref(null);

    const form = useForm({
        name : '',
        image : null,
        '_method': 'PUT',
    });


//===============Changing the image will show a new preview=============
    const handleImage = (event) =>{
        const file = event.target.files[0];       
         if (file) {
            form.image = file;
            CategoryImage.value = URL.createObjectURL(file);
        }
    }


//=================Show old image during edit=========================
    onMounted(() => {
        if (props.category) {
            form.name = props.category.name;
            CategoryImage.value = '/' + props.category.image;
    
           
        }
    });

//===============Submit Form==================
const submitForm = () =>{
     if (form.name.length === 0) {
         toast.warning("Name is required");
     }
     else{
        form.post(`/categories/${props.category.id}`,{
              onSuccess: () => {
                flash.value.success && toast.success(flash.value.success);
                flash.value.error && toast.error(flash.value.error);
            },
            onError: () => {
                toast.error('Failed to add Category. Please try again.');
            }
        })
     }
}



</script>

<template>
    <Layout>
          <!-- Main Content -->
            <main class="ml-64 p-8 lg:min-w-[1460px] sm:min-w-[500px] md:min-w-[768px]">
        
                   <h2 class="text-2xl font-bold mb-6">Edit Categories</h2>
                <form class="bg-white shadow-md rounded-lg p-6" @submit.prevent="submitForm">

                <div class="mb-4">
                    <label for="category-name" class="block text-gray-700 font-bold mb-2">Name</label>
                    <input type="text" id="category-name" class="w-full p-2 border rounded-md" placeholder="Enter Category name" v-model="form.name" >
                </div>

                <div class="mb-4">
                    <label for="brand-image" class="block text-gray-700 font-bold mb-2">Image</label>
                    <input type="file" id="image" class="w-full p-2 border rounded-md"  v-on:change="handleImage">
                </div>

                <div class="mb-4 text-center">
                    <img :src="CategoryImage" alt=""
                        class="w-25 h-25 rounded-full mb-2">
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add Brand</button>
                </form>
              
            </main>
    </Layout>
</template>