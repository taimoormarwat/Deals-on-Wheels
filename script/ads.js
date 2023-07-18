async function getAds(field = '', value = '', search = '') {
    let data;
    await $.ajax({
        type: 'POST',
        url: './model/ads.php',
        data: { action: 'getads', field: field, value: value, search: search },
        dataType: 'json',
        success: function (response) {
            data = response;

        }
    });

    return data;
}

function createTable(ads) {

    const listingsSection = document.getElementById('listing');

    // Create a table element
    const table = document.createElement('table');
    table.classList.add('table', 'table-striped');

    // Create the table header
    const tableHead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    const headers = ['No.', 'Title', 'Price', 'Description', 'Make', 'Status', 'View', 'Edit'];

    for (const header of headers) {
        const th = document.createElement('th');
        th.scope = 'col';
        th.textContent = header;
        headerRow.appendChild(th);
    }

    tableHead.appendChild(headerRow);
    table.appendChild(tableHead);

    // Create the table body
    const tableBody = document.createElement('tbody');

    ads.forEach((ad, index) => {
        const row = document.createElement('tr');

        const noCell = document.createElement('td');
        noCell.textContent = index + 1;
        row.appendChild(noCell);

        const titleCell = document.createElement('td');
        titleCell.textContent = ad.title;
        row.appendChild(titleCell);

        const priceCell = document.createElement('td');
        priceCell.textContent = ad.price;
        row.appendChild(priceCell);

        const descriptionCell = document.createElement('td');
        descriptionCell.textContent = ad.description;
        row.appendChild(descriptionCell);

        const makeCell = document.createElement('td');
        makeCell.textContent = ad.make;
        row.appendChild(makeCell);

        const statusCell = document.createElement('td');
        if (ad.status == 0) {
            statusCell.textContent = 'Pending';
        }
        if (ad.status == 1) {
            statusCell.textContent = 'Approved';
        }
        row.appendChild(statusCell);

        const viewCell = document.createElement('td');
        const viewLink = document.createElement('a');
        viewLink.href = 'view.php';
        viewLink.textContent = 'View';
        viewLink.addEventListener('click', function () {
            localStorage.setItem('adId', ad.id);
        });
        viewCell.appendChild(viewLink);
        row.appendChild(viewCell);

        const editCell = document.createElement('td');
        const editLink = document.createElement('a');
        editLink.href = 'edit.php'; // Set the href without the ad.id
        editLink.textContent = 'Edit';
        editLink.addEventListener('click', function () {
            localStorage.setItem('adId', ad.id); // Store the ad.id in localStorage
        });
        editCell.appendChild(editLink);
        row.appendChild(editCell);


        tableBody.appendChild(row);
    });

    table.appendChild(tableBody);

    // Append the table to the listings section
    listingsSection.appendChild(table);
}

