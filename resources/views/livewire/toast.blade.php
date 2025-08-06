<div
    x-data="{
        toasts: [],
        add(toast) {
            toast.id = Date.now() + Math.random();
            this.toasts.push(toast);
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== toast.id)
            }, 3000);
        }
    }"
    x-init="
        window.addEventListener('toast', e => Livewire.dispatch('toast', e.detail));
        Livewire.on('toast', toast => add(toast));
    "
    class="fixed top-4 right-4 flex flex-col space-y-2 z-50"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            class="px-4 py-2 rounded shadow text-white"
            :class="{
                'bg-green-500': toast.type === 'success',
                'bg-red-500': toast.type === 'error',
                'bg-yellow-500': toast.type === 'warning',
                'bg-blue-500': toast.type === 'info'
            }"
            x-text="toast.message"
        ></div>
    </template>
</div>
