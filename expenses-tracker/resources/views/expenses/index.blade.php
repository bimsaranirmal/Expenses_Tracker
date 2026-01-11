<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Expenses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-6">
                    Manage Expenses
                </h3>

                <div class="flex flex-wrap gap-4 mb-8" style="margin-bottom: 10px;">

                    <!-- Add Expense Button -->
                    <button onclick="openExpenseModal()" 
                        class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 transform active:scale-[0.98] transition-all duration-200 flex items-center gap-3 tracking-wide">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add Expense
                    </button>

                    <!-- Manage Categories Button -->
                    <button onclick="openCategoryModal()" 
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transform active:scale-[0.98] transition-all duration-200 flex items-center gap-3 tracking-wide">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Manage Categories
                    </button>

                    <!-- Export as PDF Button -->
                    <a href="{{ route('expenses.exportPdf', ['from' => $from, 'to' => $to]) }}" target="_blank"
                        class="px-6 py-2.5 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white font-bold rounded-xl shadow-lg shadow-red-500/30 transform active:scale-[0.98] transition-all duration-200 flex items-center gap-3 tracking-wide">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Export PDF
                    </a>

                </div>

    <!-- Insights Dashboard -->
    @if(isset($insights) && count($insights) > 0)
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Financial Insights</h3>
        <button onclick="sendReport()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
            </svg>
            Send Report
        </button>
    </div>
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

    <!-- EXPENSES + SUMMARY + PIE CHART (Fully Responsive) -->
<div class="mt-8 grid gap-8 lg:grid-cols-2">

    <!-- LEFT COLUMN: Summary + Pie Chart -->
    <div class="space-y-6 order-1">
        <!-- Total Expenses Summary -->
        <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-6 shadow-lg">
            <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">
                Total Expenses
            </h4>
            <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                Rs. {{ number_format($expenses->sum('amount'), 2) }}
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $from }} → {{ $to }}
            </p>
        </div>

        <!-- Predicted Expenses Card -->
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-6 shadow-lg text-white relative overflow-hidden group">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            
            <h4 class="text-lg font-semibold text-indigo-100 mb-2 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 9.586 14.586 6H12z" clip-rule="evenodd" />
                </svg>
                Forecast Next Month
            </h4>
            <div class="text-3xl font-bold">
                Rs. {{ number_format($predictedExpense, 2) }}
            </div>
            <p class="text-sm text-indigo-200 mt-1 flex justify-between items-center">
                <span>Based on your last 6 months trend</span>
                <span class="bg-white/20 px-2 py-0.5 rounded text-xs font-medium" title="R-Squared Accuracy">
                    Confidence: {{ number_format($predictionConfidence, 0) }}%
                </span>
            </p>
        </div>

        <!-- Pie Chart -->
        <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-6 shadow-lg">
            <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                Expenses by Category
            </h4>
            <div class="relative">
                <canvas id="categoryPieChart" class="mx-auto"></canvas>
            </div>

            <!-- Optional: Hidden detailed table under chart (uncomment if needed) -->
            <!-- <div class="mt-6 overflow-x-auto">
                <table class="w-full text-sm">...</table>
            </div> -->
        </div>
    </div>

    <!-- RIGHT COLUMN: Filters + Expenses Table -->
    <div class="space-y-6 order-2">

        <!-- Filter Controls -->
       <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5" style="margin-left:20px;margin-right:20px">
    <!-- Flex container: column on small screens, row on large screens -->
    <div class="flex flex-col lg:flex-row gap-4">

        <!-- Quick Filter -->
        <div class="w-full lg:w-auto lg:flex-1">
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                Quick Filter
            </label>
            <select id="quickFilter" onchange="applyQuickFilter()" style="color: antiquewhite;"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                       bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                <option value="this_month">This Month</option>
                <option value="last_month">Last Month</option>
                <option value="custom">Custom Range</option>
            </select>
        </div>

        <!-- Category Filter -->
        <div class="w-full lg:w-auto lg:flex-1">
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                Category
            </label>
            <select id="categoryFilter" style="color: antiquewhite;"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                       bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ isset($categoryId) && $categoryId == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- From Date -->
        <div class="w-full lg:w-auto lg:flex-1">
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">From</label>
            <input type="date" id="fromDate" value="{{ $from }}" style="color: antiquewhite;"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                       bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
        </div>

        <!-- To Date -->
        <div class="w-full lg:w-auto lg:flex-1">
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">To</label>
            <input type="date" id="toDate" value="{{ $to }}" style="color: antiquewhite;"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                       bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
        </div>

        <!-- Filter Button -->
        <div class="w-full lg:w-auto lg:pt-6">
            <button onclick="filterExpenses()" style="background-color:#4f46e5; margin-top:25px"
                class="w-full lg:w-auto px-6 py-2 bg-indigo-600 hover:bg-indigo-700 
                       text-white font-medium rounded-lg transition whitespace-nowrap h-[42px]">
                Apply Filter
            </button>
        </div>

    </div>
