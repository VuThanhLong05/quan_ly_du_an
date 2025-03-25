<!-- checkout.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>

<body>
    <h1>Checkout</h1>

    <h2>Your Cart</h2>
    <table>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
        </tr>
        <?php foreach ($cart as $item): ?>
            <tr>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo number_format($item['price'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Total Price: <?php echo number_format($totalPrice, 2); ?></h3>

    <?php if ($discountValue > 0): ?>
        <h3>Discount Applied: <?php echo number_format($discountValue, 2); ?></h3>
    <?php endif; ?>

    <!-- Form for user to input shipping info and payment details -->
    <form action="xu-ly-thanh-toan" method="POST">
        <h2>Shipping Information</h2>
        <input type="text" name="address" placeholder="Enter your address" required>
        <input type="text" name="phone" placeholder="Enter your phone number" required>

        <h2>Payment Method</h2>
        <select name="payment_method" required>
            <option value="credit_card">Credit Card</option>
            <option value="momo">Momo</option>
        </select>

        <button type="submit">Proceed to Payment</button>
    </form>
</body>

</html>