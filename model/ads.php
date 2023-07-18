
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
    if ($_POST['action'] == 'getads') {

        $conn = connect_database();

        $field = $_POST['field'];
        $condition = $_POST['value'];
        $search = $_POST['search'];

        if ($condition == 'email') {
            $condition = $_SESSION['email'];
        }

        try {

            if ($search != '') {
                $query = "SELECT * FROM ads WHERE status = 1 AND title LIKE '%$search%'";
            } else {

                if ($field != '') {
                    // $query = "SELECT * FROM ads WHERE uploader='".$_SESSION['email']."'";

                    $query = 'SELECT * FROM ads WHERE ' . $field . "='" . $condition . "'";
                } else {
                    $query = "SELECT * FROM ads";
                }
            }

            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $ads = array();

                while ($row = $result->fetch_assoc()) {
                    $ads[] = $row;
                }

                $json = json_encode($ads);
                $response = array('status' => true, 'ads' => $ads);
                echo json_encode($response);
            } else {
                $response = array('status' => false, 'message' => 'No ads Found');
                echo json_encode($response);
            }
        } catch (Exception $e) {
            $response = array('status' => false, 'message' => $e->getMessage());
            echo json_encode($response);
        }
    }


    if ($_POST['action'] == 'getImages') {

        $conn = connect_database();

        $car_id = $_POST['adId'];

        try {


            $query = 'SELECT * FROM images where car_id=' . $car_id;

            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $images = array();

                while ($row = $result->fetch_assoc()) {
                    $images[] = $row;
                }

                $json = json_encode($images);
                $response = array('status' => true, 'images' => $images);
                echo json_encode($response);
            } else {
                $response = array('status' => false, 'message' => 'No images Found');
                echo json_encode($response);
            }
        } catch (Exception $e) {
            $response = array('status' => false, 'message' => $e->getMessage());
            echo json_encode($response);
        }
    }

    if ($_POST['action'] == 'changeStatus') {

        $conn = connect_database();

        $car_id = $_POST['adId'];
        $status = $_POST['status'];

        try {

            $query = "UPDATE ads
            SET status = '$status'
            WHERE id = $car_id";

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

    if ($_POST['action'] == 'getSeller') {

        $conn = connect_database();

        $uploader = $_POST['uploader'];

        try {
            $query = 'SELECT * FROM user where email="' . $uploader . '"';

            $result = $conn->query($query);

            $row = $result->fetch_assoc();

            $response = array('status' => true, 'contact' => $row['contact'], 'name' => $row['name']);
            echo json_encode($response);
        } catch (Exception $e) {
            $response = array('status' => false, 'message' => $e->getMessage());
            echo json_encode($response);
        }
    }

    if ($_POST['action'] == 'sendOffer') {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $offer = $_POST['offer'];
        $contact = $_POST['contact'];
        $car_id = $_POST['car_id'];
        $car_owner_email=$_POST['car_owner_email'];
        $ad_title=$_POST['ad_title'];



        $conn = connect_database();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }


        try {

            $query = "INSERT INTO offers (email,name,contact,offer,car_id,owner_email,ad_title) VALUES (?, ?,?,?,?,?,?)";
            $statement = $conn->prepare($query);


            $statement->bind_param('sssiiss', $email, $name, $contact, $offer, $car_id, $car_owner_email,$ad_title);

            if ($statement->execute()) {

                $response = array('status' => true, 'message'=>'Offer Sent');
                echo json_encode($response);
            } else {
                $response = array('status' => false, "message" => 'An Error Occured');
                echo json_encode($response);
            }
        } catch (Exception $e) {
                $response = array('status' => false, "message" => $e->getMessage());
                echo json_encode($response);

        }
    }

    if ($_POST['action'] == 'getOffers') {

        $conn = connect_database();



        try {


            $query = "SELECT * FROM offers WHERE owner_email='".$_SESSION['email']."'";

            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $ads = array();

                while ($row = $result->fetch_assoc()) {
                    $ads[] = $row;
                }

                $json = json_encode($ads);
                $response = array('status' => true, 'offers' => $ads);
                echo json_encode($response);
            } else {
                $response = array('status' => false, 'message' => 'No Offers Found');
                echo json_encode($response);
            }
        } catch (Exception $e) {
            $response = array('status' => false, 'message' => $e->getMessage());
            echo json_encode($response);
        }
    }
}
