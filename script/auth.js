function getName() {
  console.log('here');
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

                // $('#account').text(response.name);
                $('#accountName').text(response.name);
                $('#accountImage').attr('src', '../dealsonwheels/images/'+response.img);

                // $('#usericon').attr('href', 'dashboard_user.php');
                  
                $('#dropdownMenu').empty();

                addDropdownItem('Profile', '../dealsonwheels/profile.php');                  
                addDropdownItem('Offers', '../dealsonwheels/Offers.php');                  

                if(response.role=='admin'){
                  addDropdownItem('Dashboard', '../dealsonwheels/dashboard_admin.php');                  
                  addDropdownItem('Users', '../dealsonwheels/users.php');                  
                }else{
                  addDropdownItem('Dashboard', '../dealsonwheels/dashboard_user.php');
                }


                var newItem = $('<li><a class="dropdown-item" href="' + '../dealsonwheels/dashboard_user.php' + '">' + 'Logout' + '</a></li>');
                newItem.find('a').click(logout);
                $('#dropdownMenu').append(newItem);
          }
          
          
        } else {
          // $('#username').text(response.name);
        }

      },
      error: function(xhr, status, error) {
        console.log(xhr.responseText);
        console.log(error);
      }

    });

  }

  function addDropdownItem(text, link) {
    var newItem = $('<li><a class="dropdown-item" href="' + link + '">' + text + '</a></li>');
    $('#dropdownMenu').append(newItem);
  }


  function logout(){
    console.log('Logout Pressed');
    localStorage.removeItem('token');
    $('#dropdownMenu').empty();

    addDropdownItem('Login', '../dealsonwheels/login.php');
    addDropdownItem('Sign Up', '../dealsonwheels/signup.php');
    location.href='../dealsonwheels/home.php'
  }

  function checkStatus() {

    console.log('Check Status called...');

    if (localStorage.getItem('token')) {

      $.ajax({
        type: 'POST',
        url: './model/auth.php',
        data: {
          action: 'confirmLogin',
          token: localStorage.getItem('token')
        }, // Specify the function to call
        dataType: 'json',
        success: function(response) {
          if (!response.status) {
            location.href = 'login.php';
          }
        },
        error: function(xhr, status, error) {

          console.log(xhr.responseText);
        }
      });
    } else {
      location.href = 'login.php';
    }
  }