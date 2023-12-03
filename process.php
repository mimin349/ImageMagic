<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a file was uploaded
    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] === UPLOAD_ERR_OK) {
        // Get the uploaded file path
        $imagePath = $_FILES['imageFile']['tmp_name'];

        // Additional ImageMagick filters
        $outputPath = 'filtered_image.jpg';
        $filter = isset($_POST['filter']) ? $_POST['filter'] : '';

        // Use ImageMagick to apply the selected filter
        switch ($filter) {
            case 'blur':
                $command = "/usr/bin/convert $imagePath -blur 5x5 $outputPath";
                break;
            case 'sharpen':
                $command = "/usr/bin/convert $imagePath -sharpen 5x5 $outputPath";
                break;
            case 'emboss':
                $command = "/usr/bin/convert $imagePath -emboss 5x5 $outputPath";
                break;
            case 'grayscale':
                $command = "/usr/bin/convert $imagePath -colorspace Gray $outputPath";
                break;
            case 'sepia':
                $command = "/usr/bin/convert $imagePath -sepia-tone 80% $outputPath";
                break;
            case 'negate':
                $command = "/usr/bin/convert $imagePath -negate $outputPath";
                break;
            case 'despeckle':
                $command = "/usr/bin/convert $imagePath -despeckle $outputPath";
                break;
            case 'edge':
                $command = "/usr/bin/convert $imagePath -edge 5 $outputPath";
                break;
            case 'oil_painting':
                $command = "/usr/bin/convert $imagePath -paint 5 $outputPath";
                break;
            default:
                $command = "/usr/bin/convert $imagePath $outputPath";
                break;
        }

        exec($command, $output, $status);

        // Check if the command was successful
        if ($status === 0) {
            echo "Filter applied successfully! <a href='$outputPath' download>Download Filtered Image</a>";
        } else {
            echo "Error applying the filter. Please try again.";
        }

        // Delete the uploaded image file
        unlink($imagePath);
        exit();
    } else {
        echo "Error uploading file. An upload error occurred.";
        exit();
    }
}
?>