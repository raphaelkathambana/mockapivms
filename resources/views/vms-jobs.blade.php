<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VMS Jobs Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100" x-data="{ selectedJob: null }">
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">VMS Jobs Dashboard</h1>
            <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800">← Back to Home</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Panel - Job Buttons -->
            <div class="space-y-4">
                <button @click="selectedJob = 'procurement'"
                    class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200 flex items-center justify-between">
                    <span>Vehicle Procurement</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <button @click="selectedJob = 'sale'"
                    class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-700 transition-colors duration-200 flex items-center justify-between">
                    <span>Vehicle Sale</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Right Panel - Job Details -->
            <div class="md:col-span-2 bg-white rounded-lg shadow-md p-6">
                <!-- No Selection State -->
                <div x-show="!selectedJob" class="h-full flex items-center justify-center">
                    <div class="text-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 mb-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <h3 class="text-lg font-medium mb-2">Select a Job</h3>
                        <p>Choose a job from the left panel to view its details and start the process</p>
                    </div>
                </div>

                <!-- Procurement Job -->
                <div x-show="selectedJob === 'procurement'" x-cloak>
                    <h2 class="text-xl font-semibold mb-4">Vehicle Procurement Process</h2>
                    <div class="space-y-4">
                        <p class="text-gray-600">Follow these steps to procure a new vehicle:</p>
                        <ol class="list-decimal pl-5 space-y-2">
                            <li>Contact seller and verify vehicle details</li>
                            <li>Perform vehicle inspection</li>
                            <li>Negotiate purchase price</li>
                            <li>Complete purchase documentation</li>
                            <li>Add vehicle to inventory</li>
                        </ol>
                        <div class="mt-6">
                            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Start Procurement Process
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sale Job -->
                <div x-show="selectedJob === 'sale'" x-cloak>
                    <h2 class="text-xl font-semibold mb-4">Vehicle Sale Process</h2>
                    <div class="space-y-4">
                        <p class="text-gray-600">Follow these steps to complete a vehicle sale:</p>
                        <ol class="list-decimal pl-5 space-y-2">
                            <li>Verify buyer information</li>
                            <li>Select vehicle from inventory</li>
                            <li>Prepare sales documentation</li>
                            <li>Process payment</li>
                            <li>Transfer vehicle ownership</li>
                            <li>Update inventory status</li>
                        </ol>
                        <div class="mt-6">
                            <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Start Sale Process
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
