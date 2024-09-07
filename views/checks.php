<?php require_once "templates/adminNav.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Orders</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .filter-section {
            margin-bottom: 20px;
        }
        #orderDetails, #productDetails {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Checks</h1>
        <div class="filter-section row">
            <div class="col-md-3">
                <label for="userDropdown">Select User:</label>
                <select id="userDropdown" class="form-control"></select>
            </div>
            <div class="col-md-3">
                <label for="fromDate">From:</label>
                <input type="date" id="fromDate" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="toDate">To:</label>
                <input type="date" id="toDate" class="form-control">
            </div>
            <!-- <div class="col-md-3 align-self-end">
                <button onclick="filterOrders()" class="btn btn-primary">Filter</button>
            </div> -->
        </div>
        <table id="userTable" class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>User</th>
                    <th>Total Amount</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody>
                <!-- User rows will be dynamically added here -->
            </tbody>
        </table>

        <div id="orderDetails">
            <h2>Orders for <span id="selectedUser"></span></h2>
            <table id="ordersTable" class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Order Date</th>
                        <th>Amount</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    <!-- Order rows will be dynamically added here -->
                </tbody>
            </table>
        </div>

        <div id="productDetails">
            <h2>Product Details for Order</h2>
            <div id="productContent" class="row">
                <!-- Product details will be dynamically added here -->
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        async function fetchUsers(userId="all") {
            const response = await fetch(`fetch_users.php?user_id=${userId}`);
            const usersData = await response.json();
            return usersData;
        }

        async function fetchOrders(userId, fromDate="", toDate="") {
            if(fromDate!="") fromDate = `${fromDate}T00:00:00`;
            if(toDate!="") toDate = `${toDate}T23:59:59`;

            const response = await fetch(`fetch_orders.php?user_id=${userId}&from_date=${fromDate}&to_date=${toDate}`);
            const orders = await response.json();
            return orders;
        }

        async function fetchProducts(orderId) {
            const response = await fetch(`fetch_products.php?order_id=${orderId}`);
            const products = await response.json();
            return products;
        }

        function populateUserDropdown(users) {
            const userDropdown = document.getElementById('userDropdown');
            const allOption = document.createElement('option');
            allOption.value = 'all';
            allOption.textContent = 'All';
            userDropdown.appendChild(allOption);

            users.forEach(user => {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = user.name;
                userDropdown.appendChild(option);
            });
        }

        function populateUserTable(users) {
            const userTableBody = document.querySelector('#userTable tbody');
            userTableBody.innerHTML = '';
            users.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
            <td>${user.name}</td>
            <td>${user.total_amount}</td>
            <td><button class="btn btn-primary btn-sm show-orders-btn">Show</button></td>
        `;
        const showButton = row.querySelector('.show-orders-btn');
        showButton.addEventListener('click', async () => {
            const fromDate = document.getElementById('fromDate').value;
            const toDate = document.getElementById('toDate').value;
            const productContent = document.getElementById('productContent');
            productContent.innerHTML = '';

            if (fromDate && toDate && fromDate > toDate) {
                alert('"From" date must be less than or equal to "To" date.');
                return;
            }

            const orders = await fetchOrders(user.id, fromDate, toDate);
            showOrders(orders, user.name);
        });
        userTableBody.appendChild(row);
    });
}

        async function showOrders(orders, userName) {
            let userText = userName;
            userText = `${userText}`;

            const fromDate = document.getElementById('fromDate').value;
            const toDate = document.getElementById('toDate').value;

            if (fromDate && !toDate) {
                userText += ` starting from ${fromDate}`;
            } else if (!fromDate && toDate) {
                userText += ` to ${toDate}`;
            } else if (fromDate && toDate) {
                userText += ` from ${fromDate} to ${toDate}`;
            }

            document.getElementById('selectedUser').textContent = userText;

            const ordersTableBody = document.querySelector('#ordersTable tbody');
            ordersTableBody.innerHTML = '';

            if (!Array.isArray(orders)) {
                console.error('Orders is not an array:', orders);
                return;
            }

            let totalAmount = 0;
            let numberOfOrders = orders.length;
            orders.forEach(order => {
                totalAmount += parseFloat(order.total_price);
            });

            // Add summary row
            const summaryRow = document.createElement('tr');
            summaryRow.innerHTML = `<td><strong>Total</strong></td><td><strong>${totalAmount.toFixed(2)}</strong></td>`;
            ordersTableBody.appendChild(summaryRow);

            // Add number of orders row
            const numberOfOrdersRow = document.createElement('tr');
            numberOfOrdersRow.innerHTML = `<td><strong>Number of Orders</strong></td><td><strong>${numberOfOrders}</strong></td>`;
            ordersTableBody.appendChild(numberOfOrdersRow)
            orders.forEach(order => {
                const row = document.createElement('tr');
                row.innerHTML = `<td>${order.order_date}</td><td>${order.total_price}</td><td><button class="btn btn-primary btn-sm show-pro-btn">Show</button></td>`;
                const showButton= row.querySelector('.show-pro-btn');
                showButton.addEventListener('click', () => showProducts(order.id));
                ordersTableBody.appendChild(row);
            });
        }

        async function showProducts(orderId) {
            const products = await fetchProducts(orderId);
            const productContent = document.getElementById('productContent');
            productContent.innerHTML = '';
            products.forEach(product => {
                const div = document.createElement('div');
                div.className = 'col-md-3';
                div.innerHTML = `
                    <img src="../AllProdect/view/products/uploads/${product.image}" alt="Product Image" class="img-fluid">
                    <p>Price: ${product.price}</p>
                    <p>Quantity: ${product.quantity}</p>
                `;
                productContent.appendChild(div);
            });
        }

        async function init() {
            const users = await fetchUsers();
            populateUserDropdown(users);
            populateUserTable(users);
        }

        init();
    </script>
</body>
</html>