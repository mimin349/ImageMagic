// Fungsi untuk memuat gambar pertama kali
function previewImage() {
    var preview = document.getElementById('preview');
    var fileInput = document.getElementById('imageFile');
    var file = fileInput.files[0];
    var reader = new FileReader();
    var browseLabel = document.getElementById('browseLabel'); // Tambahkan id 'browseLabel' pada label untuk tombol Browse

    reader.onload = function (e) {
        preview.src = e.target.result;
        preview.style.display = 'block';
        browseLabel.style.display = 'none'; // Menyembunyikan label untuk tombol Browse
        fileInput.style.display = 'none'; // Menyembunyikan tombol Browse

        // Menyimpan gambar ke sessionStorage
        sessionStorage.setItem('previewImage', preview.src);
    };

    reader.readAsDataURL(file);
}

// Fungsi untuk memperbarui preview filter
function previewFilter() {
    var preview = document.getElementById('preview');
    var filterSelect = document.getElementById('filter');
    var selectedFilter = filterSelect.value;

    // Mendapatkan nilai persentase filter dari input range
    var filterPercentageInput = document.getElementById('filterPercentage');
    var filterPercentage = filterPercentageInput.value;
    
    // Memperbarui label persentase filter
    var percentageLabel = document.getElementById('percentageLabel');
    percentageLabel.textContent = filterPercentage + '%';

    // Logika untuk memperbarui preview filter sesuai dengan filter dan persentase yang dipilih
    switch (selectedFilter) {
        case 'blur':
            // Implementasi logika untuk filter Blur
            preview.style.filter = 'blur(' + (filterPercentage / 10) + 'px)';
            break;
        case 'sharpen':
            // Implementasi logika untuk filter Sharpen
            preview.style.filter = 'contrast(' + (100 + filterPercentage) + '%) brightness(' + (100 + filterPercentage) + '%)';
            break;
        case 'emboss':
            // Implementasi logika untuk filter Emboss
            preview.style.filter = 'brightness(' + (100 + filterPercentage) + '%) contrast(' + (100 + filterPercentage) + '%)';
            break;
        case 'grayscale':
            // Implementasi logika untuk filter Grayscale
            preview.style.filter = 'grayscale(' + filterPercentage + '%)';
            break;
        case 'sepia':
            // Implementasi logika untuk filter Sepia
            preview.style.filter = 'sepia(' + filterPercentage + '%)';
            break;
        case 'negate':
            // Implementasi logika untuk filter Negate (Invert)
            preview.style.filter = 'invert(' + filterPercentage + '%)';
            break;
        case 'despeckle':
            // Implementasi logika untuk filter Despeckle
            preview.style.filter = 'blur(' + (filterPercentage / 100) + 'px) grayscale(' + filterPercentage + '%)';
            break;
        case 'edge':
            // Implementasi logika untuk filter Edge Detection
            preview.style.filter = 'brightness(' + (100 + filterPercentage) + '%) contrast(' + (100 + filterPercentage) + '%) grayscale(' + filterPercentage + '%)';
            break;
        case 'oil_painting':
            // Implementasi logika untuk filter Oil Painting
            preview.style.filter = 'url("#oilPaintingFilter")';
            break;
        default:
            // Menghapus filter jika tidak ada filter yang dipilih
            preview.style.filter = 'none';
            break;
    }

    // Menyimpan nilai filter dan persentase ke sessionStorage
    sessionStorage.setItem('selectedFilter', selectedFilter);
    sessionStorage.setItem('filterPercentage', filterPercentage);
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
