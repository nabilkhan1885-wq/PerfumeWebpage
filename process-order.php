<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $product = $_POST['product'];
    $price = $_POST['price'];
    $notes = isset($_POST['notes']) ? $_POST['notes'] : '';
    
    // Create order data
    $order = [
        'order_id' => 'ORD-' . time() . '-' . rand(100, 999),
        'date' => date('Y-m-d H:i:s'),
        'fullname' => $fullname,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'product' => $product,
        'price' => $price,
        'notes' => $notes,
        'status' => 'Pending'
    ];
    
    // Save order to JSON file (for admin panel)
    $orders_file = 'uploads/orders.json';
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }
    
    $existing_orders = [];
    if (file_exists($orders_file)) {
        $existing_orders = json_decode(file_get_contents($orders_file), true);
    }
    
    $existing_orders[] = $order;
    file_put_contents($orders_file, json_encode($existing_orders, JSON_PRETTY_PRINT));
    
    // Send email notification to admin
   $to = "bayonetessence@gmail.com"; // CHANGE THIS // CHANGE THIS TO YOUR EMAIL
    $subject = "New Order: " . $order['order_id'];
    
    $message = "New order received!\n\n";
    $message .= "Order ID: " . $order['order_id'] . "\n";
    $message .= "Date: " . $order['date'] . "\n";
    $message .= "Product: " . $product . "\n";
    $message .= "Price: ₹" . $price . "\n";
    $message .= "Customer: " . $fullname . "\n";
    $message .= "Email: " . $email . "\n";
    $message .= "Phone: " . $phone . "\n";
    $message .= "Address: " . $address . "\n";
    $message .= "Notes: " . $notes . "\n";
    
    $headers = "From: bayonetessence@gmail.com\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    
    // Try to send email (may need SMTP setup on Hostinger)
    mail($to, $subject, $message, $headers);
    
    // Display success page
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order Confirmation - Bayonet Essence</title>
        <link rel="stylesheet" href="css/style.css">
        <meta http-equiv="refresh" content="5;url=index.html">
    </head>
    <body>
        <div class="checkout-container">
            <h2>Thank You for Your Order! 🎉</h2>
            <div class="success-message">
                <p>Your order has been placed successfully!</p>
                <p><strong>Order ID: <?php echo $order['order_id']; ?></strong></p>
                <p>We'll contact you shortly to confirm your order.</p>
                <p>Redirecting to homepage in 5 seconds...</p>
                <p><a href="index.html" style="color: #c9a55c;">Click here if not redirected</a></p>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>