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
  <style>
    .carousel-inner {
      max-height: 400px;
      max-width: 600px;
      border-radius: 20px;
    }

    .card:hover {
      transform: scale(1.05);
    }

    .card {
      transition: transform 0.3s;
    }

    .search-bar {
      max-width: 500px;
    }
  </style>
</head>

<body>
  <!-- Nav Bar -->
  <?php include './components/narvbar.php'; ?>

  <center>
    <div class="container mt-5">
      <input type="text" class="form-control" style="max-width:500px;" placeholder="Search..." id='search-bar'>
    </div>
  </center>

  <center>
    <div id="container" class="container mt-5">
      <div class="row"></div>
    </div>
  </center>

  <div class="container mt-5">
    <div id='ad'></div>
  </div>

  <script>
    getName();


    $(document).ready(function() {
      var debounceTimer;


      $('#search-bar').keyup(function(e) {

        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(function() {

          search_value = $('#search-bar').val();
          console.log('Search value:', search_value);
          data = getAds('status', '1', search_value);
          data.then((result) => {
            if (result.status) {
              console.log(result.ads);
              result.ads.forEach((ad, index) => {
                createAdCard(ad);
              });
            }else{
              let row = document.querySelector('.row');
              row.innerHTML = '';
              row.innerHTML = '<h6>No Ads found for this search</h6>';

            }
          }).catch((error) => {
            console.log('Error:', error);
          });


        }, 600);

      });

      data = getAds('status', '1');
      data.then((result) => {
        console.log(result.ads);
        result.ads.forEach((ad, index) => {
          createAdCard(ad);
        });
      }).catch((error) => {
        console.log('Error:', error);
      });
    });
  </script>
</body>

</html>