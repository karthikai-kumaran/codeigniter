<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>

<body>

    <form action="<?= base_url('/user/saveOrder') ?>" method="post">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll" onclick="toggleAllCheckboxes(this)"> Select All</th>
                    <th>Sno</th>
                    <th>Product Id</th>
                    <th>Product Name</th>
                    <th>Available</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($data as $index => $row) {
                ?>
                    <tr>
                        <td><input type="checkbox" name="products[<?= $index ?>][select]" class="product-checkbox" onchange="toggleQuantity(this, <?= $index ?>)" data-index="<?= $index ?>"></td>
                        <td> <?php echo $row->sno; ?></td>
                        <td><input type="hidden" name="products[<?= $index ?>][productId]" value="<?= $row->productId; ?>"><?php echo $row->productId; ?></td>
                        <td><input type="hidden" name="products[<?= $index ?>][itemName]" value="<?= $row->itemName; ?>"><?php echo $row->itemName; ?></td>
                        <td><input type="hidden" name="products[<?= $index ?>][available]" value="<?= $row->available; ?>"><?php echo $row->available; ?></td>
                        <td><input type="hidden" name="products[<?= $index ?>][unitPrice]" value="<?= $row->unitPrice; ?>"><?php echo $row->unitPrice; ?></td>
                        <td><input type="number" name="products[<?= $index ?>][quantity]" min="1" disabled class="product-qty" data-index="<?= $index ?>" oninput="validateQuantity(this, <?= $row->available ?>); unitTotalCalculation()">
                        <span class="error-message" style="color: red; font-size: 12px;"></span>
                    </td>
                        <td>
                            <p id="products[<?= $index ?>][totalValue]"></p>
                        </td>
                    </tr>

                <?php     }

                ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Total Amount: ₹<span id="grandTotal">0.00</span></strong></p>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <input type="text" name="customerName" placeholder="Enter Customer Name" required>
                <input type="email" name="customerMailId" placeholder="Enter Customer Mail Id" required>
                <input type="hidden" name="totalAmount" id="totalAmount">
                <input type="submit" value="Submit">
            </div>
        </div>
    </form>

    <script>
        function toggleAllCheckboxes(selectAllCheckbox) {
    let checkboxes = document.querySelectorAll(".product-checkbox");
    checkboxes.forEach((checkbox) => {
        checkbox.checked = selectAllCheckbox.checked;
        toggleQuantity(checkbox, checkbox.getAttribute("data-index")); // Enable/disable quantity
    });
}
        function toggleQuantity(checkbox, index) {
            let qtyField = document.querySelector("[name='products[" + index + "][quantity]']");
            qtyField.disabled = !checkbox.checked; // Enable/Disable input
            if (!checkbox.checked) {
                qtyField.value = ""; // Clear value when unchecked
            }
            unitTotalCalculation();
        }

        function validateQuantity(input, maxAvailable) {
    let errorMessage = input.parentElement.querySelector(".error-message");

    if (input.value > maxAvailable) {
        input.value = maxAvailable; // Set to max available
        errorMessage.textContent = "Max available: " + maxAvailable; // Show message
    } else {
        errorMessage.textContent = ""; // Hide message if valid
    }

    if (input.value < 1) {
        input.value = 1; // Set minimum value
    }
}


        function unitTotalCalculation() {
            let total = 0;
            document.querySelectorAll(".product-qty").forEach((qtyField) => {
                if (qtyField.disabled || qtyField.value === "") {
                    return;
                }
                let index = qtyField.dataset.index;
                let price = parseFloat(document.querySelector("[name='products[" + index + "][unitPrice]']").value);
                let quantity = parseInt(qtyField.value);
                let totalValue = price * quantity;
                total += totalValue;

                // Update total value in table
                let totalValueField = document.querySelector("#products\\[" + index + "\\]\\[totalValue\\]");
                if (totalValueField) {
                    totalValueField.textContent = totalValue.toFixed(2);
                }
            });

            document.getElementById("grandTotal").textContent = total.toFixed(2);
            document.getElementById("totalAmount").value = total.toFixed(2);
        }
    </script>
</body>

</html>

