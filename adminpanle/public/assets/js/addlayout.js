// Step validation
const nextButton = document.getElementById('next-to-step2');
const zoneTabButton = document.getElementById('steparrow-description-info-tab');
// Improved validation function
const validateForm = () => {
    let invalidFields = [];
    // 1. Validate Playlist Name (text input)
    const playlistName = document.getElementById('layout-name');
    if (!playlistName.value.trim()) {
        invalidFields.push('Playlist Name');
    }
    // 2. Validate Store selection (dropdown)
    const storeSelect = document.querySelector('#steparrow-gen-info select');
    if (!storeSelect.value || storeSelect.options[storeSelect.selectedIndex].disabled) {
        invalidFields.push('Store');
    }
    // 3. Validate Display Type (radio buttons)
    const displayTypeSelected = document.querySelector('input[name="displayMode"]:checked');
    if (!displayTypeSelected) {
        invalidFields.push('displayMode');
    }
    // 4. Validate Layout selection (radio buttons)
    const layoutSelected = document.querySelector('input[name="layoutName"]:checked');
    if (!layoutSelected) {
        invalidFields.push('layoutName');
    }
    // 5. Validate Display selection (check if any displays are selected)
     const displaySelect = document.getElementById('displaySelect');
    if (!displaySelect.value || displaySelect.options[displaySelect.selectedIndex].disabled) {
        invalidFields.push('selectedDisplays');
    }
    
    // Add this if you have display selection logic
    return invalidFields;
};
nextButton.addEventListener('click', function (e) {
    e.preventDefault();
    const invalidFields = validateForm();
    if (invalidFields.length > 0) {
        Swal.fire({
            title: 'Incomplete Form',
            text: `Please fill in: ${invalidFields.join(', ')}`,
            icon: 'error',
            confirmButtonText: 'OK'
        }).then(() => {
            // Focus on first missing field
            if (invalidFields.includes('Playlist Name')) {
                document.getElementById('layout-name').focus();
            } else if (invalidFields.includes('Store')) {
                document.querySelector('#steparrow-gen-info select').focus();
            } else if (invalidFields.includes('displayMode')) {
                document.querySelector('input[name="display-type"]').focus();
            } else if (invalidFields.includes('layoutName')) {
                document.querySelector('input[name="layoutName"]').focus();
            }else if (invalidFields.includes('selectedDisplays')) {
                document.querySelector('input[name="selectedDisplays"]').focus();
            }
        });
        return;
    }
    // If all valid, proceed to next tab
    if (zoneTabButton) {
        new bootstrap.Tab(zoneTabButton).show();
    }
});
//  logo preview
function previewLogo(event) {
    const input = event.target;
    const preview = document.getElementById('logoPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
//   show items in table
let currentZone = 'zone1'; // Default
document.addEventListener('DOMContentLoaded', function () {
    // Zone tab click
    document.querySelectorAll('.zone-tab').forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelectorAll('.zone-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            // Set active zone
            currentZone = this.getAttribute('data-zone');
            // Show corresponding table
            document.querySelectorAll('.zone-table').forEach(table => {
                table.classList.add('d-none');
            });
            document.getElementById('table-' + currentZone).classList.remove('d-none');
        });
    });
    // Use event delegation for selecting media
    document.addEventListener('click', function (e) {
        if (e.target.closest('.select-media')) {
            const el = e.target.closest('.select-media');
            const name = el.getAttribute('data-name');
            const type = el.getAttribute('data-type');
            const duration = el.getAttribute('data-duration');
            const zone = currentZone;
            // Check for duplicate in the same zone
            const isDuplicate = Array.from(document.querySelectorAll(`[data-zone="${zone}"] tr`)).some(
                row => row.children[0].textContent.trim() === name
            );
            if (isDuplicate) {
                Swal.fire('Already Selected', 'This media is already in the table.', 'info');
                return;
            }
            // Add new row
            const newRow = `
                <tr>
                    <td>${name}</td>
                    <td>${type}</td>
                    <td>${duration}</td>
                    <td><button class="btn btn-sm btn-danger remove-row">Remove</button></td>
                </tr>
            `;
            document.querySelector(`tbody[data-zone="${zone}"]`).insertAdjacentHTML('beforeend', newRow);
        }
    });
    // Remove row
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });
    // On form submit, serialize zone data
    document.getElementById('layoutForm').addEventListener('submit', function (e) {
        const mediaList = [];
        document.querySelectorAll('.media-table-body').forEach(tbody => {
            const zone = tbody.getAttribute('data-zone');
            tbody.querySelectorAll('tr').forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 3) {
                    mediaList.push({
                        name: cells[0].textContent.trim(),
                        type: cells[1].textContent.trim(),
                        duration: cells[2].textContent.trim(),
                        zone: zone
                    });
                }
            });
        });
        document.getElementById('mediaInput').value = JSON.stringify(mediaList);
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const storeSelect = document.getElementById('storeSelect');
    const displaySelect = document.getElementById('displaySelect');
    function filterDisplays() {
        const selectedStore = storeSelect.value;
        Array.from(displaySelect.options).forEach(option => {
            if (option.value === "" || !option.dataset.store) return;
            option.style.display = option.getAttribute('data-store') === selectedStore ? '' :
                'none';
        });
        if (displaySelect.selectedOptions.length &&
            displaySelect.selectedOptions[0].getAttribute('data-store') !== selectedStore) {
            displaySelect.selectedIndex = 0;
        }
    }
    storeSelect.addEventListener('change', filterDisplays);
    filterDisplays();
});