<script setup>
    import { ref, onMounted } from 'vue'
    import { Link,usePage } from '@inertiajs/vue3'
    import Layout from './Shared/Layout.vue';
    import { Chart, BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend } from 'chart.js'
    Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend)

    const props = defineProps({
        total_orders: Number,
        total_products: Number,
        total_revenue: Number,
        TotalPayment: Number,
    });

   //-----------Add Chart  chart add cmd-> npm install chart.js---------------
  
    const chartRef = ref(null)
    let chartInstance = null

    const buildData = () => [
        Number(props.total_products ?? 0),
        Number(props.total_orders ?? 0),
        Number(props.total_revenue ?? 0),
        Number(props.TotalPayment ?? 0),
    ] 

    onMounted(() => {
    const ctx = chartRef.value.getContext('2d')
    chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [ 'Products','Orders', 'Revenue', 'Payment'],
            datasets: [{
                label: 'Dashboard Summary',
                data: buildData(),
                backgroundColor: ['#3b82f6','#FFBF00','#ef4444','#22c55e']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    })
  })



</script>

<template>

   <Layout>

        <main class="ml-64 p-8 " v-if="usePage().props.auth?.user && usePage().props.auth.user.role === 'admin'">
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            <div class="bg-white shadow-md p-4 rounded-lg">
                <h3 class="text-lg font-semibold">Total Products</h3>
                <p class="text-3xl text-blue-500">{{ total_products }}</p>
            </div>

            <div class="bg-white shadow-md p-4 rounded-lg">
                <h3 class="text-lg font-semibold">Total Orders</h3>
                <p class="text-3xl text-yellow-400">{{ total_orders }}</p>
            </div>

            <div class="bg-white shadow-md p-4 rounded-lg">
                <h3 class="text-lg font-semibold">Total Revenue</h3>
                <p class="text-3xl text-red-500">{{ total_revenue }}</p>
            </div>

            <div class="bg-white shadow-md p-4 rounded-lg">
                <h3 class="text-lg font-semibold">Total Payment</h3>
                <p class="text-3xl text-green-500">{{ TotalPayment }}</p>
            </div>

        </div>


        <!-- Chart Section -->
        <div class="bg-white m-auto shadow-md p-6 rounded-lg">
            <h3 class="text-lg font-semibold mb-4">Summary Chart</h3>
            <canvas ref="chartRef" class="w-full h-64"></canvas>
        </div>

        </main>

        <div v-else class="ml-64 p-8">
            <h2 class="text-2xl font-bold mb-6 text-blue-500">this is the admin dashboard</h2>
            <p class="text-gray-700">You do not have permission to view this page.</p>
        </div>

   </Layout>

</template>
