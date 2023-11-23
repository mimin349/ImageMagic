<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a file was uploaded
    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] === UPLOAD_ERR_OK) {
        // Get the uploaded file path
        $imagePath = $_FILES['imageFile']['tmp_name'];

        // Use ImageMagick to convert the image to grayscale
        $outputPath = 'grayscale.jpg';
        $command = "/usr/bin/convert $imagePath -colorspace Gray $outputPath";
        exec($command, $output, $status);

        // Check if the command was successful
        if ($status === 0) {
            echo "Conversion successful! <a href='$outputPath' download>Download Grayscale Image</a>";
        } else {
            echo "Error converting the image. Please try again.";
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
