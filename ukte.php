<?php require_once('header.php');

$login = isset($_SESSION["nick"]);
if (!$login)
    die("giriş yapmamanız bize ukte oldu :)");


$sql = "SELECT entry_baslik
			FROM tbl_ukte
			ORDER BY entry_giristarihi DESC";

$result = mysqli_query($conn, $sql);
?>
<table border="0" width="90%" align="left"> <?php
    if (mysqli_num_rows($result) == 0) {
        ?>
        <tr>
            <td align="left">sözlükte hiç ukte yok!!!</td>
        </tr>
        <?php } else { ?>
        <tr>
            <td align="left" style="font-size:8 pt;;text-align:center;text-decoration:none;">doldurulmayı bekleyen
                ukteler
            </td>
        </tr><?php
        while ($row = mysqli_fetch_array($result))
        {
            ?>
            <tr>
                <td align="left" class="left-menu">
                    <a href="goster.php?konu=<?php echo urlencode($row["entry_baslik"]);?>"
                       target="main"> <?php echo $row["entry_baslik"];?> </a><br/>
                </td>
            </tr>
            <?php }
    }?>
</table>
<?php

mysqli_close($conn);

?>
</body>
</html>