<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Card on File</title>
</head>
<body>

    <h1>Create ABA PayWay Card On file</h1>

    <form action="{{ route('payment.card') }}" method="POST">
        @csrf
        <input type="hidden" name="customer_id" value="123456">
        <br>
        <input type="text" name="name" value="Jonh Doe">
        <br>
        <input type="text" name="email" value="jonhDoe@example.com">
        <br>
        <input type="text" name="card_number" value="4026459992389502">
        <br>
        <input type="text" name="card_expiry" value="0222">
        <br>
        <input type="text" name="card_cvv" value="066">
        <br>

        <button type="submit">Create Account</button>
    </form>

</body>
</html>
