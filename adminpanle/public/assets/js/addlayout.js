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

// display add 
    const allDisplays = window.displayData;
    const storeSelect = document.getElementById('storeSelect');
    const displaySelect = document.getElementById('displaySelect');
    const displayCount = document.getElementById('displayCount');
    const selectedDisplaysContainer = document.getElementById('selectedDisplays');
    const selectedCount = document.getElementById('selectedCount');
    const addBtn = document.getElementById('addBtn');
    const noAvailableData = document.getElementById('noAvailableData');
    const noSelectedData = document.getElementById('noSelectedData');

    let selectedItems = [];

    // Enable/disable add button based on selection
    displaySelect.addEventListener('change', function () {
        addBtn.disabled = displaySelect.selectedOptions.length === 0;
    });

    // Filter displays when store changes
    storeSelect.addEventListener('change', function () {
        const selectedStore = this.value;
        const filtered = allDisplays.filter(d => d.store_id === selectedStore);

        // Reset dropdown
        displaySelect.innerHTML = '';
        if (filtered.length === 0) {
            noAvailableData.style.display = 'block';
        } else {
            noAvailableData.style.display = 'none';
        }

        filtered.forEach(display => {
            const option = document.createElement('option');
            option.value = display.display_id;
            option.text = `${display.display_id} - ${display.name}`;
            displaySelect.appendChild(option);
        });

        displayCount.textContent = filtered.length;
        addBtn.disabled = true; // reset add button
    });

    // Add selected items
    addBtn.addEventListener('click', function () {
        const selectedOptions = Array.from(displaySelect.selectedOptions);

        selectedOptions.forEach(option => {
            const displayId = option.value;
            if (!selectedItems.some(item => item.display_id == displayId)) {
                const displayData = allDisplays.find(d => d.display_id == displayId);
                selectedItems.push(displayData);
                renderSelectedDisplay(displayData);
            }
        });

        updateSelectedCount();
        addBtn.disabled = true;
    });

    // Render tag with remove button
    function renderSelectedDisplay(display) {
        const tag = document.createElement('span');
        tag.className = 'display-tag';
        tag.setAttribute('data-id', display.display_id);

        tag.innerHTML = `
            <span>${display.display_id} - ${display.name}</span>
            <button class="btn-sm btn-danger" onclick="removeDisplay('${display.display_id}')">&times;</button>
        `;

        selectedDisplaysContainer.appendChild(tag);
        noSelectedData.style.display = 'none';
    }

    // Remove display by ID
    window.removeDisplay = function (id) {
        selectedItems = selectedItems.filter(d => d.display_id != id);
        const tag = selectedDisplaysContainer.querySelector(`[data-id="${id}"]`);
        if (tag) tag.remove();
        updateSelectedCount();
    }

    // Update count
    function updateSelectedCount() {
        selectedCount.textContent = selectedItems.length;
        noSelectedData.style.display = selectedItems.length === 0 ? 'block' : 'none';
    }

    
   document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('layoutForm');
    const hiddenInput = document.getElementById('selectedDisplaysInput');

    form.addEventListener('submit', function () {
        const displayIds = selectedItems.map(item => item.display_id);
        hiddenInput.value = JSON.stringify(displayIds);
        console.log("Submitting displays:", hiddenInput.value);
    });
});