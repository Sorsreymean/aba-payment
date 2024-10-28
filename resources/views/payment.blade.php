<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Payment Link</title>
</head>
<body>

    <h1>Create ABA PayWay Payment Link</h1>

    <form action="{{ route('payment.create') }}" method="POST">
        @csrf
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" value="10" required>
        <br>

        {{-- <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <br> --}}

        <button type="submit">Create Payment Link</button>
    </form>

</body>
</html>