function createTableForAdmin(ads) {

    const listingsSection = document.getElementById('listing');

    // Create a table element
    const table = document.createElement('table');
    table.classList.add('table', 'table-striped');
    const style = document.createElement('style');
    style.textContent = `
      .table td {
        max-width: 180px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
    `;
    table.appendChild(style);

    // Create the table header
    const tableHead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    const headers = ['No.', 'Title', 'Price', 'Description', 'Make', 'Uploader', 'View', 'Status'];

    for (const header of headers) {
        const th = document.createElement('th');
        th.scope = 'col';
        th.textContent = header;
        headerRow.appendChild(th);
    }

    tableHead.appendChild(headerRow);
    table.appendChild(tableHead);

    // Create the table body
    const tableBody = document.createElement('tbody');

    ads.forEach((ad, index) => {
        const row = document.createElement('tr');

        const noCell = document.createElement('td');
        noCell.textContent = index + 1;
        row.appendChild(noCell);

        const titleCell = document.createElement('td');
        titleCell.textContent = ad.title;
        row.appendChild(titleCell);

        const priceCell = document.createElement('td');
        priceCell.textContent = ad.price;
        row.appendChild(priceCell);

        const descriptionCell = document.createElement('td');
        descriptionCell.textContent = ad.description;
        row.appendChild(descriptionCell);

        const makeCell = document.createElement('td');
        makeCell.textContent = ad.make;
        row.appendChild(makeCell);

        const uploader = document.createElement('td');
        uploader.textContent = ad.uploader;
        row.appendChild(uploader);

        const viewCell = document.createElement('td');
        const viewLink = document.createElement('a');
        viewLink.href = 'view.php';
        viewLink.textContent = 'View';
        viewLink.addEventListener('click', function () {
            localStorage.setItem('adId', ad.id);
        });
        viewCell.appendChild(viewLink);
        row.appendChild(viewCell);

        const statusCell = document.createElement(`button`);
        statusCell.classList.add('btn', 'btn-primary');
        statusCell.setAttribute('type', 'button');
        statusCell.setAttribute('id', 'modalButton');

        let inverse = '';
        let inverseStatus = 0;
        if (ad.status == 0) {
            inverseStatus = 1;
            statusCell.textContent = 'Pending';
            inverse = 'Approve';
        } else if (ad.status == 1) {
            inverseStatus = 0;
            statusCell.textContent = 'Approved';
            inverse = 'Disapprove';
        }

        statusCell.addEventListener('click', function () {
            // Create the modal elements
            const modal = document.createElement('div');
            modal.classList.add('modal');
            modal.setAttribute('tabindex', '-1');
            modal.setAttribute('id', 'statusModal');

            const modalDialog = document.createElement('div');
            modalDialog.classList.add('modal-dialog');

            const modalContent = document.createElement('div');
            modalContent.classList.add('modal-content');

            const modalHeader = document.createElement('div');
            modalHeader.classList.add('modal-header');

            const modalTitle = document.createElement('h5');
            modalTitle.classList.add('modal-title');
            modalTitle.textContent = 'Change Status';

            const closeButton = document.createElement('button');
            closeButton.classList.add('btn-close');
            closeButton.setAttribute('type', 'button');
            closeButton.setAttribute('data-bs-dismiss', 'modal');
            closeButton.setAttribute('aria-label', 'Close');

            const modalBody = document.createElement('div');
            modalBody.classList.add('modal-body');
            modalBody.setAttribute('id', 'modalBody');
            modalBody.textContent = 'The ad is currently ' + statusCell.textContent;

            const modalFooter = document.createElement('div');
            modalFooter.classList.add('modal-footer');

            const closeButtonFooter = document.createElement('button');
            closeButtonFooter.classList.add('btn', 'btn-secondary');
            closeButtonFooter.setAttribute('type', 'button');
            closeButtonFooter.setAttribute('data-bs-dismiss', 'modal');
            closeButtonFooter.textContent = 'Close';

            const modalButton = document.createElement('button');
            modalButton.classList.add('btn', 'btn-primary');
            modalButton.setAttribute('type', 'button');
            modalButton.setAttribute('id', 'modalButton');
            modalButton.textContent = inverse;

            // Create a spinner element
            const spinner = document.createElement('span');
            spinner.classList.add('spinner-border', 'spinner-border-sm', 'ms-2');
            spinner.setAttribute('role', 'status');
            spinner.setAttribute('aria-hidden', 'true');

            // Add a click event listener to the modal button
            modalButton.addEventListener('click', function () {
                // Disable the button
                modalButton.disabled = true;

                // Add the spinner to the button
                modalButton.appendChild(spinner);

                // Call the changeStatus function
                changeStatus(ad, inverseStatus);
            });



            modalHeader.appendChild(modalTitle);
            modalHeader.appendChild(closeButton);
            modalFooter.appendChild(closeButtonFooter);
            modalFooter.appendChild(modalButton);
            modalContent.appendChild(modalHeader);
            modalContent.appendChild(modalBody);
            modalContent.appendChild(modalFooter);
            modalDialog.appendChild(modalContent);
            modal.appendChild(modalDialog);

            // Append the modal to the document body
            document.body.appendChild(modal);

            // Show the modal
            bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();
        });





        row.appendChild(statusCell);




        // const editCell = document.createElement('td');
        // const editLink = document.createElement('a');
        // editLink.href = 'edit.php'; // Set the href without the ad.id
        // editLink.textContent = 'Edit';
        // editLink.addEventListener('click', function() {
        //     localStorage.setItem('adId', ad.id); // Store the ad.id in localStorage
        // });
        // editCell.appendChild(editLink);
        // row.appendChild(editCell);



        tableBody.appendChild(row);
    });

    table.appendChild(tableBody);

    // Append the table to the listings section
    listingsSection.appendChild(table);
}

