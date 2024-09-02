<?php require_once "templates/adminNav.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .filter-section {
            margin-bottom: 20px;
        }
        .action-button {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Order Management</h1>
        <div class="filter-section">
            <button class="btn btn-primary filter-button" onclick="filterOrders('all')">All</button>
            <button class="btn btn-primary filter-button" onclick="filterOrders('processing')">Processing</button>
            <button class="btn btn-primary filter-button" onclick="filterOrders('out for delivery')">Out for Delivery</button>
            <button class="btn btn-primary filter-button" onclick="filterOrders('done')">Done</button>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Order Date</th>
                    <th>Amount</th>
                    <th>User</th>
                    <th>Room</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="ordersTableBody">
                <!-- Order rows will be dynamically added here -->
            </tbody>
        </table>

        <nav>
            <ul class="pagination" id="pagination">
                <!-- Pagination links will be dynamically added here -->
            </ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        let lastClickedStatus = 'all'; // Default status
        let currentPage = 1;
        const itemsPerPage = 3;

        async function fetchOrders(status, page, limit) {
            const response = await fetch(`fetch_orders.php?status=${status}&page=${page}&limit=${limit}`);
            const data = await response.json();
            return data;
        }

        async function updateOrderStatus(orderId, newStatus) {
            const response = await fetch('update_order_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ orderId, newStatus })
            });
            const result = await response.json();
            return result;
        }

        function populateOrdersTable(orders) {
            const ordersTableBody = document.getElementById('ordersTableBody');
            ordersTableBody.innerHTML = '';
            orders.forEach(order => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${order.order_date}</td>
                    <td>${order.total_price}</td>
                    <td>${order.user_name}</td>
                    <td>${order.room_id}</td>
                    <td>${order.status}</td>
                    <td>
                        ${order.status === 'processing' ? `<button class="btn btn-sm btn-primary action-button" onclick="updateOrderStatus(${order.id}, 'out for delivery').then(() => filterOrders(lastClickedStatus, currentPage))">Deliver</button>` : ''}
                        ${order.status === 'out for delivery' ? `<button class="btn btn-sm btn-success action-button" onclick="updateOrderStatus(${order.id}, 'done').then(() => filterOrders(lastClickedStatus, currentPage))">Done</button>` : ''}
                    </td>
                `;
                ordersTableBody.appendChild(row);
            });
        }

        function createPagination(totalPages) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement('li');
                li.classList.add('page-item');
                if (i === currentPage) {
                    li.classList.add('active');
                }
                li.innerHTML = `<a class="page-link" href="#" onclick="filterOrders('${lastClickedStatus}', ${i})">${i}</a>`;
                pagination.appendChild(li);
            }
        }

        async function filterOrders(status, page = 1) {
            const ordersTableBody = document.getElementById('ordersTableBody');
            ordersTableBody.innerHTML = '';
            lastClickedStatus = status; // Update the last clicked status
            currentPage = page;
            const data = await fetchOrders(status, page, itemsPerPage);
            populateOrdersTable(data.orders);
            createPagination(data.totalPages);
        }

        // Initialize with all orders
        filterOrders('all');
    </script>
</body>
</html>