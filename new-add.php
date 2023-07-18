<!DOCTYPE html>
<html>

<head>
    <title>New Add</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    
    <script src="./script/auth.js"></script>
</head>
<style>
    .preview-image {
        width: 200px;
        height: 200px;
        object-fit: cover;
        margin: 10px;
    }
    </style>

<body>
    
    <!-- Nav Bar -->
    <?php include './components/narvbar.php'; ?>
    
    <center>
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id='toast'>
            <div class="toast-body">
                Your Ad will be published once the Admin approves it.
                <div class="mt-2 pt-2 border-top">
                    <button type="button" onclick="redirect()" class="btn btn-primary btn-sm">Sure</button>
                </div>
            </div>
        </div>
    </center>

    <div class="container mt-5">
        <h2>New Ad</h2>
        <form enctype="multipart/form-data" id='form'>
            <div class="row align-items-start">
                <div class="col">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="make" class="form-label">Make</label>
                        <input type="text" class="form-control" id="make" name="make" required>
                    </div>
                </div>
            </div>


            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>


            <div class="mb-3">
                <label for="pictures" class="form-label">Select Pictures:</label>
                <input type="file" class="form-control" id="pictures" name="pictures[]" multiple>
            </div>

            <div id="preview-container" class="preview-container">
                <!-- Images will be dynamically added here -->
            </div>
            <button type="submit" class="btn btn-primary">Post Ad</button>
        </form>
    </div>





    <script>
        function redirect(){
            location.href = 'dashboard_user.php';
        }
        $(document).ready(function() {
            checkStatus();
            getName();
            $('#pictures').on('change', function() {
                var files = $(this)[0].files;
                $('#preview-container').empty(); // Clear previous previews

                for (var i = 0; i < files.length; i++) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var img = $('<img>').addClass('preview-image');
                        img.attr('src', e.target.result);
                        $('#preview-container').append(img);
                    };

                    reader.readAsDataURL(files[i]);
                }

                $('#preview-container').sortable({
                    containment: "parent",
                    tolerance: "pointer",
                    cursor: "move"
                });
            });



            $('form').submit(function(e) {
                e.preventDefault(); // Prevent form submission

                var formData = new FormData(this); // Create a new FormData object

                // Append the selected images to the FormData object
                $('#pictures').each(function() {
                    $.each($(this)[0].files, function(i, file) {
                        formData.append('images[]', file);
                    });
                });

                formData.append('action', 'newAd');
                formData.append('title', $('#title').val());
                formData.append('description', $('#description').val());
                formData.append('price', $('#price').val());
                formData.append('make', $('#make').val());



                // Make an AJAX request to save.php
                $.ajax({
                    type: 'POST',
                    url: './model/new-ad.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            $('.toast').toast('show');
                            setTimeout(function() {
                            window.location.href = 'dashboard_user.php';
                            }, 5000); 

                        }
                        // Handle the response from save.php
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        console.log(error);
                        // Handle any errors that occur during the AJAX request
                    }
                });
            });

        });
    </script>

</body>

</html>