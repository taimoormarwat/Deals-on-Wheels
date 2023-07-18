<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./script/ads.js"></script>
    <script src="./script/auth.js"></script>
    <script src="./script/toast.js"></script>
    <style>
        .carousel-inner {
            max-height: 400px;
            max-width: 600px;
            border-radius: 20px;
        }
    </style>
</head>

<body>
    <!-- Nav Bar -->
    <?php include './components/narvbar.php'; ?>

    <div id="toastContainer" aria-live="polite" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>


    <center>
        <div class="container mt-5">
            <div id="images" class="carousel slide carousel-dark" data-bs-ride="carousel">
                <div class="carousel-inner"></div>
            </div>
        </div>
    </center>

    <div class="container mt-5">
        <div id='ad'></div>
    </div>

    <script>

        $(document).ready(function() {
            getName();
            // checkStatus();

            imgs = getImagesFor('');

            imgs.then((result) => {
                displayImagesInCarousel(result.images);
            }).catch((error) => {
                console.log('Error:', error);
            });

            data = getAds('id', localStorage.getItem('adId'));
            data.then((result) => {
                const ad = result.ads[0];
                displayAdDetails(ad);
            }).catch((error) => {
                console.log('Error:', error);
            });
        });
    </script>
</body>

</html>