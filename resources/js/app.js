

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

const restaurantCurrency = new Intl.NumberFormat('id-ID');

function closeRestaurantOrderDetail() {
    document.getElementById('restaurant-order-detail-modal')?.classList.add('hidden');
}

function renderRestaurantOrderItems(items) {
    const container = document.getElementById('restaurant-detail-items');
    if (!container) return;

    container.replaceChildren();

    if (!items.length) {
        const empty = document.createElement('p');
        empty.className = 'p-4 text-center text-[11px] italic text-neutral-400';
        empty.textContent = 'Tidak ada item menu pada pesanan ini.';
        container.appendChild(empty);
        return;
    }

    items.forEach((item) => {
        const row = document.createElement('div');
        row.className = 'p-2 flex justify-between items-center text-neutral-700 font-medium';

        const name = document.createElement('span');
        name.className = 'font-bold text-neutral-900 truncate max-w-[180px]';
        name.textContent = item.name ?? '-';

        const quantity = document.createElement('span');
        quantity.className = 'w-12 text-center font-mono text-neutral-500';
        quantity.textContent = item.quantity ?? 0;

        const price = document.createElement('span');
        price.className = 'w-24 text-right font-mono text-neutral-900 font-bold';
        price.textContent = `Rp ${restaurantCurrency.format(Number(item.price || 0))}`;

        row.append(name, quantity, price);
        container.appendChild(row);
    });
}

async function openRestaurantOrderDetail(button) {
    const originalHtml = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i>';

    try {
        const response = await fetch(button.dataset.detailUrl, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Detail pesanan tidak tersedia.');
        }

        const order = data.order;
        document.getElementById('restaurant-detail-id').textContent = `#RS-${String(order.id).padStart(4, '0')}`;
        document.getElementById('restaurant-detail-room').textContent = order.room_number || 'Table Walk-in';
        document.getElementById('restaurant-detail-guest').textContent = order.guest_name || '-';
        document.getElementById('restaurant-detail-time').textContent = order.created_at || '-';
        document.getElementById('restaurant-detail-total').textContent = `Rp ${restaurantCurrency.format(Number(order.total_price || 0))}`;
        document.getElementById('restaurant-detail-status').textContent = order.status || '-';
        renderRestaurantOrderItems(Array.isArray(data.items) ? data.items : []);
        document.getElementById('restaurant-order-detail-modal').classList.remove('hidden');
    } catch (error) {
        window.OasisDialog.error(error.message || 'Gagal mengambil detail pesanan.');
    } finally {
        button.disabled = false;
        button.innerHTML = originalHtml;
    }
}

document.addEventListener('click', (event) => {
    const detailButton = event.target.closest('[data-restaurant-order-detail]');
    if (detailButton) {
        openRestaurantOrderDetail(detailButton);
        return;
    }

    if (event.target.closest('[data-close-restaurant-detail]')) {
        closeRestaurantOrderDetail();
    }
});

Alpine.start();
