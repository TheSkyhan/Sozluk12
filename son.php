<?php
require_once('header.php');

// Veritabanı bağlantısı
$mysqli = new mysqli("localhost", "root", "gokhan", "sozluk");

// Bağlantı kontrolü
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Tarihleri hesapla
$birayonce = strtotime(date("Y-m-d")) - 2592000;
$bugunstr = date("Y-m-d", $birayonce) . " 00:00:00";
$yarinstr = date("Y-m-d") . " 23:59:59";

// Kullanıcı adını al
$nick = $_SESSION["nick"];

// Hazırlanmış ifadeyle SQL sorgusu
$sql = "SELECT entry_baslik, MAX(entry_giristarihi) AS entry_giristarihi
        FROM tbl_entries
        WHERE entry_giristarihi BETWEEN ? AND ? AND entry_yazar = ?
        GROUP BY entry_baslik
        ORDER BY entry_giristarihi DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sss", $bugunstr, $yarinstr, $nick);
$stmt->execute();
$result = $stmt->get_result();
?>
<table border="0" width="90%" align="left">
    <tr>
        <td align="left" style="font-size:8 pt;text-align:center;">
            <?php echo "senden sonra .. (" . mysqli_num_rows($result) . " başlık)"; ?>
        </td>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td align="left" class="left-menu">
            <a href="goster.php?konu=<?= urlencode($row["entry_baslik"]); ?>&zaman=<?= $row["entry_giristarihi"]; ?>" target="main">
                <?php echo htmlspecialchars($row["entry_baslik"]); ?>
            </a><br/>
        </td>
    </tr>
    <?php } ?>
</table>
<?php
$stmt->close();
$mysqli->close();
?>
</body>
</html>
