document.addEventListener('DOMContentLoaded', () => {
    // Load meals from the database
    fetch('/restaurant/php/getMeals.php')
        .then(response => response.json())
        .then(meals => {
            const mealSelect = document.getElementById('mealid');
            mealSelect.innerHTML = ''; // Clear existing options

            meals.forEach(meal => {
                const option = document.createElement('option');
                option.value = meal.Meal_ID;
                option.textContent = `${meal.Name} - $${meal.Price}`;
                mealSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading meals:', error);
            const mealSelect = document.getElementById('mealid');
            const option = document.createElement('option');
            option.value = "";
            option.textContent = "Error loading meals";
            mealSelect.innerHTML = '';
            mealSelect.appendChild(option);
        });

    // Navigation between Place Order and Track Order sections
    document.querySelector('#to-track').addEventListener('click', () => {
        document.querySelector('#place').style.animation = 'fadeOut 1s 0s ease-in-out 1 forwards running';
    });

    document.querySelector('#place').addEventListener('animationend', (event) => {
        if (event['animationName'] === 'fadeOut') {
            document.querySelector('#place').style.display = 'none';
            document.querySelector('#track').style.display = 'block';
            document.querySelector('#track').style.animation = 'fadeIn 1s 0s ease-in-out 1 forwards running';
        }
    });

    document.querySelector('#to-order').addEventListener('click', () => {
        document.querySelector('#track').style.animation = 'fadeOut 1s 0s ease-in-out 1 forwards running';
    });

    document.querySelector('#track').addEventListener('animationend', (event) => {
        if (event['animationName'] === 'fadeOut') {
            document.querySelector('#track').style.display = 'none';
            document.querySelector('#place').style.display = 'block';
            document.querySelector('#place').style.animation = 'fadeIn 1s 0s ease-in-out 1 forwards running';
        }
    });

    // Submit order form handling
    document.querySelector('form[action="/neworder.php"]').addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(this);
        // Add quantity from the quantity input
        formData.append('quantity', document.getElementById('quantity').value);

        fetch('/restaurant/php/neworder.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                // Display the response from neworder.php
                const resultDiv = document.createElement('div');
                resultDiv.innerHTML = data;
                resultDiv.className = 'order-confirmation';

                // Replace the form with the result
                this.replaceWith(resultDiv);

                // Add a button to place another order
                const newOrderBtn = document.createElement('button');
                newOrderBtn.textContent = 'Place Another Order';
                newOrderBtn.className = 'btn btn-primary mt-3';
                newOrderBtn.addEventListener('click', () => {
                    location.reload();
                });
                resultDiv.appendChild(document.createElement('br'));
                resultDiv.appendChild(newOrderBtn);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while placing your order. Please try again.');
            });
    });

    // Track order form handling
    document.querySelector('form[action="/trackorder.php"]').addEventListener('submit', function (event) {
        event.preventDefault();

        const orderID = document.getElementById('orderid').value;

        fetch(`/restaurant/php/trackorder.php?orderid=${orderID}`)
            .then(response => response.text())
            .then(data => {
                // Display the tracking result
                const resultDiv = document.createElement('div');
                resultDiv.innerHTML = data;
                resultDiv.className = 'tracking-result';

                // Clear any previous results
                const previousResults = document.querySelector('.tracking-result');
                if (previousResults) {
                    previousResults.remove();
                }

                // Add the new results after the form
                this.parentNode.appendChild(resultDiv);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while tracking your order. Please try again.');
            });
    });
});