<!DOCTYPE html>
<html>

<head>
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="./script/toast.js"></script>
    <script src="./script/auth.js"></script>

</head>
<style>
    .container-full-height {
        height: 90vh;
        /* background-color: grey; */
    }

    .round-image {
        height: 100px;
        background-color: black;
        border-radius: 50%;
        max-width: 100px;
        object-fit: cover;
    }
</style>

<body>
    <!-- Nav Bar -->
    <?php include './components/narvbar.php'; ?>

    <div id="toastContainer" aria-live="polite" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

    <div class="container mt-5">
        <div class="row justify-content-center align-items-center h-100">
            <div class="card w-75 mb-3">
                <div class="card-body text-center">
                    <br>
                    <h5 class="card-title">Profile</h5>
                    <br>
                    <form class="form-floating" id="profileForm">
                        <img id="profileImage" src="./images/logo.png" class="round-image" alt="Profile Picture">
                        <br>
                        <input type="file" id="profilePicture" accept="image/*" onchange="previewProfileImage(event)">
                        <br>
                        <br>

                        <div class="col-md">
                            <div class="row">
                                <div class="col">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="name" required>
                                        <label for="name">Name</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" id="tel" required>
                                        <label for="tel">Contact</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <br>
                        <div class="col-md">
                            <div class="row">

                                <div class="col-md">
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="oldPassword" required>
                                        <label for="oldPassword">Old Password</label>
                                    </div>
                                </div>

                                <div class="col-md">
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="newPassword" required>
                                        <label for="newPassword">New Password</label>
                                    </div>
                                </div>


                            </div>
                        </div>
 -->


                        <br>
                        <input type="submit" class="btn btn-primary" name="signup" id="signup" value="Save">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewProfileImage(event) {
            const profileImage = document.getElementById('profileImage');
            profileImage.src = URL.createObjectURL(event.target.files[0]);
        }

        $(document).ready(function() {
            getName();

            var formData = new FormData();
            // Append other form data to the FormData object
            formData.append('action', 'getName');
            $.ajax({
                type: 'POST',
                url: './model/auth.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status) {


                        if (localStorage.getItem('token')) {

                            $('#name').val(response.name);
                            $('#tel').val(response.contact);
                            $('#profileImage').attr('src', '../dealsonwheels/images/' + response.img);


                        }


                    } else {}

                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    console.log(error);
                }

            });


            $('#profileForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData();

                if ($('#profilePicture')[0].files[0] != undefined) {
                    formData.append('profilePicture', $('#profilePicture')[0].files[0]);
                }

                // Append other form data to the FormData object
                formData.append('action', 'profileSave');
                formData.append('name', $('#name').val());
                formData.append('contact', $('#tel').val());

                $.ajax({
                    type: 'POST',
                    url: './model/auth.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            console.log(response.message);
                            location.reload();
                            showToast(response.message);
                        } else {
                            showToast(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        console.log(error);
                    }
                });
            });
        });
    </script>

</html>