<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderID = $_POST['orderid'] ?? null;
    $status = $_POST['status'] ?? null;

    if (!$orderID || !$status) {
        exit("<div style='text-align: center; padding-top: 50px;'>Missing order ID or status.</div>");
    }

    $validStatuses = ['Pending', 'Cancelled', 'Completed'];
    if (!in_array($status, $validStatuses)) {
        exit("<div style='text-align: center; padding-top: 50px;'>⚠️ Invalid status value.</div>");
    }

    // Get the order details before updating
    $getOrderStmt = $pdo->prepare("SELECT * FROM Orders WHERE Order_ID = ?");
    $getOrderStmt->execute([$orderID]);
    $order = $getOrderStmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        exit("<div style='text-align: center; padding-top: 50px;'>⚠️ No order found with ID #{$orderID}.</div>");
    }

    $oldStatus = $order['OrderStatus'];

    // Update the order
    $stmt = $pdo->prepare("UPDATE Orders SET OrderStatus = ? WHERE Order_ID = ?");
    $stmt->execute([$status, $orderID]);

    if ($stmt->rowCount() > 0) {
        ?>
        <!doctype html>
        <html lang="en" class="h-100">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Order Updated</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="../static/index.css" rel="stylesheet">
            <style>
                body {
                    text-shadow: 0 .05rem .1rem rgba(0, 0, 0, .5);
                    box-shadow: inset 0 0 5rem rgba(0, 0, 0, .5);
                }
                .cover-container {
                    max-width: 42em;
                }
            </style>
        </head>
        <body class="d-flex h-100 text-center text-white bg-dark">
            <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
                <header class="mb-auto">
                    <div>
                        <h3 class="float-md-start mb-0">Awesome Restaurant</h3>
                        <nav class="nav nav-masthead justify-content-center float-md-end">
                            <a class="nav-link" href="../htmlFiles/customer.html">Customers</a>
                            <a class="nav-link active" aria-current="page" href="../htmlFiles/staff.html">Staff</a>
                            <a class="nav-link" href="../htmlFiles/admin.html">Administrator</a>
                        </nav>
                    </div>
                </header>

                <main class="px-3">
                    <h1>Order Updated Successfully!</h1>
                    <p>Order #<?php echo htmlspecialchars($orderID); ?> status updated from <b><?php echo htmlspecialchars($oldStatus); ?></b> to <b><?php echo htmlspecialchars($status); ?></b>.</p>
                    <p>Order Details:</p>
                    <p>Order Type: <?php echo htmlspecialchars($order['OrderType']); ?></p>
                    <p>Total Price: $<?php echo htmlspecialchars(number_format($order['TotalPrice'], 2)); ?></p>
                    <p>Order Time: <?php echo htmlspecialchars($order['Time']); ?></p>
                    <p>
                        <a href="../htmlFiles/staff.html" class="btn btn-primary">Update Another Order</a>
                    </p>
                </main>

                <footer class="mt-auto text-white-50">
                    <p>We hope you enjoy our food!</p>
                </footer>
            </div>
        </body>
        </html>
        <?php
    } else {
        ?>
        <!doctype html>
        <html lang="en" class="h-100">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>No Changes Made</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="../static/index.css" rel="stylesheet">
            <style>
                body {
                    text-shadow: 0 .05rem .1rem rgba(0, 0, 0, .5);
                    box-shadow: inset 0 0 5rem rgba(0, 0, 0, .5);
                }
                .cover-container {
                    max-width: 42em;
                }
            </style>
        </head>
        <body class="d-flex h-100 text-center text-white bg-dark">
            <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
                <header class="mb-auto">
                    <div>
                        <h3 class="float-md-start mb-0">Awesome Restaurant</h3>
                        <nav class="nav nav-masthead justify-content-center float-md-end">
                            <a class="nav-link" href="../htmlFiles/customer.html">Customers</a>
                            <a class="nav-link active" aria-current="page" href="../htmlFiles/staff.html">Staff</a>
                            <a class="nav-link" href="../htmlFiles/admin.html">Administrator</a>
                        </nav>
                    </div>
                </header>

                <main class="px-3">
                    <h1>No Changes Made</h1>
                    <p>Order #<?php echo htmlspecialchars($orderID); ?> was already set to status <?php echo htmlspecialchars($status); ?>.</p>
                    <p>
                        <a href="../htmlFiles/staff.html" class="btn btn-primary">Go Back</a>
                    </p>
                </main>

                <footer class="mt-auto text-white-50">
                    <p>We hope you enjoy our food!</p>
                </footer>
            </div>
        </body>
        </html>
        <?php
    }
} else {
    ?>
    <!doctype html>
    <html lang="en" class="h-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Invalid Request</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../static/index.css" rel="stylesheet">
        <style>
            body {
                text-shadow: 0 .05rem .1rem rgba(0, 0, 0, .5);
                box-shadow: inset 0 0 5rem rgba(0, 0, 0, .5);
            }
            .cover-container {
                max-width: 42em;
            }
        </style>
    </head>
    <body class="d-flex h-100 text-center text-white bg-dark">
        <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
            <header class="mb-auto">
                <div>
                    <h3 class="float-md-start mb-0">Awesome Restaurant</h3>
                    <nav class="nav nav-masthead justify-content-center float-md-end">
                        <a class="nav-link" href="../htmlFiles/customer.html">Customers</a>
                        <a class="nav-link active" aria-current="page" href="../htmlFiles/staff.html">Staff</a>
                        <a class="nav-link" href="../htmlFiles/admin.html">Administrator</a>
                    </nav>
                </div>
            </header>

            <main class="px-3">
                <h1>Error</h1>
                <p>Invalid request method.</p>
                <p>
                    <a href="../htmlFiles/staff.html" class="btn btn-primary">Go Back</a>
                </p>
            </main>

            <footer class="mt-auto text-white-50">
                <p>We hope you enjoy our food!</p>
            </footer>
        </div>
    </body>
    </html>
    <?php
}
?>