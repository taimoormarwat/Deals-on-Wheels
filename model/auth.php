<?php

// passwordistricky12

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

function saveImage($image, $name)
{
    $uploadDir = '../images/';
    $fileExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
    $uploadFile = $uploadDir . $name . '.' . $fileExtension;

    try {
        if (!isset($image['tmp_name']) || !is_uploaded_file($image['tmp_name'])) {
            throw new Exception('Invalid image file');
        }

        if (!is_dir($uploadDir)) {
            throw new Exception('Upload directory does not exist');
        }

        $success = move_uploaded_file($image['tmp_name'], $uploadFile);

        if (!$success) {
            throw new Exception('Failed to move uploaded file');
        }

        return $name . '.' . $fileExtension;
    } catch (Exception $e) {
        // Handle the exception
        return '';
    }
}




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] == 'login') {
        $pass = $_POST["password"];
        $email = $_POST["email"];
        $response = array('status' => false);


        $conn = connect_database();


        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM user where email='" . $email . "'";
        $result = $conn->query($sql);

        // echo '<br> username:' . $result->fetch_assoc()['username'];

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['password'] == $pass && $row['status'] == 1) {
                $response = array('status' => false, 'message' => 'Account Blocked');
                echo json_encode($response);
            } elseif ($row['password'] == $pass) {
                $name = $row['name'];
                $role = $row['role'];

                $_SESSION['token'] = password_hash(session_id(), PASSWORD_DEFAULT);
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;
                $_SESSION['name'] = $name;
                $_SESSION['img'] = $row['img'];
                $_SESSION['contact'] = $row['contact'];


                $response = array('status' => true, "name" => $name, 'token' => $_SESSION['token']);
                echo json_encode($response);
            } else {
                $response = array('status' => false, 'message' => 'Incorrect Credentials');
                echo json_encode($response);
            }
        } else {
            $response = array('status' => false, 'message' => 'Incorrect Credentials');
            echo json_encode($response);
        }
    }

    if ($_POST['action'] == 'confirmLogin') {

        if ($_POST['token'] == $_SESSION['token']) {
            $response = array('status' => true);
            echo json_encode($response);
        } else {
            $response = array('status' => false);
            echo json_encode($response);
        }
    }

    if ($_POST['action'] == 'getName') {
        if (isset($_SESSION['name'])) {
            $response = array('status' => true, 'name' => $_SESSION['name'], 'role' => $_SESSION['role'], 'img' => $_SESSION['img'], 'contact' => $_SESSION['contact'],'email' => $_SESSION['email']);
            echo json_encode($response);
        } else {
            $response = array('status' => false, 'name' => $_SESSION['name']);
            echo json_encode($response);
        }
    }


    if ($_POST['action'] == 'signup') {
        $pass = $_POST["password"];
        $email = $_POST["email"];
        $name = $_POST['name'];
        $role = $_POST['role'];
        $contact = $_POST['contact'];



        $conn = connect_database();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $imageUrl = '';

        if (isset($_FILES['profilePicture'])) {
            $imageUrl = saveImage($_FILES['profilePicture'], $email);
        }



        if ($imageUrl == '') {
            $imageUrl = 'logo.png';
        }




        try {

            $query = "INSERT INTO user (email, password,name,role,img,contact) VALUES (?, ?,?,?,?,?)";
            $statement = $conn->prepare($query);


            $statement->bind_param('ssssss', $email, $pass, $name, $role, $imageUrl, $contact);

            if ($statement->execute()) {


                $_SESSION['token'] = password_hash(session_id(), PASSWORD_DEFAULT);
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;
                $_SESSION['name'] = $name;
                $_SESSION['img'] = $imageUrl;

                $response = array('status' => true, "name" => $name, 'token' => $_SESSION['token']);
                echo json_encode($response);
            } else {
                $response = array('status' => false, "message" => 'An Error Occured');
                echo json_encode($response);
            }
        } catch (Exception $e) {
            if ($e->getCode() == 1062) {
                $response = array('status' => false, "message" => 'Email already in Use');
                echo json_encode($response);
            } else {
                $response = array('status' => false, "message" => $e->getMessage());
                echo json_encode($response);
            }
        }
    }

    if ($_POST['action'] == 'profileSave') {
        $name = $_POST['name'];
        $contact = $_POST['contact'];

        $email=$_SESSION['email'];



        $conn = connect_database();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $imageUrl = '';

        if (isset($_FILES['profilePicture'])) {
            $imageUrl = saveImage($_FILES['profilePicture'], $email);
        }



        if ($imageUrl == '') {
            $imageUrl = $_SESSION['img'];
        }





        try {

            $sql = "UPDATE user SET name = '$name', contact = '$contact', img = '$imageUrl' WHERE email = '$email'";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['img']=$imageUrl;
                $_SESSION['contact']=$contact;
                $_SESSION['name']=$name;

                $response = array('status' => true, "message" => 'Profile Updated');
                echo json_encode($response);
            } else {
                $response = array('status' => false, "message" => "Profile couldn't be Updated");
                echo json_encode($response);
            }
        } catch (Exception $e) {
            $response = array('status' => false, "message" => "Profile couldn't be Updated");
            echo json_encode($response);
        }
    }
}
