<?php
require 'header.php';

if (isset($_POST['gonder'])) {
    // Kullanıcı giriş bilgilerini al
    $mail = mysqli_real_escape_string($conn, $_POST['mail']);
    $pwd = mysqli_real_escape_string($conn, md5($_POST['pwd'])); // MD5 ile şifreyi şifrele

    // SQL sorgusunu hazırla
    $sql = "SELECT user_email, user_pwd, user_nick, user_mod
            FROM tbl_users
            WHERE user_email = '$mail' AND user_pwd = '$pwd'";

    // Sorguyu çalıştır
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Giriş başarılı
        $row = mysqli_fetch_array($result);
        $_SESSION["nick"] = $row["user_nick"];
        $_SESSION['mod'] = $row['user_mod'];

        mysqli_close($conn);
        ?>
        <script language="JavaScript">
            function yonlendir(adres) {
                if (adres != '') {
                    var url = 'goster.php?' + adres;
                    parent.frames["main"].location.href = url;
                } else {
                    parent.frames["main"].location.href = 'main.php';
                }
                parent.frames["ust"].location.reload();
            }

            function login() {
                yonlendir('<?= isset($_SESSION["adres"]) ? $_SESSION["adres"] : ""; ?>');
            }

            login();
        </script>
        <?php
    } else {
        // Giriş başarısız
        echo "<font color='red'>E-posta veya şifre hatalı.</font>";
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
</head>
<body>
    <form name="user" method="post">
        E-mail adresi<br/>
        <input type="text" name="mail" id="mail" style="width:210px" required/><br/>
        Şifre<br/>
        <input type="password" name="pwd" id="pwd" style="width:210px" maxlength="25" required/><br/>
        <input type='hidden' name='hid' value="ok"/>
        <input type="submit" name="gonder" value="Giriş Yap"/><br/>
    </form>

    <div class="left-menu">
        <a href="sifreyolla.php" target="main">Şifremi Unuttum</a><br/>
        <a href="yeni_kullanici.php" target="main">Yeni Üye Ol</a>
    </div>
</body>
</html>
