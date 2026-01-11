<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                    <!-- Dashboard -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Expenses -->
                    <x-nav-link :href="route('expenses.page')" :active="request()->routeIs('expenses.page')">
                        {{ __('Expenses') }}
                    </x-nav-link>

                    <!-- Income -->
                    <x-nav-link :href="route('income.page')" :active="request()->routeIs('income.page')">
                        {{ __('Income') }}
                    </x-nav-link>

                </div>
            </div>
           <!-- Notification Bell -->
<!-- Right Section (Notification + User Menu) -->
<div class="flex sm:hidden items-center sm:space-x-4">
    <!-- Mobile Notification -->
    <div class="relative mr-3">
        <button id="notificationBellMobile" class="relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none" onclick="toggleNotificationsMobile()">
            <i class="bi bi-bell text-xl text-gray-700 dark:text-gray-300"></i>

            @php
                $now = now();
                $firstThisMonth = $now->copy()->startOfMonth();
                $lastThisMonth = $now->copy()->endOfMonth();
                $unreadCount = \App\Models\Notification::where('user_id', Auth::id())
                    ->where('read', false)
                    ->whereBetween('created_at', [$firstThisMonth, $lastThisMonth])
                    ->count();
            @endphp
            <span id="notificationCountMobile"
                class="absolute top-0 right-0 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center shadow-lg animate-pulse"
                style="{{ $unreadCount > 0 ? 'display:flex;' : 'display:none;' }}">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        </button>
    </div>
</div>

<div class="hidden sm:flex sm:items-center sm:space-x-4">

    <!-- Desktop Notification -->
    <div class="relative" style="margin-right: 5px;">
        <button id="notificationBell" class="relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400" onclick="toggleNotifications()">
            <i class="bi bi-bell text-xl text-gray-700 dark:text-gray-300" ></i>

            @php
                $now = now();
                $firstThisMonth = $now->copy()->startOfMonth();
                $lastThisMonth = $now->copy()->endOfMonth();
                $unreadCount = \App\Models\Notification::where('user_id', Auth::id())
                    ->where('read', false)
                    ->whereBetween('created_at', [$firstThisMonth, $lastThisMonth])
                    ->count();
            @endphp
            <span id="notificationCount"
                class="absolute top-0 right-0 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center shadow-lg animate-pulse"
                style="{{ $unreadCount > 0 ? 'display:flex;' : 'display:none;' }}">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        </button>

        <!-- Desktop Dropdown -->
        <div id="notificationDropdown"
            class="absolute right-0 mt-3 w-[28rem] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 
            rounded-xl shadow-2xl z-50 hidden overflow-hidden backdrop-blur-sm">
            <!-- Header -->
            <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-750 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <i class="bi bi-bell-fill text-blue-600 dark:text-blue-400 text-lg"></i>
                    <span class="font-bold text-lg text-gray-800 dark:text-gray-100">Notifications</span>
                </div>
                <button onclick="toggleNotifications()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-white dark:hover:bg-gray-700 rounded-full p-1.5 transition-all">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>
            <!-- Notification List -->
            <ul id="notificationList" style="width: 290px"class="max-h-96 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent"></ul>
            <!-- Footer -->
            <div class="px-5 py-3 text-xs text-center text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                <i class="bi bi-info-circle mr-1"></i>Tap a notification to mark as read
            </div>
        </div>
    </div>

    <!-- User Dropdown -->
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm
                leading-4 font-medium rounded-md text-gray-600 dark:text-gray-300
                bg-white dark:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100
                focus:outline-none transition">
                <div>{{ Auth::user()->name }}</div>
                <div class="ms-1">
                    <i class="bi bi-caret-down-fill text-xs"></i>
                </div>
            </button>
        </x-slot>

        <x-slot name="content">
            <x-dropdown-link :href="route('profile.edit')">
                Profile
            </x-dropdown-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')"
                     onclick="event.preventDefault(); this.closest('form').submit();">
                    Log Out
                </x-dropdown-link>
            </form>
        </x-slot>
    </x-dropdown>

</div>


            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">


            <!-- Dashboard -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Expenses -->
            <x-responsive-nav-link :href="route('expenses.page')" :active="request()->routeIs('expenses.page')">
                {{ __('Expenses') }}
            </x-responsive-nav-link>

            <!-- Income -->
            <x-responsive-nav-link :href="route('income.page')" :active="request()->routeIs('income.page')">
                {{ __('Income') }}
            </x-responsive-nav-link>

        </div>


        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Notification Modal -->
<div id="notificationModalMobile" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden sm:hidden" onclick="toggleNotificationsMobile()">
    <div class="fixed inset-x-0 bottom-0 bg-white dark:bg-gray-800 rounded-t-3xl shadow-2xl max-h-[80vh] overflow-hidden" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-750 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <i class="bi bi-bell-fill text-blue-600 dark:text-blue-400 text-lg"></i>
                <span class="font-bold text-lg text-gray-800 dark:text-gray-100">Notifications</span>
            </div>
            <button onclick="toggleNotificationsMobile()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-white dark:hover:bg-gray-700 rounded-full p-1.5 transition-all">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>
        <!-- Notification List -->
        <ul id="notificationListMobile" class="overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent" style="max-height: calc(80vh - 120px);"></ul>
        <!-- Footer -->
        <div class="px-5 py-3 text-xs text-center text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
            <i class="bi bi-info-circle mr-1"></i>Tap a notification to mark as read
        </div>
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
// Ensure notification count is set correctly on page load
document.addEventListener('DOMContentLoaded', function() {
    fetchNotifications('notificationList', 'notificationCount');
    fetchNotifications('notificationListMobile', 'notificationCountMobile');
});
</script>

