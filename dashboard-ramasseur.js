    document.addEventListener('DOMContentLoaded', function() {
    const barcodeInput = document.getElementById('barcode');
    const verificationPopup = document.getElementById('verificationPopup');
    const popupBarcode = document.getElementById('popupBarcode');
    const popupNumber = document.getElementById('popupNumber');
    const popupUser = document.getElementById('popupUser');
    const closePopup = document.querySelector('.close-popup');
    const confirmBtn = document.getElementById('confirmBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const currentUser = "Anis";

    barcodeInput.focus();
    let lastInputTime = 0;

    barcodeInput.addEventListener('input', function(e) {
    const now = new Date().getTime();
    const timeSinceLastInput = now - lastInputTime;

    if (timeSinceLastInput < 50 && this.value.length > 3) {
    showVerificationPopup(this.value);
    this.value = '';
}
    lastInputTime = now;
});

    function showVerificationPopup(barcode) {
    const randomNumber = Math.floor(100000 + Math.random() * 900000);
    popupBarcode.textContent = barcode;
    popupNumber.textContent = randomNumber;
    popupUser.textContent = currentUser;
    verificationPopup.style.display = 'flex';
}

    closePopup.addEventListener('click', function() {
    verificationPopup.style.display = 'none';
    barcodeInput.focus();
});

    confirmBtn.addEventListener('click', function() {
    alert('Barcode verified successfully!');
    verificationPopup.style.display = 'none';
    barcodeInput.focus();
});

    cancelBtn.addEventListener('click', function() {
    verificationPopup.style.display = 'none';
    barcodeInput.focus();
});

    window.addEventListener('click', function(e) {
    if (e.target === verificationPopup) {
    verificationPopup.style.display = 'none';
    barcodeInput.focus();
}
});

    document.addEventListener('click', function(e) {
    if (e.target !== barcodeInput) {
    barcodeInput.focus();
}
});

    function updateDateTime() {
    const now = new Date();
    const options = {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
};
    document.getElementById('currentDateTime').textContent = now.toLocaleDateString('en-US', options);
}

    updateDateTime();
    setInterval(updateDateTime, 1000);

    document.getElementById('refreshBtn').addEventListener('click', function() {
    location.reload();
});
});
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('detailsModal');
        const closeBtn = modal.querySelector('.close');
        const tableRows = document.querySelectorAll('#mainTable tbody tr');

        // Close modal when clicking X
        closeBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        tableRows.forEach(row => {
            row.addEventListener('click', function() {
                // Remove selected class from all rows
                tableRows.forEach(r => r.classList.remove('selected-row'));
                // Add selected class to clicked row
                this.classList.add('selected-row');

                // Get row data
                const cells = this.cells;
                const orderNumber = cells[0].textContent;
                const creationTime = cells[2].textContent;
                const startTime = cells[3].textContent;

                // Calculate elapsed time if start time exists
                let elapsedTime = '-';
                if (startTime !== '-') {
                    const [startHours, startMins, startSecs] = startTime.split(':').map(Number);
                    const [creationHours, creationMins, creationSecs] = creationTime.split(':').map(Number);

                    const startTotal = startHours * 3600 + startMins * 60 + startSecs;
                    const creationTotal = creationHours * 3600 + creationMins * 60 + creationSecs;

                    const diffSeconds = startTotal - creationTotal;
                    const minutes = Math.floor(diffSeconds / 60);
                    const seconds = diffSeconds % 60;

                    elapsedTime = `${minutes}m ${seconds}s`;
                }

                // Update modal content
                document.getElementById('orderNumber').textContent = orderNumber;
                document.getElementById('creationTime').textContent = creationTime;
                document.getElementById('startTime').textContent = startTime;
                document.getElementById('elapsedTime').textContent = elapsedTime;

                // Generate zones
                const zonesContainer = document.getElementById('zonesContainer');
                zonesContainer.innerHTML = '';

                const zones = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];
                zones.forEach(zone => {
                    const zoneDiv = document.createElement('div');
                    zoneDiv.className = 'zone';

                    const zoneName = document.createElement('div');
                    zoneName.className = 'zone-name';
                    zoneName.textContent = `Zone ${zone}`;

                    const basketNumber = document.createElement('div');
                    basketNumber.className = 'zone-value';
                    basketNumber.textContent = Math.floor(Math.random() * 10) + 1;

                    zoneDiv.appendChild(zoneName);
                    zoneDiv.appendChild(basketNumber);
                    zonesContainer.appendChild(zoneDiv);
                });

                // Show modal
                openModal();
            });
        });
    });

    function openModal() {
        const modal = document.getElementById('detailsModal');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('detailsModal');
        modal.classList.remove('active');
        document.querySelector('.selected-row')?.classList.remove('selected-row');
        document.body.style.overflow = 'auto';
    }

    function printModal() {
        window.print();
    }
