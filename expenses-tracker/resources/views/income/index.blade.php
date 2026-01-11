

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Income') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-6">
                    Manage Income
                </h3>
                <div class="flex flex-wrap gap-4 mb-8" style="margin-bottom: 10px;">
                    <!-- Add Income Button -->
                    <button onclick="openIncomeModal()"
                        class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 transform active:scale-[0.98] transition-all duration-200 flex items-center gap-3 tracking-wide">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add Income
                    </button>
                    <!-- Manage Categories Button -->
                    <button onclick="openIncomeCategoryModal()"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transform active:scale-[0.98] transition-all duration-200 flex items-center gap-3 tracking-wide">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Manage Categories
                    </button>
                </div>

                <!-- Insights Dashboard -->
                @if(isset($insights) && count($insights) > 0)
                <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($insights as $insight)
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg border-l-4 
                            {{ $insight['type'] == 'warning' ? 'border-yellow-500' : '' }}
                            {{ $insight['type'] == 'success' ? 'border-green-500' : '' }}
                            {{ $insight['type'] == 'danger' ? 'border-red-500' : '' }}
                            {{ $insight['type'] == 'info' ? 'border-blue-500' : '' }}
                        " style="color:white">
                            <div class="flex items-center">
                                <div class="p-2 rounded-full mr-3
                                    {{ $insight['type'] == 'warning' ? 'bg-yellow-100 text-yellow-600' : '' }}
                                    {{ $insight['type'] == 'success' ? 'bg-green-100 text-green-600' : '' }}
                                    {{ $insight['type'] == 'danger' ? 'bg-red-100 text-red-600' : '' }}
                                    {{ $insight['type'] == 'info' ? 'bg-blue-100 text-blue-600' : '' }}
                                ">
                                    @if($insight['type'] == 'warning') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg> @endif
                                    @if($insight['type'] == 'success') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg> @endif
                                    @if($insight['type'] == 'danger') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> @endif
                                    @if($insight['type'] == 'info') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg> @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $insight['message'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif

                <div class="mt-8 grid gap-8 lg:grid-cols-2">
                    <!-- LEFT COLUMN: Summary + Pie Chart -->
                    <div class="space-y-6 order-1">
                        <!-- Total Income Summary -->
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-6 shadow-lg">
                            <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">
                                Total Income
                            </h4>
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                                Rs. {{ number_format($incomes->sum('amount'), 2) }}
                            </div>
                        </div>

                        <!-- Forecast Next Month -->
                        @if(isset($predictedIncome))
                        <div class="bg-gradient-to-br from-teal-500 to-green-600 rounded-xl p-6 shadow-lg text-white">
                            <h4 class="text-lg font-semibold mb-2 opacity-90">Forecast Next Month</h4>
                            <div class="text-3xl font-bold">
                                Rs. {{ number_format($predictedIncome, 2) }}
                            </div>
                            <p class="text-sm mt-2 opacity-80">
                                Based on your past income trends.
                                @if(isset($predictionConfidence))
                                    <br>
                                    <span class="font-semibold">Confidence: {{ number_format($predictionConfidence, 1) }}%</span>
                                @endif
                            </p>
                        </div>
                        @endif
                        <!-- Pie Chart -->
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-6 shadow-lg">
                            <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                                Income by Category
                            </h4>
                            <div class="relative">
                                <canvas id="incomePieChart" class="mx-auto"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- RIGHT COLUMN: Filters + Income Table -->
                    <div class="space-y-6 order-2">
                        <!-- Filter Controls -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5">
                            <!-- Flex container: column on small screens, row on large screens -->
                            <div class="flex flex-col lg:flex-row gap-4">
                                
                                <!-- Category Filter -->
                                <div class="w-full lg:w-auto lg:flex-1">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Category</label>
                                    <select id="filter_category_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" style="color: antiquewhite;">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- From Date -->
                                <div class="w-full lg:w-auto lg:flex-1">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">From</label>
                                    <input type="date" id="filter_from" value="{{ request('from', $from) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" style="color: antiquewhite;">
                                </div>
                                
                                <!-- To Date -->
                                <div class="w-full lg:w-auto lg:flex-1">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">To</label>
                                    <input type="date" id="filter_to" value="{{ request('to', $to) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" style="color: antiquewhite;">
                                </div>
                                
                                <!-- Filter Button -->
                                <div class="w-full lg:w-auto lg:pt-6">
                                    <button id="filterButton" class="w-full lg:w-auto px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition whitespace-nowrap h-[42px]" style="background-color:#4f46e5; margin-top:25px">
                                        Apply Filter
                                    </button>
                                </div>
                                
                            </div>
                        </div>
                        <!-- Income Table (Desktop) -->
                        <div class="hidden md:block bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" style="width: 100%;">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="incomeTable" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @forelse($incomes as $income)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ isset($anomalies[$income->id]) ? 'bg-yellow-50 dark:bg-yellow-900/20' : '' }}">
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                                        {{ $income->category->name ?? '' }}
                                                    </span>
                                                    @if(isset($anomalies[$income->id]))
                                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800" title="{{ $anomalies[$income->id]['message'] }}">
                                                            Unusual
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-4 text-sm font-semibold text-green-600 dark:text-green-400" style="text-align: right;">
                                                    Rs. {{ number_format($income->amount, 2) }}
                                                </td>
                                                <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-200">{{ $income->description }}</td>
                                                <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $income->date }}</td>
                                                <td class="px-4 py-4 text-sm">
                                                    <div class="flex items-center gap-2">
                                                        <button onclick='editIncome(@json($income))' title="Edit" class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" style="color: antiquewhite;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                            </svg>
                                                        </button>
                                                        <button onclick="deleteIncome({{ $income->id }})" title="Delete" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                                    No income recorded for this period.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Mobile Card View (Income) -->
                        <div class="md:hidden space-y-4">
                            @forelse($incomes as $income)
                                <div class="p-4 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 dark:bg-gray-800 {{ isset($anomalies[$income->id]) ? 'bg-yellow-50 dark:bg-yellow-900/20' : 'bg-white' }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 mb-1">
                                                {{ $income->category->name ?? 'Uncategorized' }}
                                            </span>
                                            @if(isset($anomalies[$income->id]))
                                                <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    ! Unusual
                                                </span>
                                            @endif
                                            @if($income->description)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 italic mt-1">{{ $income->description }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-green-600 dark:text-green-400">Rs. {{ number_format($income->amount, 2) }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $income->date }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end gap-3 mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                        <button onclick='editIncome(@json($income))' class="text-sm font-medium text-blue-600 dark:text-blue-400 flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                            Edit
                                        </button>
                                        <button onclick="deleteIncome({{ $income->id }})" class="text-sm font-medium text-red-600 dark:text-red-400 flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-500 bg-white dark:bg-gray-800 rounded-xl shadow">
                                    No income records found.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('income.modal')

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // ---------- OPEN INCOME MODAL ----------
    function openIncomeModal() {
        const modal = document.getElementById("incomeModal");
        const box = document.getElementById("incomeModalBox");
        // Set default date to today if adding new income
        if (!document.getElementById("income_id").value) {
            const today = new Date();
            document.getElementById("income_date").value = today.toISOString().slice(0, 10);
        }
        modal.classList.remove("hidden");
        setTimeout(() => {
            modal.classList.remove("opacity-0");
            box.classList.add("scale-100");
        }, 10);
    }
    // ---------- CLOSE INCOME MODAL ----------
    function closeIncomeModal() {
        const modal = document.getElementById("incomeModal");
        const box = document.getElementById("incomeModalBox");
        modal.classList.add("opacity-0");
        box.classList.remove("scale-100");
        setTimeout(() => {
            modal.classList.add("hidden");
        }, 250);
    }
    // ------------------ EDIT INCOME ------------------
   function editIncome(income) {
    document.getElementById("incomeForm").reset();

    document.getElementById("income_id").value = income.id;
    document.getElementById("income_category_id").value = income.category_id;
    document.getElementById("income_amount").value = income.amount;
    document.getElementById("income_description").value = income.description ?? '';
    document.getElementById("income_date").value = income.date;

    document.getElementById("submitIncomeButton").innerText = "Update Income";
    document.getElementById("incomeModalTitle").innerText = "Update Income";

    const modal = document.getElementById("incomeModal");
    const box = document.getElementById("incomeModalBox");

    modal.classList.remove("hidden", "opacity-0");
    box.classList.remove("scale-95");
    box.classList.add("scale-100");
}


    // ------------------ DELETE INCOME ------------------
    function deleteIncome(id) {
        if (!confirm("Are you sure you want to delete this income?")) return;
        fetch("/income/delete/" + id, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            location.reload();
        });
    }
    // ---------- HANDLE INCOME SAVE / UPDATE ----------
    document.getElementById("incomeForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const id = document.getElementById("income_id").value;
        const url = id ? "/income/update/" + id : "/income/store";
        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                category_id: document.getElementById("income_category_id").value,
                amount: document.getElementById("income_amount").value,
                description: document.getElementById("income_description").value,
                date: document.getElementById("income_date").value
            })
        })
        .then(res => res.json())
        .then(data => {
            location.reload();
        });
    });
    // ---------- FILTER INCOME ----------
    document.getElementById('filterButton').addEventListener('click', function(e) {
        e.preventDefault();
        const category_id = document.getElementById('filter_category_id').value;
        const from = document.getElementById('filter_from').value;
        const to = document.getElementById('filter_to').value;
        let url = `/income?`;
        if(category_id) url += `category_id=${category_id}&`;
        if(from) url += `from=${from}&`;
        if(to) url += `to=${to}`;
        window.location = url;
    });
    // ---------- PIE CHART ----------
    document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('incomePieChart');
    if (!canvas) return;

    const rows = document.querySelectorAll('#incomeTable tr');
    const categoryTotals = {};

    rows.forEach(row => {
        if (row.children.length < 2) return;

        const category = row.children[0].innerText.trim();
        const amount = parseFloat(
            row.children[1].innerText.replace('Rs.', '').replace(/,/g, '')
        ) || 0;

        if (category) {
            categoryTotals[category] = (categoryTotals[category] || 0) + amount;
        }
    });

    const ctx = canvas.getContext('2d');

    // 🔴 SAFE DESTROY
    if (window.incomePieChart instanceof Chart) {
        window.incomePieChart.destroy();
    }

    // ✅ CREATE CHART
    window.incomePieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: Object.keys(categoryTotals),
            datasets: [{
                data: Object.values(categoryTotals),
                backgroundColor: [
                    '#6366F1', '#818CF8', '#A5B4FC', '#F59E42',
                    '#F472B6', '#34D399', '#F87171', '#FBBF24',
                    '#60A5FA', '#FCD34D'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});

    </script>
</x-app-layout>

