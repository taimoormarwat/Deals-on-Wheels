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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] == 'getUsers') {

        $conn = connect_database();

        $field = $_POST['field'];
        $condition = $_POST['value'];
        $search = $_POST['search'];


        try {


            $query = "SELECT * FROM user";

            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $users = array();

                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }

                $json = json_encode($users);
                $response = array('status' => true, 'users' => $users);
                echo json_encode($response);
            } else {
                $response = array('status' => false, 'message' => 'No users Found');
                echo json_encode($response);
            }
        } catch (Exception $e) {
            $response = array('status' => false, 'message' => $e->getMessage());
            echo json_encode($response);
        }
    }

    if ($_POST['action'] == 'changeStatus') {

        $conn = connect_database();

        $userEmail = $_POST['userEmail'];
        $status = $_POST['status'];

        try {

            $query = "UPDATE user
            SET status = '$status'
            WHERE email = '$userEmail'";

            if ($conn->query($query) === TRUE) {
                $response = array('status' => true, 'message' => 'Updated Successfully');
                echo json_encode($response);
            } else {
                $response = array('status' => false, 'message' => "Couldn't be updated");
                echo json_encode($response);
            }
        } catch (Exception $e) {
            $response = array('status' => false, 'message' => $e->getMessage());
            echo json_encode($response);
        }
    }

}