function changeStatus(ad, status) {

    $.ajax({
        type: 'POST',
        url: './model/ads.php',
        data: { action: 'changeStatus', adId: ad.id, status: status },
        dataType: 'json',
        success: function (response) {
            console.log(response.message);
            $.ajax({
                type: 'POST',
                url: './model/mail.php',
                data: { action: 'sendMail', 'receiver': ad.uploader, 'status': status, 'title': ad.title },
                dataType: 'json',
                success: function (response) {


                    location.reload();
                    // console.log(ad.uploader);
                    // console.log(response.message);

                },
                error: function (xhr, error) {
                    console.log(xhr.responseText);
                    console.log(error);
                }
            });


        },
        error: function (xhr, error) {
            console.log(xhr.responseText);
            console.log(error);
        }
    });


}


function displayAdDetails(ad) {

    $.ajax({
        type: 'POST',
        url: './model/ads.php',
        data: { action: 'getSeller', uploader: ad.uploader, },
        dataType: 'json',
        success: function (response) {

            const listingsDiv = document.getElementById('ad');

            // Create a card container
            const cardContainer = document.createElement('div');
            cardContainer.classList.add('card', 'mb-3');

            // Create card body
            const cardBody = document.createElement('div');
            cardBody.classList.add('card-body');


            // Add ad title
            const adTitle = document.createElement('h5');
            adTitle.classList.add('card-title');
            adTitle.textContent = ad.title;
            cardBody.appendChild(adTitle);

            // Create button
            const sendOfferButton = document.createElement('button');
            sendOfferButton.classList.add('btn', 'btn-primary');
            sendOfferButton.textContent = 'Send Offer';

            sendOfferButton.addEventListener('click', () => sendOffer(ad));



            // Add button to the card body
            cardBody.appendChild(sendOfferButton);

            sendOfferButton.style.float = 'right';
            adTitle.style.display = 'inline-block';
            adTitle.style.marginRight = '10px';



            // Add ad price
            const adPrice = document.createElement('h6');
            adPrice.classList.add('card-text');
            adPrice.textContent = 'Price: ' + ad.price;
            cardBody.appendChild(adPrice);

            // name
            const name = document.createElement('p');
            name.classList.add('card-title');
            name.textContent = 'By: ' + response.name + ' (' + response.contact + ')';
            cardBody.appendChild(name);

            // Contact
            const Contact = document.createElement('br');
            // Contact.classList.add('card-title');
            // Contact.textContent = '';
            cardBody.appendChild(Contact);


            // Add ad description
            const adDescription = document.createElement('p');
            adDescription.classList.add('card-text');
            adDescription.textContent = ad.description;
            cardBody.appendChild(adDescription);

            // Append card body to card container
            cardContainer.appendChild(cardBody);

            // Append card container to listings div
            listingsDiv.appendChild(cardContainer);


        },
        error: function (xhr, error) {
            console.log(xhr.responseText);
            console.log(error);
        }
    });

}

