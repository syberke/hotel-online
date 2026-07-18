@php
    $financePortalPrefix = auth()->user()->role === 'manager' ? 'manager' : 'admin';
    $financeDetailTemplate = route($financePortalPrefix . '.finance.transaction.detail', ['id' => '__TRANSACTION_ID__']);
    $financeUpdateTemplate = route('admin.finance.transaction.update', ['id' => '__TRANSACTION_ID__']);
@endphp

<style>
    .staff-main-content {
        background-color: #e7edf5 !important;
    }

    .staff-main-content .bg-white.border[class*="shadow-sm"] {
        border-color: #cbd5e1 !important;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.08) !important;
    }

    .staff-main-content table thead {
        background-color: #f1f5f9 !important;
    }

    .staff-main-content table tbody tr:hover {
        background-color: #eef4fb !important;
    }

    #modalTrxDetail,
    #modalManageTrx {
        background-color: rgba(15, 23, 42, 0.72) !important;
        backdrop-filter: blur(4px);
    }

    #modalTrxDetail > div,
    #modalManageTrx > div {
        border-color: #94a3b8 !important;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.32) !important;
    }
</style>

<script>
    (() => {
        const detailUrlTemplate = @json($financeDetailTemplate);
        const updateUrlTemplate = @json($financeUpdateTemplate);

        const urlFor = (template, id) => template.replace('__TRANSACTION_ID__', String(id));

        const transactionIdFromButton = (button) => {
            const source = button.getAttribute('onclick') || '';
            const match = source.match(/\((\d+)\)/);
            return match ? Number.parseInt(match[1], 10) : null;
        };

        const fetchTransaction = async (id) => {
            const response = await fetch(urlFor(detailUrlTemplate, id), {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                throw new Error(`Server mengembalikan halaman HTML (${response.status}), bukan detail transaksi JSON.`);
            }

            const payload = await response.json();
            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Detail transaksi tidak tersedia.');
            }

            return payload.data;
        };

        const showDetail = async (id) => {
            const transaction = await fetchTransaction(id);

            document.getElementById('lbl_trx_guest').textContent = transaction.guest_name || 'Walk-in Cash Customer';
            document.getElementById('lbl_trx_amount').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(Math.round(Number(transaction.amount || 0)))}`;
            document.getElementById('lbl_trx_method').textContent = String(transaction.payment_method || '-').replaceAll('_', ' ');
            document.getElementById('lbl_trx_status').textContent = transaction.payment_status || '-';
            document.getElementById('lbl_trx_note').textContent = transaction.note || 'None recorded.';

            const statusLabel = document.getElementById('lbl_trx_status');
            const isPaid = ['paid', 'success'].includes(String(transaction.payment_status || '').toLowerCase());
            statusLabel.className = isPaid
                ? 'text-emerald-700 font-bold uppercase'
                : 'text-amber-700 font-bold uppercase';

            document.getElementById('modalTrxDetail').classList.remove('hidden');
        };

        const showManage = async (id) => {
            const transaction = await fetchTransaction(id);
            document.getElementById('select_trx_status').value = String(transaction.payment_status || 'pending').toLowerCase();
            document.getElementById('formUpdateTrxStage').action = urlFor(updateUrlTemplate, id);
            document.getElementById('modalManageTrx').classList.remove('hidden');
        };

        document.addEventListener('click', async (event) => {
            const detailButton = event.target.closest('button[onclick^="viewTrxDetail"]');
            const manageButton = event.target.closest('button[onclick^="openManageTrxModal"]');
            const button = detailButton || manageButton;

            if (!button) return;

            event.preventDefault();
            event.stopImmediatePropagation();

            const id = transactionIdFromButton(button);
            if (!id) {
                window.OasisDialog?.error('ID transaksi tidak valid.');
                return;
            }

            const originalHtml = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i>';

            try {
                if (manageButton) {
                    await showManage(id);
                } else {
                    await showDetail(id);
                }
            } catch (error) {
                window.OasisDialog?.error(error.message || 'Gagal memuat transaksi Finance.');
            } finally {
                button.disabled = false;
                button.innerHTML = originalHtml;
            }
        }, true);
    })();
</script>
