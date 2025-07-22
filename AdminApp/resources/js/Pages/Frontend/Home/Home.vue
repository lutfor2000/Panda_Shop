<script setup>
import UserLayout from '../../Shared/Frontend/UserLayout.vue';
import Slider from './Slider.vue';
import HomeFilter from './HomeFilter.vue';

const props = defineProps({
  sliders : Array,
  products: Array,
})

</script>

<template>
    <UserLayout>
         <Slider :sliders="sliders"/>
          <HomeFilter :products="products" />
    </UserLayout>
</template>