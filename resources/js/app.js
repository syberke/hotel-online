

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

window.Alpine = Alpine;
window.Swal = Swal;

const oasisDialogOptions = {
    buttonsStyling: false,
    customClass: {
        popup: 'rounded-none border border-neutral-200 shadow-2xl',
        title: 'font-serif text-2xl text-neutral-900',
        htmlContainer: 'text-sm text-neutral-600',
        confirmButton: 'bg-neutral-950 hover:bg-neutral-800 text-white px-6 py-2.5 text-xs font-bold uppercase tracking-widest mx-1',
        cancelButton: 'bg-white hover:bg-neutral-100 text-neutral-700 border border-neutral-300 px-6 py-2.5 text-xs font-bold uppercase tracking-widest mx-1',
    },
};

window.OasisDialog = {
    fire(options = {}) {
        return Swal.fire({ ...oasisDialogOptions, ...options });
    },
    success(message, title = 'Berhasil') {
        return this.fire({ icon: 'success', title, text: message });
    },
    error(message, title = 'Terjadi Kendala') {
        return this.fire({ icon: 'error', title, text: message });
    },
    info(message, title = 'Informasi') {
        return this.fire({ icon: 'info', title, text: message });
    },
    async confirm(message, title = 'Konfirmasi Tindakan') {
        const result = await this.fire({
            icon: 'warning',
            title,
            text: message,
            showCancelButton: true,
            confirmButtonText: 'Ya, lanjutkan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        });

        return result.isConfirmed;
    },
};

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-oasis-flash]').forEach((element) => {
        const type = element.dataset.type || 'info';
        const message = element.dataset.message || '';

        if (message && typeof window.OasisDialog[type] === 'function') {
            window.OasisDialog[type](message);
        }
    });
});

document.addEventListener('submit', async (event) => {
    const form = event.target.closest('form[data-confirm]');

    if (!form || form.dataset.confirmed === 'true') {
        return;
    }

    event.preventDefault();
    const submitter = event.submitter;
    const confirmed = await window.OasisDialog.confirm(
        form.dataset.confirm,
        form.dataset.confirmTitle || 'Konfirmasi Tindakan',
    );

    if (confirmed) {
        form.dataset.confirmed = 'true';
        form.requestSubmit(submitter || undefined);
    }
});

Alpine.start();
