<script setup>
import UserLayout from '../../Shared/Frontend/UserLayout.vue';
</script>

<template>
    <UserLayout>
         <!-- Login Form -->
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6 text-center">Login to Your Account</h2>
                <form >
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                        <input type="email" id="email" class="w-full p-2 border rounded-md"
                            placeholder="Enter your email" required >
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                        <input type="password" id="password" class="w-full p-2 border rounded-md"
                            placeholder="Enter your password" required >
                    </div>
                    <button type="submit"
                        class="bg-blue-500 text-white w-full py-2 rounded-md hover:bg-blue-600">Login</button>
                </form>
                <p class="text-center mt-4 text-gray-600">
                    Don't have an account?
                    <Link href="/register" class="text-blue-500 hover:text-blue-700">Register here</Link>
                </p>
            </div>
        </div>
    </UserLayout>
</template>

<style scoped>

</style>