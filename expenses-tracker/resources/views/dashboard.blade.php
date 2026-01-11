<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- AI Insights Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Expense Forecast -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">AI Expense Forecast</h4>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-200">
                        Rs. {{ number_format($expenseForecast['prediction'] ?? 0, 0) }}
                    </p>
                    <div class="mt-2 flex items-center space-x-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                            Accuracy: {{ $expenseForecast['accuracy'] ?? 'N/A' }}%
                        </span>
                        <span class="text-[10px] text-gray-400">Next Month</span>
                    </div>
                </div>

                <!-- Income Forecast -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">AI Income Forecast</h4>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-200">
                        Rs. {{ number_format($incomeForecast['prediction'] ?? 0, 0) }}
                    </p>
                    <div class="mt-2 flex items-center space-x-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                            Accuracy: {{ $incomeForecast['accuracy'] ?? 'N/A' }}%
                        </span>
                         <span class="text-[10px] text-gray-400">Next Month</span>
                    </div>
                </div>

                <!-- Anomaly Detection -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Anomaly Monitor</h4>
                     <p class="text-2xl font-black {{ $totalAnomalies > 0 ? 'text-red-500' : 'text-green-500' }}">
                        {{ $totalAnomalies }} Detected
                    </p>
                    <div class="mt-2 text-[10px] text-gray-400">
                        Unusual transactions this month
                    </div>
                </div>
            </div>

            <!-- KPI Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Income Card -->
                <div class="bg-gradient-to-br from-green-500 to-teal-600 rounded-xl p-6 shadow-lg text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold uppercase opacity-80 mb-1">Total Income (This Month)</p>
                            <h3 class="text-3xl font-bold">Rs. {{ number_format($currentMonthIncome, 0) }}</h3>
                        </div>
                        <div class="p-2 bg-white/20 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="{{ $incomeChange >= 0 ? 'bg-white/20 text-white' : 'bg-red-500/20 text-red-100' }} px-2 py-0.5 rounded font-bold mr-2">
                             {{ $incomeChange >= 0 ? '+' : '' }}{{ number_format($incomeChange, 1) }}%
                        </span>
                        <span class="opacity-80">vs last month</span>
                    </div>
                </div>

                <!-- Expense Card -->
                <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-xl p-6 shadow-lg text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold uppercase opacity-80 mb-1">Total Expenses (This Month)</p>
                            <h3 class="text-3xl font-bold">Rs. {{ number_format($currentMonthExpenses, 0) }}</h3>
                        </div>
                        <div class="p-2 bg-white/20 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="{{ $expenseChange <= 0 ? 'bg-white/20 text-white' : 'bg-red-900/20 text-red-100' }} px-2 py-0.5 rounded font-bold mr-2">
                             {{ $expenseChange > 0 ? '+' : '' }}{{ number_format($expenseChange, 1) }}%
                        </span>
                        <span class="opacity-80">vs last month</span>
                    </div>
                </div>

                <!-- Savings/Balance Card -->
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-6 shadow-lg text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold uppercase opacity-80 mb-1">Net Savings (This Month)</p>
                            <h3 class="text-3xl font-bold">Rs. {{ number_format($currentNetSavings, 0) }}</h3>
                        </div>
                        <div class="p-2 bg-white/20 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="opacity-80 mr-2">Savings Rate:</span>
                        <span class="font-bold bg-white/20 px-2 py-0.5 rounded">{{ number_format($savingsRate, 1) }}%</span>
                    </div>
                </div>

            </div>

             <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Trend Chart (Line) - Spans 2 columns -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">Financial Overview (Income vs Expense)</h4>
                    <div class="relative h-72">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <!-- Distribution Chart (Donut) - Spans 1 column -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                     <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">Expense Distribution</h4>
                    <div class="relative h-64">
                         <canvas id="distributionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Yearly Trend Section -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">Yearly Expense Trend</h4>
                <div class="relative h-80">
                    <canvas id="yearlyTrendChart"></canvas>
                </div>
            </div>

            <!-- Recent Transactions Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200">Recent Activity</h4>
                    <a href="{{ route('expenses.page') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">View All</a>
                </div>

                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recentTransactions as $tx)
                                <tr class="group hover:bg-gray-100 dark:hover:bg-white/5 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($tx['type'] == 'income')
                                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">Income</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">Expense</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">
                                        {{ $tx['category'] }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300">
                                        {{ $tx['description'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ Carbon\Carbon::parse($tx['date'])->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold {{ $tx['type'] == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $tx['type'] == 'income' ? '+' : '-' }} Rs. {{ number_format($tx['amount'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No recent transactions.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden">
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($recentTransactions as $tx)
                            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <div class="flex justify-between items-start mb-1">
                                    <div class="flex items-center gap-2">
                                        @if($tx['type'] == 'income')
                                            <div class="p-1.5 rounded-full bg-green-100 text-green-600">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            </div>
                                        @else
                                            <div class="p-1.5 rounded-full bg-red-100 text-red-600">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                            </div>
                                        @endif
                                        <h4 class="font-bold text-gray-800 dark:text-gray-200 text-sm">{{ $tx['category'] }}</h4>
                                    </div>
                                    <span class="font-bold text-sm {{ $tx['type'] == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $tx['type'] == 'income' ? '+' : '-' }} Rs. {{ number_format($tx['amount'], 0) }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center pl-8">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate pr-2">{{ $tx['description'] }}</p>
                                    <span class="text-xs text-gray-400 whitespace-nowrap">{{ Carbon\Carbon::parse($tx['date'])->format('M d') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-sm text-gray-500">No recent transactions.</div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Common Options
        Chart.defaults.font.family = "'Inter', sans-serif";
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#9ca3af' : '#4b5563';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';

        // 1. Income vs Expense Trend Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const expenseData = @json($monthlyExpenses);
        const incomeData = @json($monthlyIncome);

        // Merge months from both datasets to ensure complete x-axis
        const allMonths = [...new Set([
            ...expenseData.map(d => d.month),
            ...incomeData.map(d => d.month)
        ])].sort();

        // Map data to aligned months (fill 0 if missing)
        const alignedExpenses = allMonths.map(month => {
            const found = expenseData.find(d => d.month === month);
            return found ? found.total : 0;
        });

        const alignedIncome = allMonths.map(month => {
            const found = incomeData.find(d => d.month === month);
            return found ? found.total : 0;
        });

        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: allMonths.map(m => {
                     const date = new Date(m + '-01');
                     return date.toLocaleDateString('en-US', { month: 'short' });
                }),
                datasets: [
                    {
                        label: 'Income',
                        data: alignedIncome,
                        backgroundColor: '#10b981', // Green-500
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Expenses',
                        data: alignedExpenses,
                        backgroundColor: '#ef4444', // Red-500
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        position: 'top',
                        align: 'end',
                        labels: { color: textColor, usePointStyle: true, boxWidth: 8 }
                    },
                    tooltip: {
                         mode: 'index',
                         intersect: false,
                         callbacks: {
                             label: (ctx) => ' ' + ctx.dataset.label + ': Rs. ' + ctx.raw.toLocaleString()
                         }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { color: textColor }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                }
            }
        });

        // 2. Distribution Chart
        const distCtx = document.getElementById('distributionChart').getContext('2d');
        const distData = @json($categoryDistribution);
        
        // Colors pallette
        const colors = ['#3b82f6', '#8b5cf6', '#ec4899', '#f97316', '#10b981', '#06b6d4'];

        new Chart(distCtx, {
            type: 'doughnut',
            data: {
                labels: distData.map(d => d.name),
                datasets: [{
                    data: distData.map(d => d.total),
                    backgroundColor: colors,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'right',
                        labels: { color: textColor, boxWidth: 12, font: { size: 11 } }
                    },
                    tooltip: {
                         callbacks: {
                             label: (ctx) => ' Rs. ' + ctx.raw.toLocaleString()
                         }
                    }
                },
                cutout: '70%'
            }
        });

        // 3. Yearly Trend Chart
        const yearlyCtx = document.getElementById('yearlyTrendChart').getContext('2d');
        const yearlyData = @json($yearlyExpenses);

        new Chart(yearlyCtx, {
            type: 'line',
            data: {
                labels: yearlyData.map(d => {
                     const date = new Date(d.month + '-01');
                     return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                }),
                datasets: [{
                    label: 'Expenses',
                    data: yearlyData.map(d => d.total),
                    borderColor: '#3b82f6', // Blue-500
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                         mode: 'index',
                         intersect: false,
                         callbacks: {
                             label: (ctx) => 'Rs. ' + ctx.raw.toLocaleString()
                         }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { color: textColor }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor }
                    }
                }
            }
        });

    </script>
</x-app-layout>
