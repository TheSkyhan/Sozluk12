<?php require_once('header.php'); ?>
<script language="javascript">
    function popup(url) {
        var width = 650;
        var height = 400;
        var left = (screen.width - width) / 2;
        var top = (screen.height - height) / 2;
        var params = 'width=' + width + ', height=' + height;
        params += ', top=' + top + ', left=' + left;
        params += ', directories=0';
        params += ', location=0';
        params += ', menubar=0';
        params += ', resizable=0';
        params += ', scrollbars=0';
        params += ', status=0';
        params += ', toolbar=0';
        newwin = window.open(url, 'windowname5', params);
        if (window.focus) {
            newwin.focus()
        }
        return false;
    }
</script>
<table border="0" width="50%">

	<th><a href="kontrol.php" target="main" style="WORD-BREAK:keep-words;text-decoration:none;color:#FBFAF7; text-decoration: none;"
	       onmouseover='this.style.background = "green";' onmouseout='this.style.background = "none";'> Olay </a></th>

	<th><a href="kontrol.php?islem=mesaj" target="main" style="WORD-BREAK:keep-words;text-decoration:none;color:#FBFAF7; text-decoration: underline;"
	       onmouseover='this.style.background = "green";' onmouseout='this.style.background = "none";'> Mesaj </a></th>

	<th><a href="kontrol.php?islem=ayarlar" target="main" style="WORD-BREAK:keep-words;text-decoration:none;color:#FBFAF7 ;text-decoration: underline;"
	       onmouseover='this.style.background = "green";' onmouseout='this.style.background = "none";'> Ayarlar </a></th>

	<th><a href="kontrol.php?islem=badiler" target="main" style="WORD-BREAK:keep-words;text-decoration:none;color:#FBFAF7; text-decoration: underline;"
	       onmouseover='this.style.background = "green";' onmouseout='this.style.background = "none";'> Badiler </a></th>


    </tr>
    <tr>
	<td colspan="4"><br/></td>
    <tr>
	<?php

// Oturum kontrolü
session_start();
$login = isset($_SESSION["nick"]);
if (!$login) {
    die("Siteye giriş yapmamışsınız neyi kontrol edecem?");
}

$yazar = $_SESSION["nick"];
$islem = isset($_GET["islem"]) ? $_GET["islem"] : null;

