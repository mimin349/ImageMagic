<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a file was uploaded
    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] === UPLOAD_ERR_OK) {
        // Get the uploaded file path
        $imagePath = $_FILES['imageFile']['tmp_name'];

        // Additional ImageMagick filters
        $outputPath = 'filtered_image.jpg';
        $filter = isset($_POST['filter']) ? $_POST['filter'] : '';
        $filterPercentage = isset($_POST['filterPercentage']) ? $_POST['filterPercentage'] : 50; // Default to 50 if not set

        // Use ImageMagick to apply the selected filter with the specified percentage
        switch ($filter) {
            case 'blur':
                $command = "/usr/bin/convert $imagePath -blur " . ($filterPercentage / 10) . "x" . ($filterPercentage / 10) . " $outputPath";
                break;
            case 'sharpen':
                $command = "/usr/bin/convert $imagePath -sharpen " . ($filterPercentage / 10) . "x" . ($filterPercentage / 10) . " $outputPath";
                break;
            case 'emboss':
                $command = "/usr/bin/convert $imagePath -emboss " . ($filterPercentage / 10) . "x" . ($filterPercentage / 10) . " $outputPath";
                break;
            case 'grayscale':
                $command = "/usr/bin/convert $imagePath -colorspace Gray" . $filterPercentage . "$outputPath"; // Adjust as needed
                break;
            case 'sepia':
                $command = "/usr/bin/convert $imagePath -sepia-tone " . $filterPercentage . "% $outputPath";
                break;
            case 'negate':
                $command = "/usr/bin/convert $imagePath -negate" . $filterPercentage . "$outputPath"; // Adjust as needed
                break;
            case 'despeckle':
                $command = "/usr/bin/convert $imagePath -despeckle". $filterPercentage . "$outputPath"; // Adjust as needed
                break;
            case 'edge':
                $command = "/usr/bin/convert $imagePath -edge " . ($filterPercentage / 10) . " $outputPath";
                break;
            case 'oil_painting':
                $command = "/usr/bin/convert $imagePath -paint " . $filterPercentage . " $outputPath";
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
