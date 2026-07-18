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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const root = document.querySelector('.staff-main-content');
        if (!root) return;

        root.querySelectorAll('option[value="dirty"]').forEach((option) => option.remove());

        const replacements = new Map([
            ['Reserved Rooms', 'Reserved Bookings'],
            ['Reserved', 'Reserved Booking'],
            ['Out Of Order', 'Maintenance'],
            ['Out of Order', 'Maintenance'],
            ['Cleaning', 'Maintenance'],
        ]);

        root.querySelectorAll('span, option, th, p, label').forEach((element) => {
            if (element.children.length > 0) return;

            const current = element.textContent.trim();
            if (replacements.has(current)) {
                element.textContent = replacements.get(current);
            }
        });

        root.querySelectorAll('span').forEach((label) => {
            if (label.textContent.trim() !== 'Cleaning Process') return;

            const card = label.closest('.bg-white.p-5');
            card?.remove();
        });

        const normalizeDetailStatus = () => {
            const badge = document.getElementById('mv_room_status');
            if (!badge) return;

            const current = badge.textContent.trim().toLowerCase();
            if (current === 'dirty') badge.textContent = 'Maintenance';
            if (current === 'reserved') badge.textContent = 'Reserved Booking';
            if (current === 'maintenance') badge.textContent = 'Maintenance';
        };

        const detailBadge = document.getElementById('mv_room_status');
        if (detailBadge) {
            normalizeDetailStatus();
            new MutationObserver(normalizeDetailStatus).observe(detailBadge, {
                childList: true,
                subtree: true,
                characterData: true,
            });
        }
    });
</script>
