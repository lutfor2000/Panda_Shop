<script setup>
import Swiper from 'swiper';
import { Navigation, Pagination,Autoplay  } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/autoplay';
import { onMounted } from 'vue';

const props = defineProps({
    sliders: Array
});

onMounted(() => {
    new Swiper('.swiper-container', {
        modules: [Navigation, Pagination, Autoplay],
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        loop: true,
        autoplay: {
            delay: 3000,
        },
    });
});

</script>

<template>
    <div class="swiper">
        <div class="swiper-container relative overflow-hidden mt-0" style="height: 750px;">
            <div class="swiper-wrapper">
                <div v-for="(slider, index) in sliders" :key="index" class="swiper-slide">
                    <img :src="slider.image" alt="Promo Banner 1"
                        class="w-full h-full object-cover">
                </div>
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
            <!-- Navigation Buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</template>