</div>


        <!-- Expenses Table (Desktop) -->
        <div class="hidden md:block bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" style="width: 100%;">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="expenseTable" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($expenses as $exp)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ isset($anomalies[$exp->id]) ? 'bg-red-100 dark:bg-red-900/50 text-red-900 dark:text-red-100' : '' }}">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                        {{ $exp->category->name }}
                                    </span>
                                    @if(isset($anomalies[$exp->id]))
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800" title="{{ $anomalies[$exp->id]['message'] }}">
                                            Abnormal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-200">{{ $exp->title }}</td>
                                <td class="px-4 py-4 text-sm font-semibold text-green-600 dark:text-green-400" style="text-align: right;">
                                    Rs. {{ number_format($exp->amount, 2) }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $exp->date }}</td>
                                <td class="px-4 py-4 text-sm" style="item-align: center;">
                                    <div class="flex items-center gap-2">
                                        <button onclick='editExpense(@json($exp))' title="Edit" style="color: antiquewhite;"
                                            class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>
                                        <button onclick="deleteExpense({{ $exp->id }})" title="Delete"
                                            class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors">
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
                                    No expenses recorded for this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            @forelse($expenses as $exp)
                <div class="p-4 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 dark:bg-gray-800 {{ isset($anomalies[$exp->id]) ? 'bg-red-100 dark:bg-red-900/50' : 'bg-white' }}">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 mb-1">
                                {{ $exp->category->name }}
                            </span>
                             @if(isset($anomalies[$exp->id]))
                                <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-200 text-red-800">
                                    ! Unusual
                                </span>
                            @endif
                            <h4 class="font-bold text-gray-800 dark:text-gray-100 {{ isset($anomalies[$exp->id]) ? 'text-red-900 dark:text-red-100' : '' }}">{{ $exp->title }}</h4>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-green-600 dark:text-green-400">Rs. {{ number_format($exp->amount, 2) }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $exp->date }}</div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <button onclick='editExpense(@json($exp))' class="text-sm font-medium text-blue-600 dark:text-blue-400 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit
                        </button>
                        <button onclick="deleteExpense({{ $exp->id }})" class="text-sm font-medium text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 bg-white dark:bg-gray-800 rounded-xl shadow">
                    No expenses found.
                </div>
            @endforelse
        </div>
    </div>
</div>



            </div>

        </div>
    </div>

   @include('categories.modal')
@include('expenses.modal')

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ---------- OPEN EXPENSE MODAL ----------
function openExpenseModal() {
    const modal = document.getElementById("expenseModal");
    const box = document.getElementById("expenseModalBox");

    // Set default date to today if adding new expense
    if (!document.getElementById("expense_id").value) {
        const today = new Date();
        document.getElementById("expense_date").value = formatDateLocal(today);
    }

    modal.classList.remove("hidden");
    setTimeout(() => {
        modal.classList.remove("opacity-0");
        box.classList.add("scale-100");
    }, 10);
}