<!-- Notification Dropdown Script -->
<script>
// Desktop Notifications
function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    dropdown.classList.toggle('hidden');
    if (!dropdown.classList.contains('hidden')) {
        fetchNotifications('notificationList', 'notificationCount');
    }
}

// Mobile Notifications
function toggleNotificationsMobile() {
    const modal = document.getElementById('notificationModalMobile');
    modal.classList.toggle('hidden');
    if (!modal.classList.contains('hidden')) {
        fetchNotifications('notificationListMobile', 'notificationCountMobile');
    }
}

function fetchNotifications(listId, countId) {
    fetch('/notifications')
        .then(res => res.json())
        .then(data => {
            const list = document.getElementById(listId);
            list.innerHTML = '';
            let unreadCount = 0;
            if (data.length === 0) {
                list.innerHTML = '<li class="px-6 py-12 text-center"><div class="flex flex-col items-center gap-3"><i class="bi bi-bell-slash text-4xl text-gray-300 dark:text-gray-600"></i><span class="text-gray-500 dark:text-gray-400 text-sm">No notifications yet</span></div></li>';
            } else {
                data.forEach(n => {
                    const li = document.createElement('li');
                    li.className =
                        'flex items-start gap-4 px-5 py-4 cursor-pointer transition-all duration-200 border-l-4 ' +
                        (n.read
                            ? 'border-transparent text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-750'
                            : 'border-blue-500 text-gray-800 dark:text-gray-100 bg-blue-50 dark:bg-gray-700/50 hover:bg-blue-100 dark:hover:bg-gray-700 shadow-sm');

                    // Warning icon
                    const icon = document.createElement('div');
                    icon.className = 'mt-0.5 flex-shrink-0';
                    icon.innerHTML = '<div class="w-10 h-10 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center"><i class="bi bi-exclamation-triangle-fill text-yellow-600 dark:text-yellow-500 text-lg"></i></div>';

                    // Message and date/time
                    const content = document.createElement('div');
                    content.className = 'flex-1 min-w-0';
                    content.innerHTML =
                        '<div class="' + (n.read ? 'font-normal' : 'font-semibold') + ' text-sm leading-relaxed mb-2">' + n.message + '</div>' +
                        '<div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400"><i class="bi bi-clock text-xs"></i><span>' +
                        (n.created_at ? formatDateTime(n.created_at) : '') +
                        '</span></div>';

                    li.appendChild(icon);
                    li.appendChild(content);

                    // Delete button
                    const deleteBtn = document.createElement('button');
                    deleteBtn.className = 'p-1.5 rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors';
                    deleteBtn.innerHTML = '<i class="bi bi-trash"></i>';
                    deleteBtn.onclick = function(e) {
                        e.stopPropagation();
                        deleteNotification(n.id, li, listId, countId);
                    };
                    li.appendChild(deleteBtn);

                    li.onclick = function() { markNotificationRead(n.id, li, listId, countId); };
                    list.appendChild(li);
                    if (!n.read) unreadCount++;
                });
            }
            
            // Update both desktop and mobile counters
            const desktopCount = document.getElementById('notificationCount');
            const mobileCount = document.getElementById('notificationCountMobile');
            
            if (desktopCount) {
                desktopCount.style.display = unreadCount > 0 ? 'flex' : 'none';
                desktopCount.textContent = unreadCount > 9 ? '9+' : unreadCount;
            }
            if (mobileCount) {
                mobileCount.style.display = unreadCount > 0 ? 'flex' : 'none';
                mobileCount.textContent = unreadCount > 9 ? '9+' : unreadCount;
            }
        });
}

function formatDateTime(dt) {
    const d = new Date(dt);
    if (isNaN(d.getTime())) return '';
    return d.toLocaleDateString() + ' ' + d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

function markNotificationRead(id, li, listId, countId) {
    fetch('/notifications/read/' + id, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                li.classList.remove('text-red-600', 'font-semibold');
                li.classList.add('text-gray-400');
                fetchNotifications(listId, countId);
            }
        });
}

function deleteNotification(id, li, listId, countId) {
    if (!confirm('Are you sure you want to remove this notification?')) return;

    fetch('/notifications/delete/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            li.remove();
            fetchNotifications(listId, countId);
        }
    });
}

// Hide dropdown when clicking outside (Desktop only)
document.addEventListener('click', function(e) {
    const bell = document.getElementById('notificationBell');
    const dropdown = document.getElementById('notificationDropdown');
    if (bell && dropdown && !bell.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>

<style>
/* Custom scrollbar */
.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: transparent;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.dark .scrollbar-thin::-webkit-scrollbar-thumb {
    background: #4b5563;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.dark .scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}
</style>