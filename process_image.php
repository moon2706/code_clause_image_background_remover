<?php
$targetDir = "uploads/";
$targetFile = $targetDir . basename($_FILES["image"]["name"]);
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
$uploadOk = 1;

// Check if the file is an image
if (isset($_POST["submit"])) {
  $check = getimagesize($_FILES["image"]["tmp_name"]);
  if ($check !== false) {
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
}

// Check if file already exists
if (file_exists($targetFile)) {
  echo "File already exists.";
  $uploadOk = 0;
}

// Check file size (optional)
if ($_FILES["image"]["size"] > 5000000) {
  echo "File is too large.";
  $uploadOk = 0;
}

// Allow only specific file formats (e.g., jpg, png)
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
  echo "Only JPG, JPEG, and PNG files are allowed.";
  $uploadOk = 0;
}

// Move the uploaded file to the target directory
if ($uploadOk == 1) {
  if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
    // Execute the Python script passing the image file path
    $command = "python background_removal.py " . $targetFile;
    $output = shell_exec($command);
    
    // Display the resulting image
    echo "<h2>Result:</h2>";
    echo "<img src='uploads/output.png'>";
  } else {
    echo "Error uploading the file.";
  }
}
?>
