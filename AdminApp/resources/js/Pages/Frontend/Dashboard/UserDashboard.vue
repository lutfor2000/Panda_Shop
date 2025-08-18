<script setup>
    import UserLayout from '../../Shared/Frontend/UserLayout.vue';
    import Profile from './profile.vue';

    const props = defineProps({
        profile: Object,
        user: Object,
       
    })
</script>

<template>
    <UserLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <Profile :profile="profile" :user="user" />
                
            </div>
        </div>
    </UserLayout>
</template>