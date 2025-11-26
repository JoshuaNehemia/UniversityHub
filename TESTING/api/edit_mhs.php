<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../../APP/API/edit_mahasiswa_api.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="username" placeholder="username">
        <input type="text" name="nama" placeholder="nama">
        <input type="text" name="nrp" placeholder="nrp">
        <input type="text" name="tanggal_lahir" placeholder="tanggal lahir">
        <input type="text" name="gender" placeholder="gender">
        <input type="text" name="angkatan" placeholder="angkatan">
        <input type="file" name="foto" placeholder="profile picture">
        <input type="submit">
    </form>
</body>
</html>