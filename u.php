<?php
include 'function.php';
include 'config.php';
echo pasteHeader("Завантаження $mySite");
echo '<body><div align="center" width="80%">';
$target_name = formatName($_FILES["fileToUpload"]["name"]);
$target_file = $myDirectory.DIRECTORY_SEPARATOR.formatName($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
if(isset($_POST["submit"])) {
    $uploadOk = 1;
}
if(preg_match('/html$/i',$target_name) || preg_match('/php$/i',$target_name) || preg_match('/bin$/i',$target_name) || preg_match('/sh$/i',$target_name)){
    $uploadOk = 0;
    echo "<H1>Пішов нафіг, квакер <br><br></H1>";
}
if (file_exists($target_file)) {
    echo "Помилка: вже є такий файл<br><br>";
    $uploadOk = 0;
}
if ($_FILES["fileToUpload"]["size"] > 10240000) {
    echo "Помилка: за великий розмір (>10Мб)<br><br>";
    $uploadOk = 0;
}
if ($uploadOk == 0) {
    //echo "Помилка: я загубив файл<br><br>";
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "Файл ". basename( $_FILES["fileToUpload"]["name"]). " завантажено та збережено як $target_name<br><H1>Вдало</H1><br><br>";
    } else {
        echo "Помилка: щось інше";
    }
}
?>
<form action="u.php" method="post" style="align:center; width:500px; border: 4px double #2C5F10;" enctype="multipart/form-data">
Виберіть що саме:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Завантажити" name="submit">
</form>
</div>
</body>
</html>

