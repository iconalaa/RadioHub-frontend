<?php
// Get the image data and image name from the POST request
$imageData = $_POST['imageData'];
$imageName = $_POST['imageName'];

// Decode the image data
$imageDataDecoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));

// Set the file path to save the image
$imagePath = __DIR__ . '/uploads/images/' . $imageName;

// Save the image to the specified path
if (file_put_contents($imagePath, $imageDataDecoded) !== false) {
    echo 'Image saved successfully.';
} else {
    echo 'Error saving image.';
}
?>