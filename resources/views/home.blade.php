<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vehicle Sales Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-8 text-center">Vehicle Sales Management System</h1>

        <div class="max-w-6xl mx-auto">
            <!-- Dashboard Card -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-md p-6 mb-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold mb-2">Sales Dashboard</h2>
                        <p class="text-blue-100">View key metrics, statistics, and performance indicators</p>
                    </div>
                    <a href="{{ url('/dashboard') }}"
                        class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        View Dashboard →
                    </a>
                </div>
            </div>

            <!-- VMS Jobs Card -->
            <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-lg shadow-md p-6 mb-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold mb-2">VMS Jobs</h2>
                        <p class="text-green-100">Execute vehicle procurement and sales processes</p>
                    </div>
                    <a href="{{ url('/vms-jobs') }}"
                        class="bg-white text-green-600 hover:bg-green-50 px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        View Jobs →
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Database Models</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Vehicle Model Card -->
                    <a href="{{ url('/vehicles') }}" class="block">
                        <div
                            class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-blue-500">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-blue-600">Vehicles</h3>
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Primary</span>
                            </div>
                            <p class="text-gray-600 mt-2 text-sm">Manage and visualize vehicle inventory data</p>
                            <div class="mt-4 flex justify-end">
                                <span class="text-blue-600 text-sm">View Model →</span>
                            </div>
                        </div>
                    </a>

                    <!-- Buyer Model Card -->
                    <a href="{{ url('/buyers') }}" class="block">
                        <div
                            class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-blue-500">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-blue-600">Buyers</h3>
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Model</span>
                            </div>
                            <p class="text-gray-600 mt-2 text-sm">Manage customer data for vehicle buyers</p>
                            <div class="mt-4 flex justify-end">
                                <span class="text-blue-600 text-sm">View Model →</span>
                            </div>
                        </div>
                    </a>

                    <!-- Seller Model Card -->
                    <a href="{{ url('/sellers') }}" class="block">
                        <div
                            class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-blue-500">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-blue-600">Sellers</h3>
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Model</span>
                            </div>
                            <p class="text-gray-600 mt-2 text-sm">Manage data for vehicle sellers</p>
                            <div class="mt-4 flex justify-end">
                                <span class="text-blue-600 text-sm">View Model →</span>
                            </div>
                        </div>
                    </a>

                    <!-- Employee Model Card -->
                    <a href="{{ url('/employees') }}" class="block">
                        <div
                            class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-blue-500">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-blue-600">Employees</h3>
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Model</span>
                            </div>
                            <p class="text-gray-600 mt-2 text-sm">Manage employee data and roles</p>
                            <div class="mt-4 flex justify-end">
                                <span class="text-blue-600 text-sm">View Model →</span>
                            </div>
                        </div>
                    </a>

                    <!-- Purchase Contract Model Card -->
                    <a href="{{ url('/purchase-contracts') }}" class="block">
                        <div
                            class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-blue-500">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-blue-600">Purchase Contracts</h3>
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Model</span>
                            </div>
                            <p class="text-gray-600 mt-2 text-sm">Manage vehicle purchase contracts</p>
                            <div class="mt-4 flex justify-end">
                                <span class="text-blue-600 text-sm">View Model →</span>
                            </div>
                        </div>
                    </a>

                    <!-- Vehicle Registration Model Card -->
                    <a href="{{ url('/vehicle-registrations') }}" class="block">
                        <div
                            class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-blue-500">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-blue-600">Vehicle Registrations</h3>
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Model</span>
                            </div>
                            <p class="text-gray-600 mt-2 text-sm">Manage vehicle registration documents</p>
                            <div class="mt-4 flex justify-end">
                                <span class="text-blue-600 text-sm">View Model →</span>
                            </div>
                        </div>
                    </a>

                    <!-- Additional Equipment Model Card -->
                    <a href="{{ url('/additional-equipment') }}" class="block">
                        <div
                            class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-blue-500">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-blue-600">Additional Equipment</h3>
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Model</span>
                            </div>
                            <p class="text-gray-600 mt-2 text-sm">Manage vehicle additional equipment</p>
                            <div class="mt-4 flex justify-end">
                                <span class="text-blue-600 text-sm">View Model →</span>
                            </div>
                        </div>
                    </a>

                    <!-- Reference Data Card -->
                    <a href="{{ url('/reference-data') }}" class="block">
                        <div
                            class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-green-500">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-green-600">Reference Data</h3>
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Lookup
                                    Tables</span>
                            </div>
                            <p class="text-gray-600 mt-2 text-sm">Manage lookup tables and reference data</p>
                            <div class="mt-4 flex justify-end">
                                <span class="text-green-600 text-sm">View Models →</span>
                            </div>
                        </div>
                    </a>

                    <!-- Legacy Item Model Card -->
                    <a href="{{ url('/items') }}" class="block">
                        <div
                            class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-gray-500">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-600">Items (Legacy)</h3>
                                <span
                                    class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">Legacy</span>
                            </div>
                            <p class="text-gray-600 mt-2 text-sm">Original item visualizer from base application</p>
                            <div class="mt-4 flex justify-end">
                                <span class="text-gray-600 text-sm">View Model →</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="mt-8 bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Database Schema</h2>
                <p class="text-gray-600 mb-4">
                    This application provides a comprehensive vehicle sales management system with the following key
                    entities:
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border rounded-lg p-4">
                        <h3 class="font-medium text-lg mb-2">Core Entities</h3>
                        <ul class="list-disc pl-5 text-gray-600 space-y-1">
                            <li>Vehicles - Complete vehicle inventory with details</li>
                            <li>Buyers - Customer information for vehicle purchasers</li>
                            <li>Sellers - Information about vehicle sellers</li>
                            <li>Employees - Staff members managing sales</li>
                            <li>Purchase Contracts - Legal agreements for vehicle sales</li>
                            <li>Vehicle Registrations - Official registration documents</li>
                        </ul>
                    </div>

                    <div class="border rounded-lg p-4">
                        <h3 class="font-medium text-lg mb-2">Supporting Entities</h3>
                        <ul class="list-disc pl-5 text-gray-600 space-y-1">
                            <li>Vehicle Specifications - Engine details, tires, etc.</li>
                            <li>Inspection Records - Vehicle inspection history</li>
                            <li>Damage Records - Documentation of vehicle damage</li>
                            <li>Additional Equipment - Extra features and accessories</li>
                            <li>Sales Logs - History of vehicle sales activities</li>
                            <li>Reference Data - Types, categories, and classifications</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
