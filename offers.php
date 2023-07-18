<!DOCTYPE html>
<html>

<head>
    <title>Offers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./script/ads.js"></script>
    <script src="./script/auth.js"></script>
</head>

<body>

    <!-- Nav Bar -->
    <?php include './components/narvbar.php'; ?>


    <div class="container mt-5">
    <div id='listing'></div>
    </div>



    <!-- Footer -->
    <?php include './components/footer.php'; ?>

    <script>
        
        $(document).ready(function() {
            getName();
            checkStatus();

            data = getOffers();
            data.then((result) => {
                    // console.log('Success:', result.ads);

                    if(result.status){
                        createTableForOffers(result.offers);
                    }
                    else{
                        $('#listing').html('<center><h6>Your Offers will appear here.</h6></center>');

                    }
                })
                .catch((error) => {
                    console.log('Error:', error);
                });

        });
    </script>

</body>

</html>