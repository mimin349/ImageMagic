function previewImage() {
    var preview = document.getElementById('preview');
    var fileInput = document.getElementById('imageFile');
    var file = fileInput.files[0];
    var reader = new FileReader();
    var browseLabel = document.getElementById('browseLabel');

    reader.onload = function (e) {
        preview.src = e.target.result;
        preview.style.display = 'block';
        browseLabel.style.display = 'none';
        fileInput.style.display = 'none';

        // Menyimpan gambar ke sessionStorage
        sessionStorage.setItem('previewImage', preview.src);
    };

    reader.readAsDataURL(file);
}

function previewFilter() {
    var preview = document.getElementById('preview');
    var filterSelect = document.getElementById('filter');
    var selectedFilter = filterSelect.value;

    // Menyimpan nilai filter ke sessionStorage
    sessionStorage.setItem('selectedFilter', selectedFilter);

    // Mengirim data filter ke server untuk proses ImageMagick
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'apply_filter.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    // If a new filter is selected, reset to 'none' first
    if (selectedFilter !== 'none') {
        var noneData = {
            filter: 'none',
            previewImage: sessionStorage.getItem('previewImage')
        };

        var noneXHR = new XMLHttpRequest();
        noneXHR.open('POST', 'apply_filter.php', true);
        noneXHR.setRequestHeader('Content-Type', 'application/json');

        noneXHR.onreadystatechange = function () {
            if (noneXHR.readyState === 4 && noneXHR.status === 200) {
                var noneResponseData = JSON.parse(noneXHR.responseText);
                preview.src = noneResponseData.previewImage;

                // Proceed with applying the new filter
                applyNewFilter();
            }
        };

        noneXHR.send(JSON.stringify(noneData));
    } else {
        // If 'none' is selected directly, apply the new filter
        applyNewFilter();
    }

    function applyNewFilter() {
        var data = {
            filter: selectedFilter,
            previewImage: preview.src
        };

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var responseData = JSON.parse(xhr.responseText);

                // Update preview based on the response
                preview.src = responseData.previewImage;
            }
        };

        xhr.send(JSON.stringify(data));
    }
}




// Fungsi untuk memeriksa apakah ada gambar hasil filter yang disimpan di sessionStorage saat halaman dimuat
window.onload = function () {
    var previewImage = sessionStorage.getItem('previewImage');
    var selectedFilter = sessionStorage.getItem('selectedFilter');
    var filterPercentage = sessionStorage.getItem('filterPercentage');

    if (previewImage) {
        var preview = document.getElementById('preview');
        preview.src = previewImage;
        preview.style.display = 'block';
        
        var browseLabel = document.getElementById('browseLabel');
        var fileInput = document.getElementById('imageFile');
        browseLabel.style.display = 'none';
        fileInput.style.display = 'none';

        if (selectedFilter && filterPercentage) {
            var filterSelect = document.getElementById('filter');
            var filterPercentageInput = document.getElementById('filterPercentage');
            var percentageLabel = document.getElementById('percentageLabel');
            
            filterSelect.value = selectedFilter;
            filterPercentageInput.value = filterPercentage;
            percentageLabel.textContent = filterPercentage + '%';

            previewFilter();
        }
    }
}

// Fungsi untuk validasi formulir
function validateForm() {
    var fileInput = document.getElementById('imageFile');

    // Validate if a file is selected
    if (!fileInput.value) {
        alert("Please choose an image file.");
        return false;
    }

    // Validate if the selected file is an image
    var allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!allowedTypes.includes(fileInput.files[0].type)) {
        alert("Please choose a valid image file (JPEG, PNG, or GIF).");
        return false;
    }

    // Proceed with form submission
    return true;
}
