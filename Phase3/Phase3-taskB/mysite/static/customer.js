document.addEventListener('DOMContentLoaded', () => {
    const cart = [];

    // Load meals into the dropdown
    fetch('../php/getMeals.php')
        .then(response => response.json())
        .then(meals => {
            const mealSelect = document.getElementById('mealid');
            mealSelect.innerHTML = '';
            meals.forEach(meal => {
                const option = document.createElement('option');
                option.value = meal.Meal_ID;
                option.textContent = `${meal.Name} - $${meal.Price}`;
                mealSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading meals:', error);
            document.getElementById('mealid').innerHTML = '<option value="">Error loading meals</option>';
        });

    // Add meal to cart
    document.getElementById('placeOrderForm').addEventListener('submit', function (event) {
        event.preventDefault();

        const mealId = document.getElementById('mealid').value;
        const mealText = document.getElementById('mealid').selectedOptions[0].textContent;
        const quantity = document.getElementById('quantity').value;

        if (!mealId || quantity <= 0) {
            alert('Please select a valid meal and quantity.');
            return;
        }

        cart.push({ mealId, mealText, quantity });
        renderCart();
    });

    function renderCart() {
        const tbody = document.querySelector('#cartTable tbody');
        tbody.innerHTML = '';

        cart.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.mealText}</td>
                <td>${item.quantity}</td>
                <td><button class="btn btn-danger btn-sm" onclick="removeFromCart(${index})">Remove</button></td>
            `;
            tbody.appendChild(tr);
        });

        // Enable Place Order button only if there are items
        document.getElementById('placeFullOrderBtn').disabled = cart.length === 0;
    }

    window.removeFromCart = function (index) {
        cart.splice(index, 1);
        renderCart();
    };

    // Place the full order
    document.getElementById('placeFullOrderBtn').addEventListener('click', async function () {
        if (cart.length === 0) {
            alert('Your cart is empty!');
            return;
        }

        const orderType = document.getElementById('orderTypeFinal').value;
        const formData = new FormData();
        formData.append('orderType', orderType);
        formData.append('cart', JSON.stringify(cart));

        try {
            const response = await fetch('../php/neworder.php', {
                method: 'POST',
                body: formData
            });
            const resultHtml = await response.text();
            document.getElementById('placeOrderResult').innerHTML = resultHtml;

            cart.length = 0;
            renderCart();
        } catch (error) {
            console.error('Error placing order:', error);
            document.getElementById('placeOrderResult').innerHTML = '<div class="alert alert-danger">Error placing order.</div>';
        }
    });

    // Track Order
    document.getElementById('trackOrderForm').addEventListener('submit', async function (event) {
        event.preventDefault();
        const orderid = document.getElementById('orderid').value;
        const resultDiv = document.getElementById('trackOrderResult');

        if (!orderid.trim()) {
            resultDiv.innerHTML = '<div class="alert alert-warning">Please enter a valid Order ID.</div>';
            return;
        }

        try {
            const response = await fetch(`../php/trackorder.php?orderid=${encodeURIComponent(orderid)}`);
            const resultHtml = await response.text();
            resultDiv.innerHTML = resultHtml;
        } catch (error) {
            console.error('Error tracking order:', error);
            resultDiv.innerHTML = '<div class="alert alert-danger">Error tracking order.</div>';
        }
    });

    // Page animation for switching between place/track
    document.getElementById('to-track').addEventListener('click', () => {
        document.getElementById('place').style.animation = 'fadeOut 1s forwards';
    });

    document.getElementById('place').addEventListener('animationend', (event) => {
        if (event.animationName === 'fadeOut') {
            document.getElementById('place').style.display = 'none';
            document.getElementById('track').style.display = 'block';
            document.getElementById('track').style.animation = 'fadeIn 1s forwards';
        }
    });

    document.getElementById('to-order').addEventListener('click', () => {
        document.getElementById('track').style.animation = 'fadeOut 1s forwards';
    });

    document.getElementById('track').addEventListener('animationend', (event) => {
        if (event.animationName === 'fadeOut') {
            document.getElementById('track').style.display = 'none';
            document.getElementById('place').style.display = 'block';
            document.getElementById('place').style.animation = 'fadeIn 1s forwards';
        }
    });
});
