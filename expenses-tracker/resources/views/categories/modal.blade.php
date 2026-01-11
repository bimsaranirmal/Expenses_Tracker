<!-- Modal Background -->
<div id="categoryModal" 
     class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50
            opacity-0 transition-opacity duration-300">

    <!-- Modal Box -->
    <div id="categoryModalBox"
         class="bg-white dark:bg-gray-800 w-full max-w-md sm:max-w-lg p-0 rounded-2xl shadow-2xl transform scale-95 transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700">

        <!-- Header -->
        <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-700 dark:to-indigo-700 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white tracking-wide">
                Manage Categories
            </h2>
            <button onclick="closeCategoryModal()" style="color: antiquewhite;" class="text-white/80 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full p-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <div class="p-6">
            <!-- Alert / Success Message -->
            <div id="categoryAlert" style="color: antiquewhite;" padding="10px"
                 class="hidden mb-4 p-3 rounded-lg text-sm font-medium shadow-sm flex items-center gap-2"></div>

            <!-- Delete Confirmation -->
            <div id="deleteConfirm"
                 class="hidden mb-4 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 flex flex-col sm:flex-row justify-between items-center gap-3">
                 
                <span id="deleteMessage" style="color: antiquewhite;"class="text-red-700 dark:text-red-300 font-medium text-sm">Are you sure you want to delete?</span>

                <div class="flex gap-2">
                    <button id="confirmDelete"
                            class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-red-700 shadow-sm transition-colors">
                        Yes, Delete
                    </button>

                    <button onclick="hideDeleteConfirm()"
                            class="px-3 py-1.5 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-bold uppercase tracking-wider rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 shadow-sm transition-colors">
                        Cancel
                    </button>
                </div>
            </div>

            <!-- Add / Update Category Form -->
            <form id="categoryForm" class="space-y-4">
                @csrf
                <input type="hidden" name="id" id="category_id">

                <div class="grid grid-cols-1 gap-4">
                    <!-- Name -->
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Category Name</label>
                        <input id="category_name" name="name" required placeholder="e.g. Groceries, Rent" style="color: antiquewhite;"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none placeholder-gray-500 dark:placeholder-gray-400">
                    </div>

                    <!-- Limit -->
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Monthly Limit</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium"></span>
                            <input id="category_limit" name="limit_amount" type="number" step="0.01" placeholder="0.00" style="color: antiquewhite;"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none placeholder-gray-500 dark:placeholder-gray-400">
                        </div>
                    </div>
                </div>

                <button id="submitButton" style="margin-top: 15px; margin-bottom: 15px; padding-top: 5px;padding-bottom: 5px;background-color: #4F46E5;"
                        class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transform active:scale-[0.98] transition-all duration-200 tracking-wide">
                    Add Category
                </button>
            </form>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="px-3 bg-white dark:bg-gray-800 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Existing Categories
                    </span>
                </div>
            </div>

            <!-- Category List Header -->
            <div class="mb-3">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" id="categorySearch" placeholder="Search categories..."
       class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 
              bg-white dark:bg-gray-900 text-sm text-white focus:ring-2 
              focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none 
              placeholder-gray-500 dark:placeholder-gray-400"
       onkeyup="filterCategories()">

                </div>
            </div>

            <!-- Category List -->
            <ul id="categoryList"
                class="space-y-2 overflow-y-auto pr-1 custom-scrollbar"
                style="max-height: 240px;">
                
                @foreach(auth()->user()->categories as $cat)
                <li data-id="{{ $cat->id }}"
                    class="group flex justify-between items-center p-3 rounded-xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-blue-200 dark:hover:border-blue-800 hover:shadow-md transition-all duration-200">
                    
                    <div class="flex items-center gap-3">
                        <div style="color: antiquewhite;" class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-lg">
                            {{ substr($cat->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-bold text-gray-800 dark:text-gray-100 category-name text-sm">
                                {{ $cat->name }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                                Limit: <span class="text-gray-700 dark:text-gray-300">Rs. {{ number_format($cat->limit_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-1 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-200">
                        <button onclick="editCategory({{ $cat->id }}, '{{ $cat->name }}', '{{ $cat->limit_amount }}')" style="color: antiquewhite;"
                                class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                        </button>
                        <button onclick="showDeleteConfirm({{ $cat->id }})"
                                class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>


<script>
let deleteId = null;

/* ---------------------- Delete Confirmation ------------------------ */
function showDeleteConfirm(id) {
    deleteId = id;
    document.getElementById('deleteConfirm').classList.remove('hidden');
    // Scroll to top to see confirmation
    document.getElementById('categoryModalBox').scrollTop = 0;
}

function hideDeleteConfirm() {
    deleteId = null;
    document.getElementById('deleteConfirm').classList.add('hidden');
}

document.getElementById('confirmDelete').addEventListener('click', function() {
    if(!deleteId) return;
    fetch(`/categories/delete/${deleteId}`, {
        method: 'DELETE',
        headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(res => res.json())
    .then(data => {
        document.querySelector(`#categoryList li[data-id='${deleteId}']`)?.remove();
        showCategoryAlert(data.message, 'success');
        hideDeleteConfirm();
        refreshExpenseCategoryDropdown();
    });
});

/* ---------------------- Modal Alert ------------------------ */
function showCategoryAlert(message, type='success') {
    const alert = document.getElementById('categoryAlert');
    alert.innerHTML = type === 'success' 
        ? `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <span class="text-green-700 dark:text-green-300">${message}</span>`
        : `<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> <span class="text-red-700 dark:text-red-300">${message}</span>`;
    
    alert.className = `mb-4 p-3 rounded-lg text-sm font-medium shadow-sm flex items-center gap-2 ${type==='success'?'bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800':'bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800'}`;
    alert.classList.remove('hidden');
    setTimeout(() => alert.classList.add('hidden'), 3000);
}

/* ---------------------- Existing Code (Modal Animations, Edit, Add/Update, Search) ------------------------ */
function openCategoryModal() {
    const modal = document.getElementById('categoryModal');
    const box = document.getElementById('categoryModalBox');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        box.classList.remove('scale-95');
        box.classList.add('scale-100');
    }, 10);
}
function closeCategoryModal() {
    const modal = document.getElementById('categoryModal');
    const box = document.getElementById('categoryModalBox');
    modal.classList.add('opacity-0');
    box.classList.remove('scale-100');
    box.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
        location.reload();
    }, 250);
}

function editCategory(id, name, limit) {
    document.getElementById("category_id").value = id;
    document.getElementById("category_name").value = name;
    document.getElementById("category_limit").value = limit;
    document.getElementById("submitButton").innerText = "Update Category";
    // Highlight the form
    document.getElementById("category_name").focus();
}

/* ---------------------- AJAX Add / Update Category ------------------------ */
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('category_id').value;
    const name = document.getElementById('category_name').value;
    const limit = document.getElementById('category_limit').value;
    const url = id ? `/categories/update` : `/categories/store`;

    fetch(url, {
        method: 'POST',
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ id: id, name: name, limit_amount: limit })
    })
    .then(res => {
        if (!res.ok) {
            return res.json().then(err => {
                throw err;
            });
        }
        return res.json();
    })
    .then(data => {
        if(data.success){
            const successMessage = id 
                ? `Category "${name}" updated successfully!` 
                : `Category "${name}" added successfully!`;
            updateCategoryList(data.category, id);
            resetForm();
            showCategoryAlert(successMessage, 'success');
            refreshExpenseCategoryDropdown();
        } else {
            showCategoryAlert(data.message || 'An error occurred', 'error');
        }
    })
    .catch(error => {
        let errorMessage = 'Failed to save category. Please try again.';
        
        // Handle Laravel validation errors
        if (error.errors) {
            const firstError = Object.values(error.errors)[0];
            errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
        } else if (error.message) {
            errorMessage = error.message;
        }
        
        showCategoryAlert(errorMessage, 'error');
    });
/* ---------------------- Refresh Expense Modal Category Dropdown ------------------------ */
function refreshExpenseCategoryDropdown() {
    fetch('/api/categories')
        .then(res => res.json())
        .then(categories => {
            const select = document.getElementById('expense_category_id');
            if (!select) return;
            // Save current selection
            const current = select.value;
            // Remove all except the first option
            while (select.options.length > 1) select.remove(1);
            categories.forEach(cat => {
                const opt = document.createElement('option');
                opt.value = cat.id;
                opt.textContent = cat.name;
                select.appendChild(opt);
            });
            // Try to restore previous selection if still exists
            if ([...select.options].some(o => o.value == current)) {
                select.value = current;
            }
        });
}
});

function updateCategoryList(category, id) {
    const list = document.getElementById('categoryList');
    const firstLetter = category.name.charAt(0).toUpperCase();
    const limitFormatted = parseFloat(category.limit_amount).toFixed(2);
    
    if(id) {
        const li = list.querySelector(`li[data-id='${id}']`);
        li.querySelector('.category-name').innerText = category.name;
        li.querySelector('.text-xs span').innerText = `Rs. ${limitFormatted}`;
        // Update initial
        li.querySelector('.w-10').innerText = firstLetter;
        // Update onclick handlers
        const editBtn = li.querySelector('button[onclick^="editCategory"]');
        editBtn.setAttribute('onclick', `editCategory(${category.id}, '${category.name}', '${category.limit_amount}')`);
    } else {
        const li = document.createElement('li');
        li.setAttribute('data-id', category.id);
        li.className = "group flex justify-between items-center p-3 rounded-xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-blue-200 dark:hover:border-blue-800 hover:shadow-md transition-all duration-200";
        li.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-lg">
                    ${firstLetter}
                </div>
                <div>
                    <div class="font-bold text-gray-800 dark:text-gray-100 category-name text-sm">
                        ${category.name}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                        Limit: <span class="text-gray-700 dark:text-gray-300">Rs. ${limitFormatted}</span>
                    </div>
                </div>
            </div>

            <div class="flex gap-1 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-200">
                <button onclick="editCategory(${category.id}, '${category.name}', '${category.limit_amount}')"
                        class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                </button>
                <button onclick="showDeleteConfirm(${category.id})"
                        class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Delete">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        `;
        list.prepend(li);
    }
}

function resetForm() {
    document.getElementById('category_id').value = '';
    document.getElementById('category_name').value = '';
    document.getElementById('category_limit').value = '';
    document.getElementById('submitButton').innerText = "Add Category";
}

/* ---------------------- Live Search ------------------------ */
function filterCategories() {
    const searchValue = document.getElementById('categorySearch').value.toLowerCase();
    document.querySelectorAll('#categoryList li').forEach(item => {
        const name = item.querySelector('.category-name').innerText.toLowerCase();
        item.style.display = name.includes(searchValue) ? '' : 'none';
    });
}
</script>
