import '../css/portal.css';
import '../css/brand.css';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

window.Alpine = Alpine;
window.Swal = Swal;

const oasisDialogOptions = {
    buttonsStyling: false,
    customClass: {
        popup: 'rounded-2xl border border-slate-200 shadow-2xl',
        title: 'text-2xl font-semibold tracking-tight text-slate-900',
        htmlContainer: 'text-sm leading-6 text-slate-600',
        confirmButton: 'rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 text-sm font-semibold mx-1',
        cancelButton: 'rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-6 py-3 text-sm font-semibold mx-1',
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
        empty.className = 'p-4 text-center text-sm text-slate-500';
        empty.textContent = 'Tidak ada item menu pada pesanan ini.';
        container.appendChild(empty);
        return;
    }

    items.forEach((item) => {
        const row = document.createElement('div');
        row.className = 'flex items-center justify-between gap-4 border-b border-slate-100 p-3 text-sm text-slate-700 last:border-0';

        const name = document.createElement('span');
        name.className = 'max-w-[180px] truncate font-semibold text-slate-900';
        name.textContent = item.name ?? '-';

        const quantity = document.createElement('span');
        quantity.className = 'w-12 text-center font-mono text-slate-500';
        quantity.textContent = item.quantity ?? 0;

        const price = document.createElement('span');
        price.className = 'w-28 text-right font-mono font-semibold text-slate-900';
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
