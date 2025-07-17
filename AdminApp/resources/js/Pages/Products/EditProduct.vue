<script setup>
import { defineProps, ref, computed, reactive, onMounted } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Layout from '../Shared/Layout.vue';
import { useToast } from 'vue-toastification';

const toast = useToast();
const flash = computed(() => usePage().props.flash);
 
const images = reactive({
      image: null,
      img1: null,
      img2: null,
      img3: null,
      img4: null,
    });


 const props = defineProps({
      categories: Array,
      brands: Array,
      product: Object,
   })


   const form = useForm({
      title: '',
      short_des: '',
      price: '',
      discount_price: '',
      is_discount: 0,
      image: null,
      in_stock: 1,
      stock: '',
      remark: 'regular',
      category: '',
      brand: '',
      img1: null,
      img2: null,
      img3: null,
      img4: null,
      description: '',
      color: '',
      size: '',
      '_method': 'PUT',
   });

//===============Changing the image will show a new preview=============
   const handleImageChange = (event, field) => {
      const file = event.target.files[0];
      if (file) {
         form[field] = file;
         images[field] = URL.createObjectURL(file);
      }
   }
  
onMounted(() => {
    if (props.product) {
        form.title = props.product.title;
        form.short_des = props.product.short_des;
        form.price = props.product.price;
        form.discount_price = props.product.discount_price;
        form.is_discount = props.product.is_discount;
        form.in_stock = props.product.in_stock;
        form.stock = props.product.stock;
        form.remark = props.product.remark;
        form.category = props.product.category_id;
        form.brand = props.product.brand_id;
        form.description = props.product.details.description;
        form.color = props.product.details.color;
        form.size = props.product.details.size;
        images.image = '/' + props.product.image
        images.img1 = '/' + props.product.details.img1;
        images.img2 = '/' + props.product.details.img2;
        images.img3 = '/' + props.product.details.img3;
        images.img4 = '/' + props.product.details.img4;
    }
});

const SubmitForm =()=>{
    form.post(`/products/${props.product.id}`,{
       onSuccess: ()=>{
         flash.value.success && toast.success(flash.value.success);
         flash.value.error && toast.error(flash.value.error);
       }
    });
}

</script>

