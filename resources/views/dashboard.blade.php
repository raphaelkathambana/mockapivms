<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vehicle Sales Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Vehicle Sales Dashboard</h1>
            <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800">← Back to Home</a>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-gray-500 text-sm font-medium">Total Vehicles</h2>
                <div class="flex items-center justify-between mt-2">
                    <div class="text-3xl font-bold">{{ $totalVehicles }}</div>
                    <div class="flex flex-col items-end">
                        <div class="text-sm text-gray-500">Available: <span
                                class="font-medium text-green-600">{{ $availableVehicles }}</span></div>
                        <div class="text-sm text-gray-500">Sold: <span
                                class="font-medium text-blue-600">{{ $soldVehicles }}</span></div>
                        <div class="text-sm text-gray-500">Reserved: <span
                                class="font-medium text-yellow-600">{{ $reservedVehicles }}</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-gray-500 text-sm font-medium">Sales</h2>
                <div class="flex items-center justify-between mt-2">
                    <div class="text-3xl font-bold">{{ $totalSales }}</div>
                    <div class="flex flex-col items-end">
                        <div class="text-sm text-gray-500">This Month: <span
                                class="font-medium text-blue-600">{{ $monthlySales }}</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-gray-500 text-sm font-medium">Revenue</h2>
                <div class="flex items-center justify-between mt-2">
                    <div class="text-3xl font-bold">€{{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    <div class="flex flex-col items-end">
                        <div class="text-sm text-gray-500">This Month: <span
                                class="font-medium text-blue-600">€{{ number_format($monthlyRevenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="text-sm text-gray-500">Total Profit: <span
                                class="font-medium text-green-600">€{{ number_format($totalProfit, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-gray-500 text-sm font-medium">Customers</h2>
                <div class="flex items-center justify-between mt-2">
                    <div class="text-3xl font-bold">{{ $totalBuyers + $totalSellers }}</div>
                    <div class="flex flex-col items-end">
                        <div class="text-sm text-gray-500">Buyers: <span
                                class="font-medium text-blue-600">{{ $totalBuyers }}</span></div>
                        <div class="text-sm text-gray-500">Sellers: <span
                                class="font-medium text-green-600">{{ $totalSellers }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Vehicle Status Chart -->
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-lg font-medium mb-4">Vehicle Status Distribution</h2>
                <div class="h-64">
                    <canvas id="vehicleStatusChart"></canvas>
                </div>
            </div>

            <!-- Inventory Age Chart -->
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-lg font-medium mb-4">Inventory Age</h2>
                <div class="h-64">
                    <canvas id="inventoryAgeChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Top Selling Models -->
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-lg font-medium mb-4">Top Selling Models</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Model</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Manufacturer</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Units Sold</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($topModels as $model)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $model->model_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $model->manufacturer_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $model->count }}
                                    </td>
                                </tr>
                            @endforeach

                            @if (count($topModels) === 0)
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No data
                                        available</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Performing Employees -->
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-lg font-medium mb-4">Top Performing Employees</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Employee</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sales Count</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Sales</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($topEmployees as $employee)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $employee->first_name }} {{ $employee->last_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $employee->sales_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        €{{ number_format($employee->total_sales, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach

                            @if (count($topEmployees) === 0)
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No data
                                        available</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <h2 class="text-lg font-medium mb-4">Recent Transactions</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contract ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vehicle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Buyer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($recentTransactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $transaction->contract_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->vin }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->buyer->first_name }} {{ $transaction->buyer->last_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->employee->first_name }} {{ $transaction->employee->last_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->contract_date->format('d.m.Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    €{{ number_format($transaction->vehicle->selling_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach

                        @if (count($recentTransactions) === 0)
                            <tr>
                                <td colspan="6"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No
                                    transactions available</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Vehicle Status Chart
        const vehicleStatusCtx = document.getElementById('vehicleStatusChart').getContext('2d');
        new Chart(vehicleStatusCtx, {
            type: 'pie',
            data: {
                labels: ['Available', 'Sold', 'Reserved'],
                datasets: [{
                    data: [{{ $availableVehicles }}, {{ $soldVehicles }}, {{ $reservedVehicles }}],
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(234, 179, 8, 0.7)'
                    ],
                    borderColor: [
                        'rgba(34, 197, 94, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(234, 179, 8, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Inventory Age Chart
        const inventoryAgeCtx = document.getElementById('inventoryAgeChart').getContext('2d');
        new Chart(inventoryAgeCtx, {
            type: 'bar',
            data: {
                labels: ['0-30 days', '31-60 days', '61-90 days', '90+ days'],
                datasets: [{
                    label: 'Number of Vehicles',
                    data: [
                        {{ $inventoryAgeStats['0-30 days'] }},
                        {{ $inventoryAgeStats['31-60 days'] }},
                        {{ $inventoryAgeStats['61-90 days'] }},
                        {{ $inventoryAgeStats['90+ days'] }}
                    ],
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
