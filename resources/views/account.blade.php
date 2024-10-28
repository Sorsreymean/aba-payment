<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
</head>
<body>

    <h1>Create ABA PayWay Account On file</h1>

    <form action="{{ route('payment.account') }}" method="POST">
        @csrf
        <input type="hidden" name="customer_id" value="123456">
        <br>
        <input type="text" name="name" value="Jonh Doe">
        <br>
        <input type="text" name="email" value="jonhDoe@example.com">
        <br>
        <input type="text" name="phone" value="098765433">
        <br>

        <button type="submit">Create Account</button>
    </form>

</body>
</html>
