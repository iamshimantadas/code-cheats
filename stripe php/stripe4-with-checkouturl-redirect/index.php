<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>stripe example</title>
</head>
<body>


<form action="checkout.php" method="post">
<input type="email" name="email" placeholder="enter customer email">   
<input type="text" name="customer_name" placeholder="enter customer full name">   
<input type="text" name="product" placeholder="enter product name">
    <input type="number" min="1" name="price" id="price" placeholder="enter product price">
    <button type="submit">pay</button>
</form>

    
</body>
</html>