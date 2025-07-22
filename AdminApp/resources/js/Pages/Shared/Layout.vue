<script setup>
    import Sidebar from './Sidebar.vue';
</script>

<template>

<div class="flex h-screen">
     <!-- Sidebar -->
        <Sidebar/>

    <!-- Main Content -->
     <slot/>
  </div> 

</template>