function sendOffer(ad) {

        // Create the popup modal
        const modal = $('<div>', {
          class: 'modal fade',
          id: 'offerModal',
          tabindex: '-1',
          role: 'dialog',
          'aria-labelledby': 'offerModalLabel',
          'aria-hidden': 'true'
        });
      
        const modalDialog = $('<div>', {
          class: 'modal-dialog',
          role: 'document'
        });
      
        const modalContent = $('<div>', {
          class: 'modal-content'
        });
      
        // Modal header
        const modalHeader = $('<div>', {
          class: 'modal-header'
        });
      
        const modalTitle = $('<h5>', {
          class: 'modal-title',
          id: 'offerModalLabel',
          text: 'Send Offer'
        });
      
        const closeButton = $('<button>', {
          type: 'button',
          class: 'close',
          'data-dismiss': 'modal',
          'aria-label': 'Close'
        });
      
        const closeIcon = $('<span>', {
          'aria-hidden': 'true',
          html: '&times;'
        });
      
        closeButton.append(closeIcon);
        modalHeader.append(modalTitle, closeButton);
      
        // Modal body
        const modalBody = $('<div>', {
          class: 'modal-body'
        });
      
        const form = $('<form>');
      
        // Name field
        const nameLabel = $('<label>', {
          for: 'name',
          text: 'Name'
        });
      
        const nameInput = $('<input>', {
          type: 'text',
          id: 'name',
          class: 'form-control'
        });
      
        form.append(nameLabel, nameInput);
      
        // Email field
        const emailLabel = $('<label>', {
          for: 'email',
          text: 'Email'
        });
      
        const emailInput = $('<input>', {
          type: 'email',
          id: 'email',
          class: 'form-control'
        });
      
        form.append(emailLabel, emailInput);
      
        // Offer price field
        const priceLabel = $('<label>', {
          for: 'price',
          text: 'Offer Price'
        });
      
        const priceInput = $('<input>', {
          type: 'number',
          id: 'price',
          class: 'form-control'
        });
      
        form.append(priceLabel, priceInput);
      
        // Contact field
        const contactLabel = $('<label>', {
          for: 'contact',
          text: 'Contact'
        });
      
        const contactInput = $('<input>', {
          type: 'text',
          id: 'contact',
          class: 'form-control'
        });
      
        form.append(contactLabel, contactInput);
      
        modalBody.append(form);
      
        // Modal footer
        const modalFooter = $('<div>', {
          class: 'modal-footer'
        });
      
        const sendButton = $('<button>', {
          type: 'button',
          class: 'btn btn-primary',
          text: 'Send Offer'
        });
      
        modalFooter.append(sendButton);
      
        // Construct the modal structure
        modalContent.append(modalHeader, modalBody, modalFooter);
        modalDialog.append(modalContent);
        modal.append(modalDialog);
      
        // Add the modal to the document
        $('body').append(modal);
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
                        $('#contact').val(response.contact);
                        $('#email').val(response.email);


                    }


                } else {}

            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                console.log(error);
            }

        });
      
        // Show the modal
        $('#offerModal').modal('show');

      
        // Handle the "Send Offer" button click
        sendButton.on('click', function() {
          const name = $('#name').val();
          const email = $('#email').val();
          const price = $('#price').val();
          const contact = $('#contact').val();
      
          // Your code logic to handle the offer submission goes here
        //   console.log('Name:', name);
        //   console.log('Email:', email);
        //   console.log('Offer Price:', price);
        //   console.log('Contact:', contact);

        $.ajax({
            type: 'POST',
            url: './model/ads.php',
            data: { action: 'sendOffer', car_id: ad.id,name:name,email:email,contact:contact,offer:price,car_owner_email:ad.uploader,ad_title:ad.title},
            dataType: 'json',
            success: function (response) {
                console.log(response.message);    
                showToast(response.message);            
            }
        });
      
          // Close the modal
          $('#offerModal').modal('hide');
        });
      
      
  }

async function getImagesFor(imagesFor = '') {
    let id = imagesFor;

    if (imagesFor == '') {
        id = localStorage.getItem('adId');
    }

    let data;
    await $.ajax({
        type: 'POST',
        url: './model/ads.php',
        data: { action: 'getImages', adId: id },
        dataType: 'json',
        success: function (response) {
            data = response;
        }
    });

    return data;


}


