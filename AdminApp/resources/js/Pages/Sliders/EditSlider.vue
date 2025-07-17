<script setup>
    import { Link, router, useForm, usePage, } from '@inertiajs/vue3';
    import { onMounted,computed,ref } from 'vue';
    import Layout from '../Shared/Layout.vue';
    import { useToast } from 'vue-toastification';

    const flash = computed(() => usePage().props.flash);
    const toast = useToast();

     const props = defineProps({
        slider: Object
    });

    const SlideImage = ref(null);

    const form = useForm({
        active : '',
        image : null,
        '_method': 'PUT',
    });

    //===============Changing the image will show a new preview=============
    const handleImage = (event) =>{
        const file = event.target.files[0];       
         if (file) {
            form.image = file;
            SlideImage.value = URL.createObjectURL(file);
        }
    }


    //=================Show old image during edit=========================
    onMounted(() => {
        if (props.slider) {
            form.name = props.slider.name;
            SlideImage.value = '/' + props.slider.image;
    
           
        }
    });


    const submitForm = ()=>{
        form.post(`/sliders/${props.slider.id}`, {
            onSuccess: () => {
                flash.value.success && toast.success(flash.value.success);
                flash.value.error && toast.error(flash.value.error);
            },
            onError: () => {
                toast.error('Failed to update slide. Please try again.');
            }
        });
    }
 

</script>

<template>
  <Layout>
        <!-- Main Content -->
        <main class="ml-64 p-8 lg:min-w-[1460px] sm:min-w-[500px] md:min-w-[768px]">
            <h2 class="text-2xl font-bold mb-6">Edit Slide</h2>
            <form class="bg-white shadow-md rounded-lg p-6"  @submit.prevent="submitForm">
                <div class="mb-4">
                    <label for="slide-active" class="block text-gray-700 font-bold mb-2">Is Active</label>
                    <select id="slide-active" class="w-full p-2 border rounded-md" v-model="form.active"  required>
                        <option value="">---Select---</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="slide-image" class="block text-gray-700 font-bold mb-2">Image</label>
                    <input type="file" id="slide-image" class="w-full p-2 border rounded-md" 
                        v-on:change="handleImage">
                </div>
                <div class="mb-4">
                    <img :src="SlideImage"  alt="Slide Image" class="w-32 h-32 rounded-md mb-4 ">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add Slide</button>
            </form>
        </main>
    </Layout>
    
</template>