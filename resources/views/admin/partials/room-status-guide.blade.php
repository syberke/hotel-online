<section class="rounded-2xl border border-blue-200 bg-blue-50 p-4 shadow-sm">
    <div class="flex items-start gap-3">
        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white text-blue-700 shadow-sm"><i class="fa-solid fa-circle-info"></i></span>
        <div class="min-w-0">
            <p class="text-sm font-semibold text-blue-950">Room status guide</p>
            <p class="mt-1 text-sm leading-6 text-blue-900">
                Physical room statuses are <strong>Available</strong>, <strong>Occupied</strong>, and <strong>Maintenance</strong>. Reserved is a booking overlay, not a value stored in the room status column.
            </p>
        </div>
    </div>
</section>

<style>
    .staff-main-content button[name="status"][value="dirty"] {
        display: none !important;
    }
</style>

<script>
    (() => {
        const normalizeRoomStatusUi = () => {
            const root = document.querySelector('.staff-main-content');
            if (!root) return;

            root.querySelectorAll('option[value="dirty"], button[name="status"][value="dirty"]').forEach((element) => element.remove());

            root.querySelectorAll('span, p, label, option, button').forEach((element) => {
                const text = element.textContent.trim();

                if (text === 'Cleaning Process') {
                    element.closest('.bg-white.p-5')?.remove();
                    return;
                }

                if (text === 'Cleaning') {
                    const legendItem = element.closest('div.flex.items-center');
                    if (legendItem?.querySelector('.bg-purple-500')) {
                        legendItem.remove();
                        return;
                    }

                    element.textContent = 'Maintenance';
                    return;
                }

                if (text === 'Dirty / Cleaning Turn' || text === 'Set Cleaning') {
                    element.remove();
                    return;
                }

                if (text === 'Out Of Order' || text === 'Out of Order') {
                    element.textContent = 'Maintenance';
                    return;
                }

                if (text === 'Set Out Of Order') {
                    element.innerHTML = '<span class="mr-2 inline-block h-2 w-2 rounded-full bg-amber-500"></span> Set Maintenance';
                    return;
                }

                if (text === 'Maint. Block') {
                    element.textContent = 'Maintenance';
                    return;
                }

                if (text === 'Reserved Rooms') {
                    element.textContent = 'Reserved Bookings';
                    return;
                }

                if (text === 'Reserved') {
                    element.textContent = 'Reserved Booking';
                }
            });

            const statusBadge = document.getElementById('mv_room_status');
            if (statusBadge) {
                const status = statusBadge.textContent.trim().toLowerCase();

                if (status === 'dirty' || status === 'cleaning') {
                    statusBadge.textContent = 'Maintenance';
                    statusBadge.className = 'inline-flex rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-900';
                } else if (status === 'reserved') {
                    statusBadge.textContent = 'Reserved Booking';
                }
            }
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', normalizeRoomStatusUi, { once: true });
        } else {
            normalizeRoomStatusUi();
        }

        const observer = new MutationObserver(normalizeRoomStatusUi);
        observer.observe(document.documentElement, {
            childList: true,
            subtree: true,
            characterData: true,
        });
    })();
</script>
