document.addEventListener('DOMContentLoaded', function() {
    // Make table rows clickable (for mobile)
    const tableRows = document.querySelectorAll('.modern-table tbody tr');

    tableRows.forEach(row => {
        row.addEventListener('click', function(e) {
            if (!e.target.closest('.table-actions') && window.innerWidth <= 768) {
                const productId = row.querySelector('td').textContent;
                window.location.href = 'product_detail.php?id=' + productId;
            }
        });
    });

    // Sortable columns
    const sortableHeaders = document.querySelectorAll('.modern-table thead th');
    sortableHeaders.forEach(header => {
        if (header.querySelector('i')) {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                const column = header.textContent.trim().replace(/ [↑↓]$/, '');
                const currentSort = header.getAttribute('data-sort') || 'none';
                let newSort, newDirection;
                if (currentSort === 'none' || currentSort === 'desc') {
                    newSort = 'asc';
                    newDirection = '↑';
                } else {
                    newSort = 'desc';
                    newDirection = '↓';
                }

                sortableHeaders.forEach(h => {
                    h.setAttribute('data-sort', 'none');
                    const icon = h.querySelector('i');
                    if (icon) {
                        h.textContent = h.textContent.replace(/ [↑↓]$/, '');
                        h.appendChild(icon);
                    }
                });

                header.setAttribute('data-sort', newSort);
                header.textContent = header.textContent.replace(/ [↑↓]$/, '') + ' ' + newDirection;

                const url = new URL(window.location.href);
                url.searchParams.set('sort', column);
                url.searchParams.set('direction', newSort);
                window.location.href = url.toString();
            });
        }
    });

    // Search functionality
    const searchInput = document.querySelector('.search-box input');
    const searchForm = document.querySelector('.search-box');

    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }

    searchInput.addEventListener('input', debounce(function() {
        searchForm.submit();
    }, 500));
});