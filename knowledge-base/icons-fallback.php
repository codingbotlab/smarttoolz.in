<?php
declare(strict_types=1);

/* SmartToolz icon loader: use Font Awesome Free instead of Material Symbols ligatures. */
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<script>
document.addEventListener('DOMContentLoaded', function () {
    const icons = {
        menu_book: 'fa-solid fa-book-open',
        home: 'fa-solid fa-house',
        apps: 'fa-solid fa-table-cells-large',
        build: 'fa-solid fa-screwdriver-wrench',
        menu: 'fa-solid fa-bars',
        library_books: 'fa-solid fa-book',
        help_center: 'fa-solid fa-circle-question',
        arrow_forward: 'fa-solid fa-arrow-right',
        tune: 'fa-solid fa-sliders',
        search: 'fa-solid fa-magnifying-glass',
        school: 'fa-solid fa-graduation-cap',
        person: 'fa-solid fa-user'
    };

    document.querySelectorAll('.material-symbols-outlined').forEach(function (el) {
        const name = el.textContent.trim();
        const icon = icons[name];
        if (!icon) return;
        el.className = icon;
        el.textContent = '';
        el.setAttribute('aria-hidden', 'true');
    });
});
</script>