function displayImagesInCarousel(imageUrls) {
    const carousel = document.getElementById('images');
    const carouselInner = document.createElement('div');
    carouselInner.classList.add('carousel-inner');

    // Add active class to the first slide
    let isFirstSlide = true;

    for (const imageUrl of imageUrls) {
        const slideElement = document.createElement('div');
        slideElement.classList.add('carousel-item');

        // Add active class to the first slide
        if (isFirstSlide) {
            slideElement.classList.add('active');
            isFirstSlide = false;
        }

        const imgElement = document.createElement('img');
        imgElement.src = './images/ads-images/' + imageUrl['url'];
        imgElement.classList.add('d-block', 'w-100');
        imgElement.style.objectFit = 'cover'; // Make the image cover the slide

        const anchorElement = document.createElement('a');
        anchorElement.href = imgElement.src;
        anchorElement.target = '_blank';
        anchorElement.appendChild(imgElement);

        slideElement.appendChild(anchorElement);
        carouselInner.appendChild(slideElement);
    }

    // Add carousel-inner to carousel
    carousel.appendChild(carouselInner);

    // Add navigation arrows
    carousel.innerHTML += `
        <button class="carousel-control-prev" type="button" data-bs-target="#images" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#images" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    `;

}

function createAdCard(ad) {

    let row = document.querySelector('.row');
    row.innerHTML = '';

    const imgs = getImagesFor(ad.id);
    imgs.then((images) => {
        let url = './images/logo.png';
        if (images.images != undefined) {
            url = './images/ads-images/' + images.images[0]['url'];
        }

        // Create card element
        const card = document.createElement('div');
        card.addEventListener('click', () => cardClick(ad.id));
        card.classList.add('col-md-4');

        // Create card content
        const cardContent = `
        <div class="card mb-3" style="max-width: 540px;">
          <div class="row g-0">
            <div style="position: relative; width: 40%;">
              <div style="padding-bottom: 100%; height: 0;">
                <img src="${url}" class="img-fluid rounded-start" style="object-fit: cover; position: absolute; top: 0; left: 0; width: 100%; height: 100%;" alt="${ad.title}">
              </div>
            </div>
            <div style="width: 60%;"> <!-- Width adjusted accordingly -->
              <div class="card-body">
                <h5 class="card-title">${ad.title}</h5>
                <p class="card-text">Rs. ${ad.price}</p>
                <p class="card-text" style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">${ad.description}</p>
              </div>
            </div>
          </div>
        </div>`;

        // Add card content to the card element
        card.innerHTML = cardContent;

        // Append the card to the row
        document.querySelector('.row').appendChild(card);
    }).catch((error) => {
        console.log('Error:', error);
    });
}

function cardClick(id) {
    localStorage.setItem('adId', id);
    location.href = 'view.php';

}

function createTableForOffers(ads) {

    const listingsSection = document.getElementById('listing');

    // Create a table element
    const table = document.createElement('table');
    table.classList.add('table', 'table-striped');

    // Create the table header
    const tableHead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    const headers = ['No.', 'From', 'Email','Ad', 'Offer', 'Contact'];

    for (const header of headers) {
        const th = document.createElement('th');
        th.scope = 'col';
        th.textContent = header;
        headerRow.appendChild(th);
    }

    tableHead.appendChild(headerRow);
    table.appendChild(tableHead);

    // Create the table body
    const tableBody = document.createElement('tbody');

    ads.forEach((ad, index) => {
        const row = document.createElement('tr');

        const noCell = document.createElement('td');
        noCell.textContent = index + 1;
        row.appendChild(noCell);

        const titleCell = document.createElement('td');
        titleCell.textContent = ad.name;
        row.appendChild(titleCell);

        const priceCell = document.createElement('td');
        priceCell.textContent = ad.email;
        row.appendChild(priceCell);

        const adCell = document.createElement('td');
        adCell.textContent = ad.ad_title;
        row.appendChild(adCell);

        const descriptionCell = document.createElement('td');
        descriptionCell.textContent = ad.contact;
        row.appendChild(descriptionCell);

        const makeCell = document.createElement('td');
        makeCell.textContent = ad.offer;
        row.appendChild(makeCell);

        tableBody.appendChild(row);
    });

    table.appendChild(tableBody);

    // Append the table to the listings section
    listingsSection.appendChild(table);
}

async function getOffers() {
    let data;
    await $.ajax({
        type: 'POST',
        url: './model/ads.php',
        data: { action: 'getOffers', },
        dataType: 'json',
        success: function (response) {
            data = response;
        }
    });

    return data;
}