// ---------- CLOSE EXPENSE MODAL ----------
function closeExpenseModal() {
    const modal = document.getElementById("expenseModal");
    const box = document.getElementById("expenseModalBox");

    modal.classList.add("opacity-0");
    box.classList.remove("scale-100");

    setTimeout(() => {
        modal.classList.add("hidden");
    }, 250);
}


// ------------------ EDIT EXPENSE ------------------
function editExpense(exp) {
    document.getElementById("expense_id").value = exp.id;
    document.getElementById("expense_category_id").value = exp.category_id;
    document.getElementById("expense_title").value = exp.title;
    document.getElementById("expense_amount").value = exp.amount;
    document.getElementById("expense_date").value = exp.date;
    document.getElementById("expense_note").value = exp.note;

    document.getElementById("expenseSubmitBtn").innerText = "Update Expense";

    openExpenseModal();
}



// ------------------ DELETE EXPENSE ------------------
function deleteExpense(id) {
    if (!confirm("Are you sure you want to delete this expense?")) return;

    fetch("/expenses/delete/" + id, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message, "success");
        closeExpenseModal();
        setTimeout(() => location.reload(), 700);
    });
}
// ---------- HANDLE EXPENSE SAVE / UPDATE ----------
document.getElementById("expenseForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const id = document.getElementById("expense_id").value;

    const url = id ? "/expenses/update" : "/expenses/store";

    fetch(url, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            id: id,
            category_id: document.getElementById("expense_category_id").value,
            title: document.getElementById("expense_title").value,
            amount: document.getElementById("expense_amount").value,
            date: document.getElementById("expense_date").value,
            note: document.getElementById("expense_note").value
        })
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message, "success");
        closeExpenseModal();
        setTimeout(() => {
            location.reload();
        }, 700);
    });
});

window.onload = function () {
    // PIE CHART RENDER
    const chartData = @json($chartData);
    const categories = @json($categories);
    const pieLabels = chartData.map(c => {
        const cat = categories.find(k => k.id === c.category_id);
        return cat ? cat.name : 'N/A';
    });
    const pieData = chartData.map(c => c.total);
    const pieBg = [
        '#4ade80', '#60a5fa', '#fbbf24', '#f87171', '#a78bfa', '#34d399', '#f472b6', '#facc15', '#38bdf8', '#818cf8'
    ];
    if (pieData.length > 0) {
        const ctx = document.getElementById('categoryPieChart').getContext('2d');
        const pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieData,
                    backgroundColor: pieBg.slice(0, pieData.length),
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const idx = context.dataIndex;
                                const cat = categories.find(k => k.id === chartData[idx].category_id);
                                const limit = cat ? cat.limit_amount : 0;
                                return `${context.label}: Rs. ${context.parsed} (Limit: Rs. ${limit})`;
                            }
                        }
                    }
                },
                onClick: function(evt, elements) {
                    if (elements.length > 0) {
                        const idx = elements[0].index;
                        const categoryId = chartData[idx].category_id;
                        loadCategoryExpenses(categoryId);
                    }
                }
            }
        });
    }

    // Set default "This Month" only if from/to not in URL
    let from = document.getElementById("fromDate").value;
    let to = document.getElementById("toDate").value;
    const quickFilter = document.getElementById("quickFilter");
    const now = new Date();
    // If Laravel passed empty values → apply auto defaults
    if (!from || !to) {
        const first = new Date(now.getFullYear(), now.getMonth(), 1);
        const last = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        document.getElementById("fromDate").value = formatDateLocal(first);
        document.getElementById("toDate").value = formatDateLocal(last);
        quickFilter.value = "this_month";
    } else {
        // Detect which filter matches the current range
        const firstThisMonth = formatDateLocal(new Date(now.getFullYear(), now.getMonth(), 1));
        const lastThisMonth = formatDateLocal(new Date(now.getFullYear(), now.getMonth() + 1, 0));
        const firstLastMonth = formatDateLocal(new Date(now.getFullYear(), now.getMonth() - 1, 1));
        const lastLastMonth = formatDateLocal(new Date(now.getFullYear(), now.getMonth(), 0));

        if (from === firstThisMonth && to === lastThisMonth) {
            quickFilter.value = "this_month";
        } else if (from === firstLastMonth && to === lastLastMonth) {
            quickFilter.value = "last_month";
        } else {
            quickFilter.value = "custom";
        }
    }

};


