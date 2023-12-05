<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImageMagick Example</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="label">
        <p class="imagi-craft"><span class="text-wrapper">Image</span><span class="span">Craft</span></p>
    </div>
    <div class="deskripsi">
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
                        $command = "convert $imagePath -blur 5x5 $outputPath";
                        break;
                    case 'sharpen':
                        $command = "convert $imagePath -sharpen 5x5 $outputPath";
                        break;
                    case 'emboss':
                        $command = "convert $imagePath -emboss 5x5 $outputPath";
                        break;
                    case 'grayscale':
                        $command = "convert $imagePath -colorspace Gray $outputPath";
                        break;
                    case 'sepia':
                        $command = "convert $imagePath -sepia-tone 80% $outputPath";
                        break;
                    case 'negate':
                        $command = "convert $imagePath -negate $outputPath";
                        break;
                    case 'despeckle':
                        $command = "convert $imagePath -despeckle $outputPath";
                        break;
                    case 'edge':
                        $command = "convert $imagePath -edge 5 $outputPath";
                        break;
                    case 'oil_painting':
                        $command = "convert $imagePath -paint 5 $outputPath";
                        break;
                    default:
                        $command = "convert $imagePath $outputPath";
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
    </div>
    <br>
    <button onclick="window.location.href='index.html'">
        <div class="text-wrapper">Kembali ke ImageCraft</div>
    </button>
</body>
</html>
