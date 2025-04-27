document.addEventListener('DOMContentLoaded', () => {
    // Navigation between Order Update and Meal Update sections
    document.querySelector('#to-meal-update').addEventListener('click', () => {
        document.querySelector('#order-update').style.animation = 'fadeOut 1s 0s ease-in-out 1 forwards running';
    });

    document.querySelector('#order-update').addEventListener('animationend', (event) => {
        if (event['animationName'] === 'fadeOut') {
            document.querySelector('#order-update').style.display = 'none';
            document.querySelector('#meal-update').style.display = 'block';
            document.querySelector('#meal-update').style.animation = 'fadeIn 1s 0s ease-in-out 1 forwards running';
        }
    });

    document.querySelector('#to-order-update').addEventListener('click', () => {
        document.querySelector('#meal-update').style.animation = 'fadeOut 1s 0s ease-in-out 1 forwards running';
    });

    document.querySelector('#meal-update').addEventListener('animationend', (event) => {
        if (event['animationName'] === 'fadeOut') {
            document.querySelector('#meal-update').style.display = 'none';
            document.querySelector('#order-update').style.display = 'block';
            document.querySelector('#order-update').style.animation = 'fadeIn 1s 0s ease-in-out 1 forwards running';
        }
    });

    // Handle the order update form submission
    document.querySelector('form[action="../php/updateorder.php"]').addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(this);
        const orderid = document.getElementById('orderid').value;
        const status = document.getElementById('status').value;

        fetch('../php/updateorder.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                // Create a styled confirmation box
                const confirmationBox = document.createElement('div');
                confirmationBox.className = 'confirmation-box';
                confirmationBox.style.backgroundColor = '#343a40';
                confirmationBox.style.color = 'white';
                confirmationBox.style.padding = '20px';
                confirmationBox.style.borderRadius = '5px';
                confirmationBox.style.marginTop = '20px';
                confirmationBox.style.boxShadow = '0 0 10px rgba(0,0,0,0.3)';
                confirmationBox.style.textAlign = 'center';

                // Set the confirmation message
                confirmationBox.innerHTML = `
                <h4>${data}</h4>
                <p>Order #${orderid} status has been updated to ${status}.</p>
                <button class="btn btn-primary mt-3" id="update-another">Update Another Order</button>
            `;

                // Replace the form with the confirmation
                const formContainer = this.parentNode;
                formContainer.innerHTML = '';
                formContainer.appendChild(confirmationBox);

                // Add event listener to the "Update Another Order" button
                document.getElementById('update-another').addEventListener('click', () => {
                    location.reload();
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the order. Please try again.');
            });
    });

    // Handle the meal inventory update form submission
    document.querySelector('form[action="/updatemeal.php"]').addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(this);
        const mealid = document.getElementById('mealid').value;
        const quantity = document.getElementById('quantity').value;

        fetch('/restaurant/php/updatemeal.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                // Create a styled confirmation box
                const confirmationBox = document.createElement('div');
                confirmationBox.className = 'confirmation-box';
                confirmationBox.style.backgroundColor = '#343a40';
                confirmationBox.style.color = 'white';
                confirmationBox.style.padding = '20px';
                confirmationBox.style.borderRadius = '5px';
                confirmationBox.style.marginTop = '20px';
                confirmationBox.style.boxShadow = '0 0 10px rgba(0,0,0,0.3)';
                confirmationBox.style.textAlign = 'center';

                // Set the confirmation message
                confirmationBox.innerHTML = `
                <h4>Inventory Updated</h4>
                <p>Meal #${mealid} quantity has been updated to ${quantity}.</p>
                <button class="btn btn-primary mt-3" id="update-another-meal">Update Another Meal</button>
            `;

                // Replace the form with the confirmation
                const formContainer = this.parentNode;
                formContainer.innerHTML = '';
                formContainer.appendChild(confirmationBox);

                // Add event listener to the "Update Another Meal" button
                document.getElementById('update-another-meal').addEventListener('click', () => {
                    location.reload();
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the meal inventory. Please try again.');
            });
    });
});