// --- QUICK FILTER HANDLER ---
function applyQuickFilter() {
    const filter = document.getElementById("quickFilter").value;
    const now = new Date();

    // Remove old URL parameters first
    const baseUrl = window.location.origin + "/expenses";

    history.replaceState({}, "", baseUrl);

    if (filter === "this_month") {
        const first = new Date(now.getFullYear(), now.getMonth(), 1);
        const last = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        document.getElementById("fromDate").value = formatDateLocal(first);
        document.getElementById("toDate").value = formatDateLocal(last);
    }

    if (filter === "last_month") {
        const first = new Date(now.getFullYear(), now.getMonth() - 1, 1);
        const last = new Date(now.getFullYear(), now.getMonth(), 0);
        document.getElementById("fromDate").value = formatDateLocal(first);
        document.getElementById("toDate").value = formatDateLocal(last);
    }

}

function formatDateLocal(date) {
    let y = date.getFullYear();
    let m = String(date.getMonth() + 1).padStart(2, '0');
    let d = String(date.getDate()).padStart(2, '0');
    return `${y}-${m}-${d}`;
}

// Load expenses for a specific category and update the table
function loadCategoryExpenses(categoryId) {
    fetch(`/expenses/category/${categoryId}`)
        .then(res => res.json())
        .then(expenses => {
            const expenseTable = document.getElementById('expenseTable');
            expenseTable.innerHTML = '';
            if (expenses.length === 0) {
                expenseTable.innerHTML = `<tr><td colspan='5' class='text-center py-6 text-gray-500 dark:text-gray-400'>No expenses recorded for this category.</td></tr>`;
                return;
            }
            expenses.forEach(exp => {
                expenseTable.innerHTML += `
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                        <td class="p-3">
                            <span class="px-2 py-1 rounded bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-sm">
                                ${exp.category ? exp.category.name : ''}
                            </span>
                        </td>
                        <td class="p-3 text-gray-700 dark:text-gray-300">${exp.title}</td>
                        <td class="p-3 font-semibold text-green-600 dark:text-green-400">Rs. ${parseFloat(exp.amount).toFixed(2)}</td>
                        <td class="p-3 text-gray-600 dark:text-gray-300">${exp.date}</td>
                        <td class="p-3 flex gap-2">
                            <button onclick='editExpense(${JSON.stringify(exp)})' style="color: antiquewhite;" class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </button>
                            <button onclick="deleteExpense(${exp.id})" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                `;
            });
        });
}




// --- FILTER REQUEST ---
function filterExpenses() {
    let from = document.getElementById("fromDate").value;
    let to = document.getElementById("toDate").value;
    let categoryId = document.getElementById("categoryFilter").value;

    window.location = `/expenses?from=${from}&to=${to}&category_id=${categoryId}`;
}

// --- SEND REPORT ---
function sendReport() {
    const btn = document.querySelector('button[onclick="sendReport()"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = 'Sending...';
    btn.disabled = true;

    fetch('/expenses/send-report', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message, "success");
        btn.innerHTML = originalText;
        btn.disabled = false;
    })
    .catch(err => {
        showToast("Failed to send report.", "error");
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

// --- SET DEFAULT DATE RANGE TO CURRENT MONTH ---

</script>


</x-app-layout>
