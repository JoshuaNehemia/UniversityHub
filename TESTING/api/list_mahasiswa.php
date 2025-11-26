<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../../APP/API/mahasiswa_list_api.php" method="GET">
        <input type="hidden" name="limit" value=5>
        <input type="hidden" name="offset" value=0>
        <input type="text" name="keyword">
        <input type="submit">
    </form>
</body>
</html>