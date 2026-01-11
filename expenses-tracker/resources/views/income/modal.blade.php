<!-- INCOME MODAL -->
<div id="incomeModal"
     class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50 opacity-0 transition-opacity duration-300">
    <div id="incomeModalBox"
         class="bg-white dark:bg-gray-800 w-full max-w-lg sm:max-w-2xl p-0 rounded-2xl shadow-2xl transform scale-95 transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700">
        <!-- Header -->
        <div class="px-6 py-5 bg-gradient-to-r from-green-600 to-teal-600 dark:from-green-700 dark:to-teal-700 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white tracking-wide">
                <span id="incomeModalTitle">Add Income</span>
            </h2>
            <button onclick="closeIncomeModal()" class="text-white/80 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full p-1.5" style="color: antiquewhite;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        <div class="p-6">
            <form id="incomeForm" class="space-y-5">
                @csrf
                <input type="hidden" id="income_id">
                <!-- Category -->
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Category</label>
                    <div class="relative">
                        <select id="income_category_id" required class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all outline-none appearance-none cursor-pointer" style="color: antiquewhite;">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                <!-- Amount & Description Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Amount -->
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Amount</label>
                        <div class="relative">
                            <input id="income_amount" type="number" step="0.01" required placeholder="0.00" class="w-full pl-4 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all outline-none placeholder-gray-500 dark:placeholder-gray-400" style="color: antiquewhite;">
                        </div>
                    </div>
                    <!-- Description -->
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Description</label>
                        <input id="income_description" placeholder="e.g. Salary, Bonus" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all outline-none placeholder-gray-500 dark:placeholder-gray-400" style="color: antiquewhite;">
                    </div>
                </div>
                <!-- Date -->
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Date</label>
                    <input id="income_date" type="date" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all outline-none placeholder-gray-500 dark:placeholder-gray-400" style="color: antiquewhite;">
                </div>
                <button id="submitIncomeButton" style="margin-top: 15px; margin-bottom: 15px; padding-top: 5px;padding-bottom: 5px;background-color: #4F46E5;" class="w-full py-3 bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 transform active:scale-[0.98] transition-all duration-200 tracking-wide">Add Income</button>
            </form>
        </div>
    </div>
</div>
<!-- Modal for managing income categories (add, update, delete) -->
<div id="incomeCategoryModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50 opacity-0 transition-opacity duration-300">
    <div id="incomeCategoryModalBox" class="bg-white dark:bg-gray-800 w-full max-w-md sm:max-w-lg p-0 rounded-2xl shadow-2xl transform scale-95 transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="px-6 py-5 bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-700 dark:to-emerald-700 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white tracking-wide">Manage Income Categories</h2>
            <button onclick="closeIncomeCategoryModal()" class="text-white/80 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full p-1.5" style="color: antiquewhite;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        <div class="p-6">
            <div id="incomeCategoryAlert" class="hidden mb-4 p-3 rounded-lg text-sm font-medium shadow-sm flex items-center gap-2" style="color: white;"></div>
            <form id="incomeCategoryForm" class="space-y-4">
                @csrf
                <input type="hidden" name="id" id="income_category_modal_id">

                <div class="space-y-1">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Category Name</label>
                    <input id="income_category_name" name="name" required placeholder="e.g. Salary, Bonus" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all outline-none placeholder-gray-500 dark:placeholder-gray-400" style="color: antiquewhite;">
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Monthly Limit</label>
                    <input id="income_category_limit" name="limit_amount" type="number" step="0.01" placeholder="0.00" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all outline-none placeholder-gray-500 dark:placeholder-gray-400" style="color: antiquewhite;">
                </div>
                <button id="submitIncomeCategoryButton" style="margin-top: 15px; margin-bottom: 15px; padding-top: 5px;padding-bottom: 5px;background-color: #4F46E5;" class="w-full py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 transform active:scale-[0.98] transition-all duration-200 tracking-wide">Add Category</button>
            </form>
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="px-3 bg-white dark:bg-gray-800 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Existing Categories</span>
                </div>
            </div>
            <ul id="incomeCategoryList" class="space-y-2 overflow-y-auto pr-1 custom-scrollbar" style="max-height: 240px;"></ul>
        </div>
    </div>
