<?php
require_once('header.php');

$baslangic = strtotime("2016-03-08");
$bitis = strtotime(date("Y-m-d")) + 86399;

$random = mt_rand($baslangic, $bitis);
$bugun = date("Y-m-d", $random);

// Tarih aralıkları oluşturuluyor
$bugunstr = $bugun . " 00:00:00";
$yarinstr = $bugun . " 23:59:59";

// SQL sorgusu düzenleniyor
$sql = "SELECT entry_baslik, COUNT(entry_baslik) as toplam
        FROM (SELECT entry_baslik, entry_giristarihi FROM tbl_entries 
              WHERE entry_giristarihi BETWEEN '$bugunstr' AND '$yarinstr' 
              ORDER BY entry_giristarihi DESC) AS tablo
        GROUP BY entry_baslik 
        ORDER BY toplam DESC";

// Veritabanı sorgusu gerçekleştiriliyor
$result = mysqli_query($conn, $sql);

if ($result === false) {
    die("Veritabanı hatası: " . mysqli_error($conn));
}
?>
<table border="0" width="90%" align="left">
    <tr>
        <td style="font-size:8pt;text-align:center;">
            <?php echo date("d-m-Y", strtotime($bugun)) . " .. (" . mysqli_num_rows($result) . " başlık)"; ?>
        </td>
    </tr>
    <?php while ($row = mysqli_fetch_array($result)) { ?>
        <tr>
            <td align="left" class="left-menu">
                <a href="goster.php?konu=<?=urlencode($row["entry_baslik"]);?>" target="main">
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
</body>
