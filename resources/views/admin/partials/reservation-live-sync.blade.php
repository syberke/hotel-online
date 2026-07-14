@php
    $configuredCheckInTime = date('h:i A', strtotime(config('hotel.checkin_time')));
    $configuredCheckOutTime = date('h:i A', strtotime(config('hotel.checkout_time')));
@endphp

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const configuredCheckInTime = @json($configuredCheckInTime);
        const configuredCheckOutTime = @json($configuredCheckOutTime);

        const paymentBadgeClass = (status) => {
            const base = 'text-[8px] px-2 py-0.5 font-bold uppercase rounded-xs border';
            if (status === 'paid' || status === 'success') {
                return `${base} bg-emerald-50 text-emerald-800 border-emerald-100`;
            }
            if (status === 'failed') {
                return `${base} bg-rose-50 text-rose-800 border-rose-100`;
            }
            return `${base} bg-amber-50 text-amber-800 border-amber-100`;
        };

        const syncReservationRows = () => {
            document.querySelectorAll('table tbody tr').forEach((row) => {
                const cells = row.querySelectorAll('td');
                if (cells.length < 7) return;

                const bookingLabel = cells[0].querySelector('span')?.textContent ?? '';
                const bookingMatch = bookingLabel.match(/#OA-(\d+)/i);
                if (!bookingMatch) return;

                const checkInSpans = cells[3]?.querySelectorAll('span');
                const checkOutSpans = cells[4]?.querySelectorAll('span');
                if (checkInSpans?.[1]) checkInSpans[1].textContent = configuredCheckInTime;
                if (checkOutSpans?.[1]) checkOutSpans[1].textContent = configuredCheckOutTime;

                const bookingId = Number.parseInt(bookingMatch[1], 10);
                fetch(`/admin/reservations/${bookingId}/json-detail`, {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin',
                })
                    .then((response) => {
                        if (!response.ok) throw new Error(`HTTP ${response.status}`);
                        return response.json();
                    })
                    .then((data) => {
                        if (!data.success) return;

                        const paymentCell = cells[6];
                        const badge = paymentCell.querySelector('div span');
                        const method = paymentCell.querySelector('span.block');
                        const paymentStatus = String(data.payment_status || 'pending').toLowerCase();

                        if (badge) {
                            badge.textContent = paymentStatus === 'pending' ? 'UNPAID' : paymentStatus.toUpperCase();
                            badge.className = paymentBadgeClass(paymentStatus);
                        }
                        if (method) {
                            method.textContent = data.payment_method || '-';
                        }
                    })
                    .catch(() => {
                        // Biarkan server-rendered value tampil jika request detail gagal.
                    });
            });
        };

        const syncSelectedReservationAside = () => {
            const bookingLabel = document.querySelector('#print-aside-target .font-mono.block')?.textContent ?? '';
            const bookingMatch = bookingLabel.match(/#OA-(\d+)/i);
            if (!bookingMatch) return;

            const bookingId = Number.parseInt(bookingMatch[1], 10);
            fetch(`/admin/reservations/${bookingId}/json-detail`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            })
                .then((response) => {
                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    return response.json();
                })
                .then((data) => {
                    if (!data.success) return;

                    const heading = [...document.querySelectorAll('#print-aside-target h4')]
                        .find((node) => node.textContent.trim() === 'Payment Information');
                    const grid = heading?.nextElementSibling;
                    if (!grid) return;

                    const rows = [...grid.children];
                    const methodValue = rows[1];
                    const statusContainer = rows[3];
                    const statusBadge = statusContainer?.querySelector('span');
                    const paymentStatus = String(data.payment_status || 'pending').toLowerCase();

                    if (methodValue) methodValue.textContent = data.payment_method || '-';
                    if (statusBadge) {
                        statusBadge.textContent = paymentStatus === 'pending' ? 'UNPAID' : paymentStatus.toUpperCase();
                        statusBadge.className = paymentBadgeClass(paymentStatus);
                    }
                })
                .catch(() => {
                    // Tidak menimpa UI dengan data asumsi ketika endpoint gagal.
                });
        };

        syncReservationRows();
        syncSelectedReservationAside();

        window.openGlobalViewModal = function (bookingId) {
            const modal = document.getElementById('globalViewModal');
            const loading = document.getElementById('modal-loading-indicator');
            const content = document.getElementById('modal-content-area');

            loading.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Querying Ledger Data Matrix...';
            loading.classList.remove('hidden');
            content.classList.add('hidden');
            modal.classList.remove('hidden');

            fetch(`/admin/reservations/${bookingId}/json-detail`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            })
                .then((response) => {
                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    return response.json();
                })
                .then((data) => {
                    if (!data.success) throw new Error('Reservation payload unavailable');

                    document.getElementById('md-id').innerText = `#OA-${String(data.id).padStart(2, '0')}`;
                    document.getElementById('md-name').innerText = data.guest_name;
                    document.getElementById('md-guest-id').innerText = data.guest_id
                        ? `#GST-${String(data.guest_id).padStart(5, '0')}`
                        : 'Guest ID pending';
                    document.getElementById('md-identity').innerText = data.identity_number || 'Identity pending';
                    document.getElementById('md-email').innerText = data.guest_email;
                    document.getElementById('md-phone').innerText = data.guest_phone;
                    document.getElementById('md-address').innerText = data.guest_address || 'No permanent address recorded';
                    document.getElementById('md-type').innerText = data.room_type;
                    document.getElementById('md-room').innerText = `Room Number ${data.room_number || 'TBD'}`;
                    document.getElementById('md-in').innerText = `${data.check_in} · ${data.check_in_time}`;
                    document.getElementById('md-out').innerText = `${data.check_out} · ${data.check_out_time}`;
                    document.getElementById('md-duration').innerText = data.duration;
                    document.getElementById('md-guests').innerText = data.guests_count;
                    document.getElementById('md-method').innerText = data.payment_method || 'N/A';
                    document.getElementById('md-total').innerText = data.total_price;

                    const statusBadge = document.getElementById('md-status');
                    statusBadge.innerText = data.status.toUpperCase();
                    if (['confirmed', 'checked_in', 'checked_out'].includes(data.status)) {
                        statusBadge.className = 'inline-block text-[8px] font-bold uppercase tracking-wide px-2 py-0.5 border bg-emerald-50 text-emerald-800 border-emerald-200';
                    } else if (data.status === 'pending') {
                        statusBadge.className = 'inline-block text-[8px] font-bold uppercase tracking-wide px-2 py-0.5 border bg-amber-50 text-amber-800 border-amber-200';
                    } else {
                        statusBadge.className = 'inline-block text-[8px] font-bold uppercase tracking-wide px-2 py-0.5 border bg-rose-50 text-rose-800 border-rose-200';
                    }

                    const paymentStatus = String(data.payment_status || 'pending').toLowerCase();
                    const payBadge = document.getElementById('md-paystatus');
                    payBadge.innerText = paymentStatus === 'pending' ? 'UNPAID' : paymentStatus.toUpperCase();
                    payBadge.className = paymentBadgeClass(paymentStatus);

                    loading.classList.add('hidden');
                    content.classList.remove('hidden');
                })
                .catch(() => {
                    loading.innerText = 'Gagal memuat data reservasi dari ledger server.';
                });
        };
    });
</script>
