<!-- EXPENSE MODAL -->
<div id="expenseModal"
     class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50
            opacity-0 transition-opacity duration-300">

    <div id="expenseModalBox"
         class="bg-white dark:bg-gray-800 w-full max-w-lg sm:max-w-2xl p-0 rounded-2xl shadow-2xl transform scale-95 transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700">

        <!-- Header -->
        <div class="px-6 py-5 bg-gradient-to-r from-green-600 to-teal-600 dark:from-green-700 dark:to-teal-700 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white tracking-wide">
                <span id="expenseModalTitle">Add Expense</span>
            </h2>
            <button onclick="closeExpenseModal()" style="color: antiquewhite;" class="text-white/80 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full p-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <div class="p-6">
            <form id="expenseForm" class="space-y-5">
                @csrf
                <input type="hidden" id="expense_id">

                <!-- Category -->
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Category</label>
                    <div class="relative">
                        <select id="expense_category_id" required style="color: antiquewhite;"
                            class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all outline-none appearance-none cursor-pointer">
                            <option value="">Select Category</option>
                            @foreach(auth()->user()->categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Title & Amount Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Title -->
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Title</label>
                        <input id="expense_title" required placeholder="What was it?" style="color: antiquewhite;"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all outline-none placeholder-gray-500 dark:placeholder-gray-400">
                    </div>

                    <!-- Amount -->
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Amount</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium"></span>
                            <input id="expense_amount" type="number" step="0.01" required placeholder="0.00" style="color: antiquewhite;"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all outline-none placeholder-gray-500 dark:placeholder-gray-400">
                        </div>
                    </div>
                </div>

                <!-- Date -->
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Date</label>
                    <input id="expense_date" type="date" required style="color: antiquewhite;"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all outline-none cursor-pointer">
                </div>

                <!-- Notes -->
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Notes (Optional)</label>
                    <textarea id="expense_note" rows="3" placeholder="Add any details..." style="color: antiquewhite;"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all outline-none placeholder-gray-500 dark:placeholder-gray-400 resize-none"></textarea>
                </div>

                <div class="pt-2">
                    <button id="expenseSubmitBtn" style="margin-top: 15px; margin-bottom: 15px; padding-top: 5px;padding-bottom: 5px;background-color: #4F46E5;"
                        class="w-full py-3 bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 transform active:scale-[0.98] transition-all duration-200 tracking-wide">
                        Save Expense
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
