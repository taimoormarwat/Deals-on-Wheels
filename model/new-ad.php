<?php
session_start();
function connect_database()
{

    $servername = "localhost";
    $username = "mamp";
    $password = "root";
    $database = "dealsonwheels";


    $conn = new mysqli($servername, $username, $password, $database);

    return $conn;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] == 'newAd') {




        $conn = connect_database();

        // Retrieve form data
        $title = $_POST['title'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $make = $_POST['make'];
        $email = $_SESSION["email"];
        $views = '0';

        // Prepare and execute the SQL statement to insert car data
        $stmt = mysqli_prepare($conn, "INSERT INTO ads (title, price, description,views,uploader,make) VALUES (?, ?, ?,?,?,?)");
        mysqli_stmt_bind_param($stmt, "ssssss", $title, $price, $description, $views, $email, $make);
        mysqli_stmt_execute($stmt);

        // Get the last inserted car ID
        $carId = mysqli_insert_id($conn);

        $images = $_FILES['pictures'];

        for ($i = 0; $i < count($images['name']); $i++) {
            $imageTmpName = $images['tmp_name'][$i];
            if ($images['name'][$i]!='') {
                $imageFileName = $images['name'][$i];

                // Generate a unique filename for each image
                $url = uniqid() . '_' . $imageFileName;
                $imagePath = '../images/ads-images/' . $url;

                move_uploaded_file($imageTmpName, $imagePath);

                // Insert the image file path into the database
                $stmt = mysqli_prepare($conn, "INSERT INTO images (car_id, url) VALUES (?, ?)");
                mysqli_stmt_bind_param($stmt, "is", $carId, $url);
                mysqli_stmt_execute($stmt);
            }
        }

        mysqli_close($conn);

        $response = ['status' => true, 'message' => 'Car data and images saved'];
        echo json_encode($response);
    }
}