</div>
<script>
function openIncomeCategoryModal() {
    const modal = document.getElementById('incomeCategoryModal');
    const box = document.getElementById('incomeCategoryModalBox');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        box.classList.remove('scale-95');
        box.classList.add('scale-100');
    }, 10);
    loadIncomeCategories();
}
function closeIncomeCategoryModal() {
    const modal = document.getElementById('incomeCategoryModal');
    const box = document.getElementById('incomeCategoryModalBox');
    modal.classList.add('opacity-0');
    box.classList.remove('scale-100');
    box.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
        location.reload();
    }, 250);
}
function loadIncomeCategories() {
    fetch('/income-categories')
        .then(res => res.json())
        .then(categories => {
            const list = document.getElementById('incomeCategoryList');
            list.innerHTML = '';
            categories.forEach(cat => {
                const li = document.createElement('li');
                li.className = 'group flex justify-between items-center p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-750 hover:border-green-300 dark:hover:border-green-700 hover:shadow-lg transition-all duration-200';
                li.innerHTML = `
                    <div class="flex items-center gap-3 flex-1">
                        <div class="p-2.5 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 dark:text-gray-100">${cat.name}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 font-medium mt-0.5">
                                Limit: <span class="text-green-600 dark:text-green-400 font-semibold">Rs. ${parseFloat(cat.limit_amount).toFixed(2)}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <button onclick='editIncomeCategory(${cat.id}, "${cat.name}", "${cat.limit_amount}")' 
                            class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" 
                            title="Edit Category" style="color: white;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                        </button>
                        <button onclick='deleteIncomeCategory(${cat.id})' 
                            class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" 
                            title="Delete Category">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                `;
                list.appendChild(li);
            });
        });
}
document.getElementById('incomeCategoryForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('income_category_modal_id').value;
    const name = document.getElementById('income_category_name').value;
    const limit_amount = document.getElementById('income_category_limit').value;

    const url = id
        ? `/income-categories/update/${id}`
        : '/income-categories/store';

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ name, limit_amount })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const successMessage = id 
                ? `Category "${name}" updated successfully!` 
                : `Category "${name}" added successfully!`;
            showIncomeCategoryAlert(successMessage, 'success');
            loadIncomeCategories();
            resetIncomeCategoryForm();
        } else {
            showIncomeCategoryAlert(data.message || 'An error occurred', 'error');
        }
    })
    .catch(error => {
        showIncomeCategoryAlert('Failed to save category. Please try again.', 'error');
    });
});

function editIncomeCategory(id, name, limit) {
    document.getElementById('income_category_modal_id').value = id;
    document.getElementById('income_category_name').value = name;
    document.getElementById('income_category_limit').value = limit;
    document.getElementById('submitIncomeCategoryButton').innerText = 'Update Category';
}

function resetIncomeCategoryForm() {
    document.getElementById('income_category_modal_id').value = '';
    document.getElementById('income_category_name').value = '';
    document.getElementById('income_category_limit').value = '';
    document.getElementById('submitIncomeCategoryButton').innerText = 'Add Category';
}

function deleteIncomeCategory(id) {
    if(!confirm('Are you sure you want to delete this category?')) return;
    fetch(`/income-categories/delete/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            showIncomeCategoryAlert('Category deleted successfully!', 'success');
            loadIncomeCategories();
        } else {
            showIncomeCategoryAlert(data.message || 'Failed to delete category', 'error');
        }
    })
    .catch(error => {
        showIncomeCategoryAlert('Failed to delete category. Please try again.', 'error');
    });
}
function showIncomeCategoryAlert(message, type='success') {
    const alert = document.getElementById('incomeCategoryAlert');
    alert.innerHTML = type === 'success'
        ? `<svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg><span class='text-green-800 dark:text-green-200'>${message}</span>`
        : `<svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.586 8.707 7.293z" clip-rule="evenodd"></path></svg><span class='text-red-800 dark:text-red-200'>${message}</span>`;
    alert.className = `mb-4 p-3 rounded-lg text-sm font-medium shadow-sm flex items-center gap-2 ${type==='success'?'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800':'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800'}`;
    alert.classList.remove('hidden');
    setTimeout(() => alert.classList.add('hidden'), 3000);
}
</script>