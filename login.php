<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="./script/toast.js"></script>
</head>
<style>
    .container-full-height {
        height: 90vh;
    }
</style>

<body>

    <!-- Nav Bar -->
    <?php include './components/narvbar.php'; ?>

    <div id="toastContainer" aria-live="polite" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>


    <div class="container-fluid container-full-height">
        <div class="row justify-content-center align-items-center h-100">
            <div class="card w-25 mb-3">
                <div class="card-body text-center">
                    <br>
                    <img src="./images/logo.png" style="max-width: 200px;" alt="...">
                    <br>
                    <h5 class="card-title">Welcome back!</h5>
                    <br>
                    <form class="form-floating" id='loginForm'>


                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" placeholder="name@example.com" required>
                            <label for="email">Email address</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" placeholder="Password" required>
                            <label for="password">Password</label>
                        </div>

                        <br>
                        <input type="submit" class="btn btn-primary" name="loginButton" id="loginButton" value='Log In'>

                    </form>
                    <br>
                    <p class="card-text">Don't have an account?<button type="button" class="btn btn-link" id='signup'>Sign Up</button></p>
                    <br>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <?php include './components/footer.php'; ?>
</body>

<script>
    $(document).ready(function() {

        if(localStorage.getItem('token')){
            console.log('token found, redirecting to home');



            $.ajax({
            type: 'POST',
            url: './model/auth.php',
            data: { action: 'confirmLogin',token:localStorage.getItem('token') }, // Specify the function to call
            dataType: 'json',
            success: function(response) {
                if(response.status){
                    location.href='home.php';
                }else{
                    console.log(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
        }

        $('#signup').click(function(e) {
            e.preventDefault();
            location.href='signup.php';
        });


        $('#loginForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: './model/auth.php',
                data: {
                    action: 'login',
                    email: $('#email').val(),
                    password: $('#password').val()
                },
                dataType: 'json',
                success: function(response) {
                    if(response.status){
                        localStorage.setItem('token',response.token);
                        location.href='home.php';
                    }else{
                        console.log(response.message);
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