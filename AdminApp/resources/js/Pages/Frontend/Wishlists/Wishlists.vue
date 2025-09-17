<script setup>
     import { onMounted, defineProps } from 'vue';
     import { router,usePage } from '@inertiajs/vue3';
     import UserLayout from '../../Shared/Frontend/UserLayout.vue'
     import { useToast } from 'vue-toastification';
     import AddToCart from '../../../Components/Frontend/AddToCart.vue';

     const toast = useToast();

     const props = defineProps({
          wishlists: Array,
          product: Array,
     });

     const removeFromWishlist = (id)=>{
      router.delete(`/wishlists/delete/${id}`,{
         onSuccess: (page) => {
                page.props.flash.success && toast.success(page.props.flash.success);
                page.props.flash.error && toast.error(page.props.flash.error);
            },
      })

     }

   
</script>

<template>

  <UserLayout>
        <!-- Wishlist Content -->
    <div class="container mx-auto px-4 py-8" v-if="usePage().props.auth?.user && usePage().props.auth.user.role === 'user'">
      <h2 class="text-2xl font-bold mb-6">Wishlist</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

        <div v-for="(wishlist, index) in wishlists" :key="index"  class="bg-white shadow-md rounded-lg overflow-hidden">
          <img :src="wishlist.product.image" class="w-full h-48 object-cover">
          <div class="p-4">
            <h3 class="text-lg font-semibold">{{ wishlist.product.title }}</h3>
            <p class="text-gray-600">{{ wishlist.product.short_des }}</p>
            <div class="flex items-center mt-2">

              <span class="text-red-500 font-bold">
                ${{ wishlist.product.is_discount ? wishlist.product.discount_price : wishlist.product.price }}
              </span>

              <span v-show="wishlist.product.is_discount" class="text-gray-400 line-through ml-2">
                $ {{wishlist.product.price }}
              </span>

            </div>
            <div class="flex space-x-2 items-center">
              <button @click="removeFromWishlist(wishlist.id)" class="text-white hover:scale-105 mt-4 rounded-md bg-red-400 p-2 mb-4">Remove</button>
              <AddToCart :product="wishlist.product"/>
            </div>
          </div>
        </div>

        <!-- Repeat for other wishlist items -->
      </div>
    </div>

     <div  class="ml-64 p-8" v-else-if="usePage().props.auth.user.role === 'admin'"">
            <h2 class="text-2xl font-bold mb-6 text-blue-500">this is the admin dashboard</h2>
            <p class="text-gray-700">You do not have permission to view this page.</p>
      </div>
  </UserLayout>
  
</template>