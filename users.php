<?php

session_start();

if ($_SESSION['role'] != 'admin') {
    echo '<center><h2>Access Denied</h2></center>';
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./script/users.js"></script>
    <script src="./script/auth.js"></script>
</head>

<body>

    <!-- Nav Bar -->
    <?php include './components/narvbar.php'; ?>


    <div class="modal" tabindex="-1" id='statusModal'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id='modalBody'>
                    <p>Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id='modalButton'>Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div id='listing'></div>
    </div>



    <!-- Footer -->
    <?php include './components/footer.php'; ?>

    <script>
        $(document).ready(function() {
            getName();
            checkStatus();

            data = getUsers();
            data.then((result) => {
                    if (result.status) {
                        createUserTable(result.users);

                    }
                })
                .catch((error) => {
                    console.log('Error:', error);
                });

        });
    </script>

</body>

</html>