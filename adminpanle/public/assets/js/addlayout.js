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
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('media-table-body');
    const layoutForm = document.getElementById('layoutForm');

    // Select Media Button
    document.querySelectorAll('.select-media').forEach(button => {
        button.addEventListener('click', function () {
            const name = this.getAttribute('data-name');
            const type = this.getAttribute('data-type');
            const duration = this.getAttribute('data-duration');

            // Prevent duplicate
            const isDuplicate = Array.from(tableBody.querySelectorAll('tr')).some(row => {
                return row.children[0].textContent.trim() === name;
            });

            if (isDuplicate) {
                Swal.fire('Already Selected', 'This media is already in the table.', 'info');
                return;
            }

            const newRow = `
                <tr>
                    <td>${name}</td>
                    <td>${type}</td>
                    <td>${duration}</td>
                    <td><button class="btn btn-sm btn-danger remove-row">Remove</button></td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', newRow);
        });
    });

    // Remove row handler
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });

    // Submit Handler with Media Check
    layoutForm.addEventListener('submit', function (e) {
        const rows = document.querySelectorAll('#media-table-body tr');
        if (rows.length === 0) {
            e.preventDefault(); // stop form
            Swal.fire({
                icon: 'warning',
                title: 'No Media Selected',
                text: 'Please select at least one media item before submitting.'
            });
            return;
        }

        // If media selected, build JSON
        const media = [];
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            media.push({
                name: cells[0].textContent.trim(),
                type: cells[1].textContent.trim(),
                duration: cells[2].textContent.trim()
            });
        });

        document.getElementById('mediaInput').value = JSON.stringify(media);
    });
});


