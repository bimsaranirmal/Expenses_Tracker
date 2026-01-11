<div id="toastBox" class="fixed top-5 right-5 z-[9999] space-y-3"></div>

<script>
    function showToast(message, type = 'success') {
        let box = document.getElementById("toastBox");

        let toast = document.createElement("div");
        toast.className =
            `p-4 rounded-lg shadow-lg text-white animate-fade-in-down
            ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;

        toast.innerHTML = `
            <div class="font-semibold">${message}</div>
        `;

        box.appendChild(toast);

        // Auto-remove toast
        setTimeout(() => {
            toast.classList.add("animate-fade-out-up");
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif

        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
    });
</script>

<style>
    @keyframes fade-in-down {
        0% { opacity: 0; transform: translateY(-15px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes fade-out-up {
        0% { opacity: 1; transform: translateY(0); }
        100% { opacity: 0; transform: translateY(-15px); }
    }

    .animate-fade-in-down { animation: fade-in-down .3s ease-out forwards; }
    .animate-fade-out-up { animation: fade-out-up .3s ease-in forwards; }
</style>
