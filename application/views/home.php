<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>

<body>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Sno</th>
                <th>Customer Name</th>
                <th>Customer Mail Id</th>
                <th>Total Amount</th>
                <th>Order Details</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sno = 1;
            foreach ($data as $index => $row) {
                // Decode the order details from JSON string to an array
                $orderDetails = json_decode($row->orderItems, true);
                $shortDetails = substr(json_encode($orderDetails), 0, 50);
                $fullDetailsJson = htmlspecialchars(json_encode($orderDetails), ENT_QUOTES, 'UTF-8');
            ?>
                <tr>
                    <td><?= $sno ?></td>
                    <td><?= $row->customerName ?></td>
                    <td><?= $row->customerMailId ?></td>
                    <td><?= $row->totalAmount ?></td>
                    <td>
                        <span id="order-preview-<?= $index ?>"><?= $shortDetails ?>...</span>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal"
                            onclick='showFullOrderDetails(<?= $fullDetailsJson ?>)'>
                            <b>Read More...</b>
                        </a>
                    </td>
                    <td><?= $row->createdAt ?></td>
                </tr>
            <?php
                $sno++;
            }
            ?>
        </tbody>


    </table>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4>Order Details</h4>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product Id</th>
                                <th>Product Name</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th>Total Value</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                    <p id="orderFullDetails">

                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

</body>
<script>
    function showFullOrderDetails(orderJson) {
        console.log("Order Details:", orderJson);

        // Clear existing table data
        let tableBody = document.querySelector("#exampleModal tbody");
        tableBody.innerHTML = "";

        // Check if it's an array of products
        if (Array.isArray(orderJson)) {
            orderJson.forEach(product => {
                let row = `<tr>
                <td>${product.productId}</td>
                <td>${product.itemName}</td>
                <td>${product.unitPrice}</td>
                <td>${product.quantity}</td>
                <td>${product.totalValue}</td>
            </tr>`;
                tableBody.innerHTML += row;
            });
        }
        // If orderJson is a single product (not an array)
        else {
            let row = `<tr>
            <td>${orderJson.productId}</td>
            <td>${orderJson.itemName}</td>
            <td>${orderJson.unitPrice}</td>
            <td>${orderJson.quantity}</td>
            <td>${orderJson.totalValue}</td>
        </tr>`;
            tableBody.innerHTML = row;
        }
    }
</script>

</html>