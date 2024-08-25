<?php
require_once('header.php');

// SQL Sorgusu
$sql = "SELECT entry_baslik, COUNT(entry_baslik) AS toplam
        FROM tbl_entries 
        GROUP BY entry_baslik
        ORDER BY MAX(entry_giristarihi) DESC
        LIMIT 100";

// Sorguyu Çalıştır
$result = mysqli_query($conn, $sql);
?>

<table border="0" width="90%" align="left" marginheight="0" marginwidth="0">
    <tr>
        <td align="left"
            style="font-size:8pt;text-align:center;text-decoration:none;">
            <?php echo "Son " . mysqli_num_rows($result) . " başlık"; ?>
        </td>
    </tr>
    <?php while ($row = mysqli_fetch_array($result)) { ?>
        <tr>
            <td align="left" class="left-menu">
                <a href="goster.php?konu=<?= urlencode($row["entry_baslik"]); ?>"
                   target="main">
                   <?php echo $row["entry_baslik"] . " (" . $row["toplam"] . ")"; ?>
                   <hr>
                </a>
            </td>
        </tr>
    <?php } ?>
</table>

<?php
mysqli_close($conn);
?>
