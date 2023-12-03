<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postData = json_decode(file_get_contents('php://input'), true);

    // Validate the input data
    if (!isset($postData['filter']) || !isset($postData['previewImage'])) {
        echo json_encode(['error' => 'Invalid input data.']);
        exit();
    }

    // Additional ImageMagick filters
    $filter = $postData['filter'];
    $previewImage = $postData['previewImage'];

    // Decode the base64 encoded image data
    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $previewImage));

    // Temporary file path for the original image
    $tempImagePath = tempnam(sys_get_temp_dir(), 'img');

    // Save the original image to a temporary file
    file_put_contents($tempImagePath, $imageData);

    // Perform ImageMagick operations based on the selected filter
    switch ($filter) {
        case 'blur':
            $command = "convert $tempImagePath -blur 5x5 $tempImagePath";
            break;
        case 'sharpen':
            $command = "convert $tempImagePath -sharpen 5x5 $tempImagePath";
            break;
        case 'emboss':
            $command = "convert $tempImagePath -emboss 5x5 $tempImagePath";
            break;
        case 'grayscale':
            $command = "convert $tempImagePath -colorspace Gray $tempImagePath";
            break;
        case 'sepia':
            $command = "convert $tempImagePath -sepia-tone 80% $tempImagePath";
            break;
        case 'negate':
            $command = "convert $tempImagePath -negate $tempImagePath";
            break;
        case 'despeckle':
            $command = "convert $tempImagePath -despeckle $tempImagePath";
            break;
        case 'edge':
            $command = "convert $tempImagePath -edge 5 $tempImagePath";
            break;
        case 'oil_painting':
            $command = "convert $tempImagePath -paint 5 $tempImagePath";
            break;
        default:
            $command = "convert $tempImagePath $tempImagePath";
            break;
    }

    // Execute the ImageMagick command
    exec($command, $output, $status);

    // Check if the command was successful
    if ($status === 0) {
        // Read the modified image data
        $modifiedImageData = file_get_contents($tempImagePath);

        // Encode the modified image data to base64
        $modifiedImageBase64 = base64_encode($modifiedImageData);

        // Respond with the modified image data
        echo json_encode(['previewImage' => 'data:image/jpeg;base64,' . $modifiedImageBase64]);
    } else {
        echo json_encode(['error' => 'Error applying the filter.']);
    }

    // Delete the temporary image file
    unlink($tempImagePath);
    exit();
} else {
    echo json_encode(['error' => 'Invalid request method.']);
    exit();
}
?>
