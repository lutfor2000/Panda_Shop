<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { ref, defineProps, reactive, onMounted } from 'vue';
    import { useToast } from 'vue-toastification';

    const props = defineProps({
        profile: Object,
        user: Object,
    });


</script>

<template>
     <!-- Profile Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <!-- Left Column: Profile Update Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Update Profile</h3>
            <form>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="mb-4">
                        <label for="cus_name" class="block text-gray-700 font-bold mb-2">Customer Name</label>
                        <input type="text" id="cus_name" class="w-full p-2 border rounded-md" >
                    </div>
                    <div class="mb-4">
                        <label for="cus_add" class="block text-gray-700 font-bold mb-2">Customer Address</label>
                        <input type="text" id="cus_add" class="w-full p-2 border rounded-md" >
                    </div>
                    <div class="mb-4">
                        <label for="cus_city" class="block text-gray-700 font-bold mb-2">Customer City</label>
                        <input type="text" id="cus_city" class="w-full p-2 border rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="cus_state" class="block text-gray-700 font-bold mb-2">Customer State</label>
                        <input type="text" id="cus_state" class="w-full p-2 border rounded-md" >
                    </div>
                    <div class="mb-4">
                        <label for="cus_postcode" class="block text-gray-700 font-bold mb-2">Customer Postcode</label>
                        <input type="text" id="cus_postcode" class="w-full p-2 border rounded-md"
                           >
                    </div>
                    <div class="mb-4">
                        <label for="cus_country" class="block text-gray-700 font-bold mb-2">Customer Country</label>
                        <input type="text" id="cus_country" class="w-full p-2 border rounded-md"
                           >
                    </div>
                    <div class="mb-4">
                        <label for="cus_phone" class="block text-gray-700 font-bold mb-2">Customer Phone</label>
                        <input type="text" id="cus_phone" class="w-full p-2 border rounded-md" >
                    </div>
                    <div class="mb-4">
                        <label for="cus_fax" class="block text-gray-700 font-bold mb-2">Customer Fax</label>
                        <input type="text" id="cus_fax" class="w-full p-2 border rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="ship_name" class="block text-gray-700 font-bold mb-2">Shipping Name</label>
                        <input type="text" id="ship_name" class="w-full p-2 border rounded-md" >
                    </div>
                    <div class="mb-4">
                        <label for="ship_add" class="block text-gray-700 font-bold mb-2">Shipping Address</label>
                        <input type="text" id="ship_add" class="w-full p-2 border rounded-md" >
                    </div>
                    <div class="mb-4">
                        <label for="ship_city" class="block text-gray-700 font-bold mb-2">Shipping City</label>
                        <input type="text" id="ship_city" class="w-full p-2 border rounded-md" >
                    </div>
                    <div class="mb-4">
                        <label for="ship_state" class="block text-gray-700 font-bold mb-2">Shipping State</label>
                        <input type="text" id="ship_state" class="w-full p-2 border rounded-md"
                           >
                    </div>
                    <div class="mb-4">
                        <label for="ship_postcode" class="block text-gray-700 font-bold mb-2">Shipping Postcode</label>
                        <input type="text" id="ship_postcode" class="w-full p-2 border rounded-md"
                           >
                    </div>
                    <div class="mb-4">
                        <label for="ship_country" class="block text-gray-700 font-bold mb-2">Shipping Country</label>
                        <input type="text" id="ship_country" class="w-full p-2 border rounded-md"
                           >
                    </div>
                    <div class="mb-4">
                        <label for="ship_phone" class="block text-gray-700 font-bold mb-2">Shipping Phone</label>
                        <input type="text" id="ship_phone" class="w-full p-2 border rounded-md"
                          >
                    </div>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Save Changes</button>
            </form>
        </div>

        <!-- Right Column: Change Password & Email Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Change Password & Email</h3>

            <form class="my-2" >
                <div class="mb-4">
                    <label for="current-password" class="block text-gray-700 font-bold mb-2">
                        Current Email
                    </label>
                    <input type="text" id="current-password" class="w-full p-2 border rounded-md"
                       >
                </div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md">
                    Update Mail
                </button>
            </form>

            <form>
                <div class="mb-4">
                    <label for="current-password" class="block text-gray-700 font-bold mb-2">Current
                        Password</label>
                    <input type="password" id="current-password" class="w-full p-2 border rounded-md"
                       >
                </div>
                <div class="mb-4">
                    <label for="new-password" class="block text-gray-700 font-bold mb-2">New Password</label>
                    <input type="password" id="new-password" class="w-full p-2 border rounded-md"
                       >
                </div>
                <div class="mb-4">
                    <label for="confirm-password" class="block text-gray-700 font-bold mb-2">Confirm New
                        Password</label>
                    <input type="password" id="confirm-password" class="w-full p-2 border rounded-md"
                       >
                </div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md">
                    Update Password
                </button>
            </form>
        </div>
    </div>
   
</template>