<template>
    <Layout>
        <!-- Main Content -->
        <main class="ml-64 p-8 lg:min-w-[1460px] sm:min-w-[500px] md:min-w-[768px]">
            <h2 class="text-2xl font-bold mb-6">Edit Product</h2>
            <form class="bg-white shadow-md rounded-lg p-6" @submit.prevent="SubmitForm">
            <!-- Product Details -->
            <div class="mb-4">
                <label for="product-name" class="block text-gray-700 font-bold mb-2">Product Name</label>
                <input type="text" v-model="form.title" id="product-name" class="w-full p-2 border rounded-md" placeholder="Enter product name" required>
            </div>
            <div class="mb-4">
                <label for="short-description" class="block text-gray-700 font-bold mb-2">Short Description</label>
                <textarea id="short-description" v-model="form.short_des" class="w-full p-2 border rounded-md" rows="2" placeholder="Enter short description (max 500 characters)" maxlength="500" required></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                <label for="price" class="block text-gray-700 font-bold mb-2">Price</label>
                <input type="number" v-model="form.price" step="0.01" id="price" class="w-full p-2 border rounded-md" placeholder="Enter price" required>
                </div>
                <div>
                <label for="discount-price" class="block text-gray-700 font-bold mb-2">Discount Price</label>
                <input type="number" v-model="form.discount_price" step="0.01" id="discount-price" class="w-full p-2 border rounded-md" placeholder="Enter discount price (optional)">
                </div>
            </div>
            <div class="mb-4">
                <label for="is-discount" class="block text-gray-700 font-bold mb-2">Is Discounted?</label>
                <select id="is-discount" v-model="form.is_discount" class="w-full p-2 border rounded-md">
                <option value="0">No</option>
                <option value="1">Yes</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="product-image" class="block text-gray-700 font-bold mb-2">Main Image</label>
                <input type="file" id="product-image" class="w-full p-2 border rounded-md" @change="event => handleImageChange(event, 'image')" >
                <img v-if="images.image" :src="images.image" alt="" class="w-20 h-20 mt-2 rounded-full">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                <label for="in-stock" class="block text-gray-700 font-bold mb-2">In Stock?</label>
                <select id="in-stock" v-model="form.in_stock" class="w-full p-2 border rounded-md">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
                </div>
                <div>
                <label for="stock" class="block text-gray-700 font-bold mb-2">Stock Quantity</label>
                <input type="number" v-model="form.stock" id="stock" class="w-full p-2 border rounded-md" placeholder="Enter stock quantity" min="0">
                </div>
            </div>

            <div class="mb-4">
                <label for="remark" class="block text-gray-700 font-bold mb-2">Remark</label>
                <select id="remark" v-model="form.remark" class="w-full p-2 border rounded-md">
                <option value="regular">Regular</option>
                <option value="popular">Popular</option>
                <option value="new">New</option>
                <option value="top">Top</option>
                <option value="special">Special</option>
                <option value="trending">Trending</option>
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                <label for="category" class="block text-gray-700 font-bold mb-2">Category</label>
                <select id="category" class="w-full p-2 border rounded-md" v-model="form.category">
                    <option value="">Select Category</option>
                    <option v-for="(category, index) in props.categories" :key="index" :value="category.id">{{ category.name }}</option>
                </select>
                </div>
                <div>
                <label for="brand" class="block text-gray-700 font-bold mb-2">Brand</label>
                <select id="brand" class="w-full p-2 border rounded-md" v-model="form.brand">
                    <option value="">Select Brand</option>
                    <option v-for="(brand, index) in brands" :key="index" :value="brand.id">{{ brand.name }}</option>
                </select>
                </div>
            </div>


            <!-- Product Details Section -->
            <h3 class="text-xl font-bold mb-4 mt-5">Product Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                <div>
                <label for="img1" class="block text-gray-700 font-bold mb-2">Image 1</label>
                <input type="file" id="img1" class="w-full p-2 border rounded-md"  @change="event => handleImageChange(event, 'img1')" >
                <img v-if="images.img1" :src="images.img1" alt="" class="w-20 h-20 mt-2 rounded-full">
                </div>

                <div>
                <label for="img2" class="block text-gray-700 font-bold mb-2">Image 2</label>
                <input type="file" id="img2" class="w-full p-2 border rounded-md" @change="event => handleImageChange(event, 'img2')">
                <img v-if="images.img2" :src="images.img2" alt="" class="w-20 h-20 mt-2 rounded-full">
                </div>

                <div>
                <label for="img3" class="block text-gray-700 font-bold mb-2" >Image 3</label>
                <input type="file" id="img3" class="w-full p-2 border rounded-md" @change="event => handleImageChange(event, 'img3')">
                <img v-if="images.img3" :src="images.img3" alt="" class="w-20 h-20 mt-2 rounded-full">
                </div>

                <div>
                <label for="img4" class="block text-gray-700 font-bold mb-2" >Image 4</label>
                <input type="file" id="img4" class="w-full p-2 border rounded-md" @change="event => handleImageChange(event, 'img4')">
                <img v-if="images.img4" :src="images.img4" alt="" class="w-20 h-20 mt-2 rounded-full">
                </div>

            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-bold mb-2">Full Description</label>
                <textarea id="description" v-model="form.description" class="w-full p-2 border rounded-md" rows="6" placeholder="Enter full description"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                <label for="color" class="block text-gray-700 font-bold mb-2">Color</label>
                <input type="text" v-model="form.color" id="color" class="w-full p-2 border rounded-md" placeholder="Enter color">
                </div>
                <div>
                <label for="size" class="block text-gray-700 font-bold mb-2">Size</label>
                <input type="text" v-model="form.size" id="size" class="w-full p-2 border rounded-md" placeholder="Enter size">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add Product</button>
            </form>
        </main>
    </Layout>
</template>