if (isset($_REQUEST["but"])) {
    $but = $_REQUEST["but"];
    if ($but == "badi ekle") {
        $badi = isset($_GET["nick"]) ? $_GET["nick"] : null;

        if ($badi) {
            $badi = $conn->real_escape_string($badi);
            $sql = "SELECT user_nick FROM tbl_users WHERE user_nick = '$badi'";
            $result = $conn->query($sql);

            if ($result->num_rows == 0) {
                echo "Böyle bir kullanıcı sözlükte maalesef yok";
            } else {
                $yazar = $conn->real_escape_string($yazar);
                $sql = "INSERT INTO tbl_badi (badi_yazar, badi_kimle) VALUES ('$yazar', '$badi')";
                if ($conn->query($sql) === TRUE) {
                    echo "Yeni badiniz hayırlı olsun";
                } else {
                    echo "Hata: " . $conn->error;
                }
            }
        } else {
            echo "Badi nick'i belirtilmemiş";
        }

    } else if ($but == "seçilileri sil") {
        $badiler = isset($_GET['badi']) ? $_GET['badi'] : [];

        if (empty($badiler)) {
            echo "Hiç badi seçmemişsiniz ??";
        } else {
            $yazar = $conn->real_escape_string($yazar);
            $N = count($badiler);

            for ($i = 0; $i < $N; $i++) {
                $badi = $conn->real_escape_string($badiler[$i]);
                $sql = "DELETE FROM tbl_badi WHERE badi_yazar = '$yazar' AND badi_kimle = '$badi'";
                if ($conn->query($sql) === FALSE) {
                    echo "Hata: " . $conn->error;
                }
            }

            echo "Seçtiğiniz badiler artık yok :)";
        }
    }

}

	if (isset($_POST["şifre_gönder"])) {
	    $pwd = $_POST["şifre"];
	    $newpwd = $_POST["yeni_şifre"];
	    $uname = $yazar;

	    if (strlen($newpwd) < 4)
		die("Lütfen şifrenizi 4 karakterden uzun seçin");

	    if ($_POST["yeni_şifre"] != $_POST["yeni_tekrar"])
		die("girdiğiniz şifreler eşleşmiyor bir daha kontrol edin");

	    $sql = "SELECT user_nick FROM tbl_users WHERE user_nick= '" . mysqli_real_escape_string($uname) . "' AND user_pwd = '" . mysqli_real_escape_string(md5($pwd)) . "'";
	    $result = mysqli_query($conn, $sql);
	    if (mysqli_num_rows($result) == 1) {
		$sql = "UPDATE tbl_users SET user_pwd = '" . mysqli_real_escape_string(md5($newpwd)) . "'WHERE user_nick= '" . mysqli_real_escape_string($uname) . "' AND user_pwd = '" . mysqli_real_escape_string(md5($pwd)) . "'";
		if (mysqli_query($sql, $conn))
		    echo "şifreniz başarıyla değişti";
	    }
	    else {
		echo "şifrenizi doğru yazdığınızdan emin misiniz?";
	    }

	    echo '<script>parent.frames["main"].location.href = "kontrol.php?islem=ayarlar&alt=şifre";</script>';
	} else if (isset($_POST["mesajsil"])) {
	    $mesajlar = $_POST['mesaj'];
	    if (empty($mesajlar)) {
		echo "hiç mesaj seçmemişsiniz ??";
	    } else {

		$N = count($mesajlar);
		for ($i = 0; $i < $N; $i++) {

		    $sql = "DELETE FROM tbl_mesaj WHERE msg_msgid =" . $mesajlar[$i] . " AND msg_kimden = '".mysqli_real_escape_string($yazar)."'";
		    $result = mysqli_query($conn, $sql);
		}
		echo "seçtiğiniz mesajlar sonsuza dek yok :)";
	    }
	    echo '<script>parent.frames["main"].location.href = "kontrol.php?islem=mesaj";</script>';
	} else if (isset($_POST["mail_gönder"])) {
	    $pwd = $_POST["pwd"];
	    $newmail = $_POST["yeni_mail"];
	    $uname = $yazar;


	    if (!preg_match("/^[^@]*@[^@]*\.[^@]*$/", $newmail))
		die("geçersiz bir e-mail adresi girdiniz");

	    if ($_POST["yeni_mail"] != $_POST["yeni_mail_tekrar"])
		die("girdiğiniz mailler eşleşmiyor bir daha kontrol edin");

	    $sql = "SELECT user_nick FROM tbl_users WHERE user_nick= '" . mysqli_real_escape_string($uname) . "' AND user_pwd = '" . mysqli_real_escape_string(md5($pwd)) . "'";
	    $result = mysqli_query($conn, $sql);
	    if (mysqli_num_rows($result) == 1) {
		$sql = "UPDATE tbl_users SET user_email = '" . mysqli_real_escape_string($newmail) . "'WHERE user_nick= '" . mysqli_real_escape_string($uname) . "' AND user_pwd = '" . mysqli_real_escape_string(md5($pwd)) . "'";
		if (mysqli_query($sql, $conn))
		    echo "mail adresiniz başarıyla değişti";
	    }
	    else {
		echo "şifrenizi doğru yazdığınızdan emin misiniz?";
	    }

	    echo '<script>parent.frames["main"].location.href = "kontrol.php?islem=ayarlar&alt=mail";</script>';
	}
	/*
	  if (isset($_POST["nick_gönder"]))
	  {
	  $pwd = $_POST["pwd"];
	  $newnick = $_POST["yeni_nick"];
	  $uname = $yazar;

	  if(preg_match("/^\s*$/",$newnick))
	  die("boş nick girdiniz lütfen tekrar deneyin");

	  if($_POST["yeni_nick"] != $_POST["yeni_nick_tekrar"])
	  die("girdiğiniz nickler eşleşmiyor bir daha kontrol edin");

	  $sql="SELECT user_nick FROM tbl_users WHERE user_nick= '". $uname ."' AND user_pwd = '".$pwd."'";
	  $result = mysqli_query($sql,$conn);
	  if(mysqli_num_rows($result)==1)
	  {
	  $sql="UPDATE tbl_users SET user_nick = '".$newnick."'WHERE user_nick= '". $uname ."' AND user_pwd = '".$pwd."'";
	  if(mysqli_query($sql,$conn))
	  {

	  $_SESSION["nick"] = $newnick;

	  //tüm tablolarda update işlemi yapılması lazım

	  //badiler değişecek artık
	  $sql="UPDATE tbl_badi SET badi_yazar = '".$newnick."'WHERE badi_yazar= '". $uname ."'";
	  mysqli_query($sql,$conn);

	  $sql="UPDATE tbl_badi SET badi_kimle = '".$newnick."'WHERE badi_kimle= '". $uname ."'";
	  mysqli_query($sql,$conn);

	  //duyuru tablosunda bu muhtereme ait bir kayıt olabilir
	  $sql="UPDATE tbl_duyuru SET duyuru_kimden = '".$newnick."'WHERE duyuru_kimden= '". $uname ."'";
	  mysqli_query($sql,$conn);

	  //entryler yeni sahibine
	  $sql="UPDATE tbl_entries SET entry_yazar = '".$newnick."'WHERE entry_yazar= '". $uname ."'";
	  mysqli_query($sql,$conn);

	  //ispiyonculuk
	  $sql="UPDATE tbl_ispiyon SET isp_kim = '".$newnick."'WHERE isp_kim= '". $uname ."'";
	  mysqli_query($sql,$conn);

	  //kenar entryler
	  $sql="UPDATE tbl_kenar SET entry_yazar = '".$newnick."'WHERE entry_yazar= '". $uname ."'";
	  mysqli_query($sql,$conn);

	  //mesajlar
	  $sql="UPDATE tbl_mesaj SET msg_kimden = '".$newnick."'WHERE msg_kimden= '". $uname ."'";
	  mysqli_query($sql,$conn);

	  $sql="UPDATE tbl_mesaj SET msg_kime = '".$newnick."'WHERE msg_kime= '". $uname ."'";
	  mysqli_query($sql,$conn);

	  //ukte
	  $sql="UPDATE tbl_ukte SET entry_yazar = '".$newnick."'WHERE entry_yazar= '". $uname ."'";
	  mysqli_query($sql,$conn);

	  echo "nickiniz başarıyla değişti";
	  }
	  }
	  else
	  {
	  echo "şifrenizi doğru yazdığınızdan emin misiniz?";
	  }

	  echo '<script>parent.frames["main"].location.href = "kontrol.php?islem=ayarlar&alt=nick";</script>';
	  }
	 */

	switch ($islem) {
	    case "mesaj":

		$sql = "UPDATE tbl_users SET user_sonmesajokuma ='" . date("Y-m-d H:i:s") . "' WHERE user_nick = '" . mysqli_real_escape_string($yazar) . "'";
		mysqli_query($sql, $conn);

		$sql = "SELECT * FROM tbl_mesaj WHERE msg_kime = '" . mysqli_real_escape_string($yazar) . "' OR msg_kimden = '" . mysqli_real_escape_string($yazar) . "'ORDER BY msg_zaman DESC";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) == 0) {
		    ?>
	        <tr>
	    	<td align="center" colspan="4"> --- o ----
	        </tr></td>
	    <tr>
	        <td align="center" colspan="4"> hiç mesajınız yok
	    </tr></td>
	    <?php
	    }
	    else {
	    ?>
	    <form name="mesajlar" action="kontrol.php" method="post">
		<?php
		while ($row = mysqli_fetch_array($result)) {
		    ?>
		    <tr>
			<td align="center" colspan="4"> --- o ----
		    </tr></td>
		    <tr>
			<td align="justify" colspan="4">
			    <?php if ($row["msg_kime"] == $yazar) { ?>
		    	    <input type="checkbox" name="mesaj[]" value="<?php echo $row["msg_msgid"]; ?>"/>
				<?php
			    }
			    echo $row["msg_kimden"] . " --> " . $row["msg_kime"];
			    ?>
			    <br/>
			    <?php
			    if (preg_match('/\(#[0-9]*\)/', $row["msg_icerik"], $eslesmeler))
				echo str_replace($eslesmeler[0], "<a href='goster.php?msgid=" . substr($eslesmeler[0], 2, strlen($eslesmeler[0]) - 3) . "'>" . $eslesmeler[0] . "</a>", $row["msg_icerik"]);
			    else
				echo $row["msg_icerik"];
			    ?></td>
		    </tr>
		    <tr>
			<td align="left"><input name="button" type="button"
						onclick="popup('msg.php?kime=<?php echo $row["msg_kimden"]; ?>');" value="cevapla"
						title="yazara mesaj atın"/></td>
			<td align="right" colspan="4"><a href="goster.php?konu=<?php echo $row["msg_kimden"]; ?>" target="main"
							 style="WORD-BREAK:keep-words;text-decoration:none;color:#000;"
							 onmouseover='this.style.background = "yellow";'
							 onmouseout='this.style.background = "none";'><?php echo $row["msg_kimden"]; ?></a>
			    @ <?php echo date("d-m-Y H:i:s", strtotime($row["msg_zaman"])); ?></td>
		    </tr>
		    <?php
		    }
		    ?>
		    <tr>
			<td align="left" colspan="4"><input type="submit" name="mesajsil" value="mesajları sil"/></td>
		    </tr><?php
		}

		break;

		case "badiler":

		$sql = "SELECT badi_kimle FROM tbl_badi WHERE badi_yazar = '" . mysqli_real_escape_string($yazar) . "' ORDER BY badi_kimle";
		$result = mysqli_query($conn, $sql);
		?>
	        <form name="badiler" action="kontrol.php" method="get">
	    	<tr>
	    	    <td colspan="4"><?php
	    		while ($row = mysqli_fetch_array($result))
	    		{
	    		?>
	    		<input type="checkbox" name="badi[]"
	    		       value="<?php echo $row["badi_kimle"]; ?>"/> <?php echo $row["badi_kimle"]; ?> <br/>
	    		<?php }?>
	    	    </td>
	    	</tr>
	    	<tr>
	    	    <td colspan="4">
	    		<input type="text" name="nick" value="<?php echo $_GET["badiisim"]; ?>" maxlength="255"/>
	    		<input type="submit" name="but" value="badi ekle"/>
	    		<input type="submit" name="but" value="seçilileri sil"/>
	    		<input type="hidden" name="islem" value="<?php echo $_GET["islem"]; ?>"/>
	    	    </td>
	    	</tr>
	        </form>
		<?php
		break;
		case "ayarlar":
		?>
	        <tr>
	    	<td align="center" colspan="4"> --- o ----</td>
	        </tr>
	        <tr>
	    	<td align="center">
	    	    <a href="kontrol.php?islem=ayarlar&alt=şifre" target="main"
	    	       style="WORD-BREAK:keep-words;text-decoration:none;color:#000;"
	    	       onmouseover='this.style.background = "yellow";' onmouseout='this.style.background = "none";'>| şifre
	    		değiştir |
	    	    </a>
	    	</td>

	    	<td align="center">
	    	    <a href="kontrol.php?islem=ayarlar&alt=mail" target="main"
	    	       style="WORD-BREAK:keep-words;text-decoration:none;color:#000;"
	    	       onmouseover='this.style.background = "yellow";' onmouseout='this.style.background = "none";'>| e-mail
	    		adresi değiştir |
	    	    </a>
	    	</td>

	        </tr>
	        <?php
	        $alt = $_GET["alt"];

	        switch ($alt)
	        {

	        case "şifre":
	        ?>
	        <tr>
	    	<td colspan="4">
	    	    <form name="şifredeğiş" action="kontrol.php" method="post">
	    		şu anki şifreniz:<br/>
	    		<input type="password" name="şifre" size="30px" maxlength="25"/><br/>
	    		yeni şifre: (4-25 karakter)<br/>
	    		<input type="password" name="yeni_şifre" size="30px" maxlength="25"/><br/>
	    		yeni şifre (tekrar):<br/>
	    		<input type="password" name="yeni_tekrar" size="30px" maxlength="25"/><br/>
	    		<input type="submit" name="şifre_gönder" value="gönder"/>
	    	    </form>
	    	</td>
	        </tr>
	        <?php
	        break;

	        case "mail":
	        ?>
	        <tr>
	    	<td colspan="4">
	    	    <form name="maildeğiş" action="kontrol.php" method="post">
	    		şu anki şifreniz:<br/>
	    		<input type="password" name="pwd" size="30px"/><br/>
	    		yeni mail:<br/>
	    		<input type="text" name="yeni_mail" size="30px"/><br/>
	    		yeni mail (tekrar):<br/>
	    		<input type="text" name="yeni_mail_tekrar" size="30px"/><br/>
	    		<input type="submit" name="mail_gönder" value="gönder"/>
	    	    </form>
	    	</td>
	        </tr>
	        <?php
	        break;
	        /*
	        case "nick":
	        ?>
	        <tr><td colspan="4">
	    	    <form name="nickdeğiş" action="kontrol.php" method="post">
	    		şu anki şifreniz:<br/>
	    		<input type="password" name="pwd" size="30px" maxlength="25"/><br/>
	    		yeni nickiniz: (max 255 karakter)<br/>
	    		<input type="text" name="yeni_nick" size="30px" maxlength="255"/><br/>
	    		yeni nickiniz (tekrar):<br/>
	    		<input type="text" name="yeni_nick_tekrar" size="30px" maxlength="255"/><br/>
	    		<input type="submit" name="nick_gönder" value="gönder" />
	    	    </form>
	    	</td></tr>
	        <?
	        break;
	        */
	        }

	        break;


	        default:

	        $sql = "SELECT * FROM tbl_duyuru WHERE 1 ORDER BY duyuru_zaman";
	        $result = mysqli_query($conn, $sql);

	        while ($row = mysqli_fetch_array($result))
	        {
	        ?>
	        <tr>
	    	<td align="center" colspan="4"> --- o ----</td>
	        </tr>
	        <tr>
	    	<td align="justify" colspan="4"><?php echo $row["duyuru_icerik"]; ?></td>
	        </tr>
	        <tr>
	    	<td align="right" colspan="4"><a href="goster.php?konu=<?php echo $row["duyuru_kimden"]; ?>" target="main"
	    					 style="WORD-BREAK:keep-words;text-decoration:none;color:#000;"
	    					 onmouseover='this.style.background = "yellow";'
	    					 onmouseout='this.style.background = "none";'><?php echo $row["duyuru_kimden"]; ?></a>
	    	    @ <?php echo $row["duyuru_zaman"]; ?></td>
    </tr>
    <?php
    }

    break;
    }
    mysqli_close($conn);
    ?>
</table>
</body>