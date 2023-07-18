async function getUsers(field = '',value='',search='') {
    let data;
    await $.ajax({
        type: 'POST',
        url: './model/users.php',
        data: { action: 'getUsers', field:field, value:value,search:search },
        dataType: 'json',
        success: function (response) {
            data = response;
        }
    });

    return data;
}

function createUserTable(users) {

    const listingsSection = document.getElementById('listing');

    // Create a table element
    const table = document.createElement('table');
    table.classList.add('table', 'table-striped');

    // Create the table header
    const tableHead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    const headers = ['No.','Name', 'Email', 'Contact', 'Status'];

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

    users.forEach((user, index) => {
        const row = document.createElement('tr');

        const noCell = document.createElement('td');
        noCell.textContent = index+1;
        row.appendChild(noCell);

        const titleCell = document.createElement('td');
        titleCell.textContent = user.name;
        row.appendChild(titleCell);

        const priceCell = document.createElement('td');
        priceCell.textContent = user.email;
        row.appendChild(priceCell);

        const descriptionCell = document.createElement('td');
        descriptionCell.textContent = user.contact;
        row.appendChild(descriptionCell);

        const statusCell = document.createElement(`button`);
        statusCell.classList.add('btn', 'btn-primary');
        statusCell.setAttribute('type', 'button');
        statusCell.setAttribute('id', 'modalButton');

        let inverse='';
        let inverseStatus=0;
        if (user.status == 1) {
            inverseStatus=0;
            statusCell.textContent = 'Deactivated';
            inverse='Activate';
        } else if (user.status == 0) {
            inverseStatus=1;
            statusCell.textContent = 'Activated';
            inverse='Deactivate';
        }
        
        statusCell.addEventListener('click', function() {
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
            modalBody.textContent = 'The user is currently '+statusCell.textContent;
        
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
            
            const spinner = document.createElement('span');
            spinner.classList.add('spinner-border', 'spinner-border-sm', 'ms-2');
            spinner.setAttribute('role', 'status');
            spinner.setAttribute('aria-hidden', 'true');
            
            modalButton.addEventListener('click', function() {
              // Disable the button
              modalButton.disabled = true;
              
              // Add the spinner to the button
              modalButton.appendChild(spinner);
            
              // Call the changeStatus function
              changeStatus(user, inverseStatus);
                // console.log('Change Status:',user.email);
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
        



        tableBody.appendChild(row);
    });

    table.appendChild(tableBody);

    // Append the table to the listings section
    listingsSection.appendChild(table);
}

function changeStatus(user,status) {

    $.ajax({
        type: 'POST',
        url: './model/users.php',
        data: { action: 'changeStatus', userEmail:user.email,status:status },
        dataType: 'json',
        success: function (response) {

            if(response.status){
                console.log(response.message);
                location.reload();

            }
            // $.ajax({
            //     type: 'POST',
            //     url: './model/mail.php',
            //     data: { action: 'sendMail','receiver':ad.uploader,'status':status,'title':ad.title },
            //     dataType: 'json',
            //     success: function (response) {

                    
            //         location.reload();
            //         // console.log(ad.uploader);
            //         // console.log(response.message);
        
            //     },
            //     error: function(xhr, error) {
            //         console.log(xhr.responseText);
            //         console.log(error);
            //       }
            // });
        

        },
        error: function(xhr, error) {
            console.log(xhr.responseText);
            console.log(error);
          }
    });


}
