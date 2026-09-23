<style>
    .fi-sidebar-item-active a {
        background: rgba(251,191,36,0.13) !important;
    }
    .fi-sidebar-item-active .fi-sidebar-item-label {
        color: rgb(251,191,36) !important;
        font-weight: 600 !important;
    }
    .fi-sidebar-item-active a svg {
        color: rgb(251,191,36) !important;
    }

    /* Sticky Floating Action Bar for All Modules & Resource Forms */
    .fi-form-actions {
        position: -webkit-sticky !important;
        position: sticky !important;
        bottom: 1rem !important;
        z-index: 30 !important;
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        padding: 0.85rem 1.25rem !important;
        border-radius: 0.875rem !important;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08) !important;
        margin-top: 1.5rem !important;
        transition: all 0.2s ease !important;
    }
    .dark .fi-form-actions {
        background: rgba(17, 24, 39, 0.95) !important;
        border-color: rgba(255, 255, 255, 0.12) !important;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.6) !important;
    }
</style>

<script>
    (function () {
        const theme = localStorage.getItem('theme') || localStorage.getItem('color-theme') || 'light';
        if (theme === 'light') {
            document.documentElement.classList.remove('dark', 'fi-dark');
        } else if (theme === 'dark') {
            document.documentElement.classList.add('dark', 'fi-dark');
        }
    })();

    document.addEventListener('livewire:navigated', function () {
        const theme = localStorage.getItem('theme') || localStorage.getItem('color-theme') || 'light';
        if (theme === 'light') {
            document.documentElement.classList.remove('dark', 'fi-dark');
        } else if (theme === 'dark') {
            document.documentElement.classList.add('dark', 'fi-dark');
        }
    });
</script>
