<script setup>
    import UserLayout from '../../Shared/Frontend/UserLayout.vue';
    import Profile from './profile.vue';
    import { router,usePage  } from '@inertiajs/vue3';

    import { useToast } from 'vue-toastification';
    const toast = useToast();


    const props = defineProps({
        profile: Object,
        user: Object,
        orders: Array,
       
    })

    const invoiceDelete = (id)=>{
         router.delete(`/order/delete/${id}`,{
         onSuccess: (page) => {
                page.props.flash.success && toast.success(page.props.flash.success);
                page.props.flash.error && toast.error(page.props.flash.error);
            },
      })
    }

    const getStatusColor = (status) => {
    switch (status) {
        case 'Success':
            return 'bg-green-200 text-green-800';
        case 'Pending':
            return 'bg-yellow-200 text-yellow-800';
        case 'Fail':
            return 'bg-red-200 text-red-800';
        case 'Cancel':
            return 'bg-gray-200 text-gray-800';
        case 'Processing':
            return 'bg-blue-200 text-blue-800';
        case 'Shipped':
            return 'bg-purple-200 text-purple-800';
        case 'Delivered':
            return 'bg-green-200 text-green-800';
        default:
            return 'bg-gray-200 text-gray-800';
    }
};

const getStatusText = (status) => {
    switch (status) {
        case 'Success':
            return 'Paid';
        case 'Pending':
            return 'Pending';
        case 'Fail':
            return 'Failed';
        case 'Cancel':
            return 'Cancelled';
        case 'Processing':
            return 'Processing';
        case 'Shipped':
            return 'Shipped';
        case 'Delivered':
            return 'Delivered';
        default:
            return status;
    }
};
</script>

<template>
    <UserLayout>
        <div class="py-12" v-if="usePage().props.auth?.user?.role === 'user'">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <Profile :profile="profile" :user="user" />

                  <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-2xl font-semibold mb-6">Recent Orders</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-3 text-left">Order #</th>
                                    <th class="p-3 text-left">Date</th>
                                    <th class="p-3 text-left">Amount</th>
                                    <th class="p-3 text-left">Payment Status</th>
                                    <th class="p-3 text-left">Delivery Status</th>
                                    <th class="p-3 text-left">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="order in orders" :key="order.id" class="border-b">
                                    <td class="p-3">{{ order.order_number }}</td>
                                    <td class="p-3">{{ order.date }}</td>
                                    <td class="p-3">${{ order.amount }}</td>
                                    <td class="p-3">
                                            <span :class="getStatusColor(order.payment_status)" class="px-2 py-1 rounded-full">
                                                {{ getStatusText(order.payment_status) }}
                                            </span>
                                    </td>
                                    <td class="p-3">
                                            <span :class="getStatusColor(order.delivery_status)" class="px-2 py-1 rounded-full">
                                                {{ getStatusText(order.delivery_status) }}
                                            </span>
                                    </td>
                                    <td class="p-3">
                                        <a :href="`/order/${order.id}`" class="text-green-500 hover:text-blue-700">
                                             <i class="fa-solid fa-eye-slash"></i>
                                        </a>
                                        <button  @click="invoiceDelete(order.id)" class="text-red-500 ml-3 hover:text-blue-700">
                                           <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

         <div  class="ml-64 p-8" v-else>
            <h2 class="text-2xl font-bold mb-6 text-blue-500">this is the User dashboard</h2>
            <p class="text-gray-700">You do not have permission to view this page.</p>
        </div>
    </UserLayout>
</template>