document.addEventListener('DOMContentLoaded', () => {
    // Handle order update form with AJAX to stay on the same page
    document.querySelector('form[action="../php/updateorder.php"]').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent form from submitting normally

        const formData = new FormData(this);
        const orderid = document.getElementById('orderid').value;
        const status = document.getElementById('status').value;

        fetch('../php/updateorder.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                document.body.innerHTML = `
            <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
                <header class="mb-auto">
                    <div>
                        <h3 class="float-md-start mb-0">Awesome Restaurant</h3>
                        <nav class="nav nav-masthead justify-content-center float-md-end">
                            <a class="nav-link" href="./customer.html">Customers</a>
                            <a class="nav-link active" aria-current="page" href="./staff.html">Staff</a>
                            <a class="nav-link" href="./admin.html">Administrator</a>
                        </nav>
                    </div>
                </header>

                <main class="px-3">
                    <h1>Order Updated Successfully!</h1>
                    <p>Order #${orderid} status updated to ${status}.</p>
                    
                    <h2>Order Details:</h2>
                    <p id="orderType">Order Type: Loading...</p>
                    <p id="totalPrice">Total Price: Loading...</p>
                    <p id="orderTime">Order Time: Loading...</p>
                    
                    <button type="button" class="btn btn-primary" id="update-another-btn">Update Another Order</button>
                </main>

                <footer class="mt-auto text-white-50">
                    <p>We hope you enjoy our food!</p>
                </footer>
            </div>
            `;

                // Fetch the order details to fill in
                fetch(`../php/getOrderDetails.php?orderid=${orderid}`)
                    .then(response => response.json())
                    .then(orderData => {
                        if (orderData && !orderData.error) {
                            document.getElementById('orderType').textContent = `Order Type: ${orderData.OrderType}`;
                            document.getElementById('totalPrice').textContent = `Total Price: $${Number(orderData.TotalPrice).toFixed(2)}`;
                            document.getElementById('orderTime').textContent = `Order Time: ${orderData.Time}`;
                        } else {
                            console.error('Error in order data:', orderData);
                        }

                        document.getElementById('update-another-btn').addEventListener('click', () => {
                            location.href = './staff.html';
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching order details:', error);
                        document.getElementById('update-another-btn').addEventListener('click', () => {
                            location.href = './staff.html';
                        });
                    });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the order. Please try again.');
            });
    });
});