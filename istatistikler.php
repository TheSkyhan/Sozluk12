<?php
require_once('header.php');

// Toplam Entry Sayısı
$sql = "SELECT COUNT(entry_id) AS toplam FROM tbl_entries";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
$toplamEntry = $row["toplam"];

// Toplam Başlık Sayısı
$sql = "SELECT DISTINCT entry_baslik FROM tbl_entries";
$result = mysqli_query($conn, $sql);
$toplamBaslik = mysqli_num_rows($result);

// Toplam Kullanıcı Sayısı
$sql = "SELECT COUNT(user_email) AS kullanici FROM tbl_users";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
$toplamKullanici = $row["kullanici"];

// İstatistik Hesaplamaları
$baslikBasiEntry = $toplamBaslik ? floor($toplamEntry / $toplamBaslik) : 0;
$yazarBasiBaslik = $toplamKullanici ? floor($toplamBaslik / $toplamKullanici) : 0;
$yazarBasiEntry = $toplamKullanici ? floor($toplamEntry / $toplamKullanici) : 0;

// Son 7 Günün En Çok Entry Giren 10 Yazarı
$gecenhafta = strtotime(date("Y-m-d")) - 604800;
$gecenhaftabugun = date("Y-m-d", $gecenhafta);
$baslangicstr = $gecenhaftabugun . " 00:00:00";
$bitisstr = date("Y-m-d");

$sql = "SELECT entry_yazar, COUNT(entry_yazar) AS toplam
        FROM tbl_entries 
        WHERE entry_giristarihi BETWEEN '$baslangicstr' AND '$bitisstr'
        GROUP BY entry_yazar
        HAVING toplam > 0
        ORDER BY toplam DESC
        LIMIT 10";
$result = mysqli_query($conn, $sql);
$numrows = mysqli_num_rows($result);
?>

<table width="50%" align="left" border="0">
    <thead>
        <tr>
            <th colspan="2">Sözlüğümüzle ilgili rakamsal veriler</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td width="50%">Sözlükteki Toplam Entry Sayısı:</td>
            <td width="50%"><?php echo $toplamEntry; ?></td>
        </tr>
        <tr>
            <td width="50%">Sözlükteki Toplam Başlık Sayısı:</td>
            <td width="50%"><?php echo $toplamBaslik; ?></td>
        </tr>
        <tr>
            <td width="50%">Sözlükteki Toplam Kullanıcı Sayısı:</td>
            <td width="50%"><?php echo $toplamKullanici; ?></td>
        </tr>
        <tr>
            <td width="50%">Başlık Başına Düşen Entry Sayısı:</td>
            <td width="50%"><?php echo $baslikBasiEntry; ?></td>
        </tr>
        <tr>
            <td width="50%">Yazar Başına Düşen Başlık Sayısı:</td>
            <td width="50%"><?php echo $yazarBasiBaslik; ?></td>
        </tr>
        <tr>
            <td width="50%">Yazar Başına Düşen Entry Sayısı:</td>
            <td width="50%"><?php echo $yazarBasiEntry; ?></td>
        </tr>
        <tr>
            <td width="50%" rowspan="<?php echo $numrows; ?>" style="vertical-align:top;">Son 7 Günde En Çok Entry Giren 10 Yazar:</td>
            <td width="50%" class="left-menu">
                <?php if ($row = mysqli_fetch_array($result)) { ?>
                    <a href="goster.php?konu=<?php echo $row["entry_yazar"]; ?>" target="main">
                        <?php echo $row["entry_yazar"]; ?> - <?php echo $row["toplam"]; ?> entry
                    </a>
                <?php } ?>
            </td>
        </tr>
        <?php while ($row = mysqli_fetch_array($result)) { ?>
            <tr>
                <td width="50%" class="left-menu">
                    <a href="goster.php?konu=<?php echo $row["entry_yazar"]; ?>" target="main">
                        <?php echo $row["entry_yazar"]; ?> - <?php echo $row["toplam"]; ?> entry
                    </a>
                </td>
            </tr>
        <?php } ?>

        <?php
        // En Beğenilen 10 Entry
        $sql = "SELECT entry_baslik, entry_id, entry_iyi
                FROM tbl_entries 
                WHERE entry_iyi > 0
                ORDER BY entry_iyi DESC
                LIMIT 10";
        $result = mysqli_query($conn, $sql);
        $numrows = mysqli_num_rows($result);
        ?>

        <tr>
            <td width="50%" rowspan="<?php echo $numrows; ?>" style="vertical-align:top;">En Beğenilen 10 Entry:</td>
            <td width="50%" class="left-menu">
                <?php if ($row = mysqli_fetch_array($result)) { ?>
                    <a href="goster.php?konu=<?php echo $row["entry_baslik"]; ?>&msgid=<?php echo $row["entry_id"]; ?>" target="main">
                        <?php echo $row["entry_baslik"] . " / #" . $row["entry_id"] . " / " . $row["entry_iyi"] . " oy"; ?>
                    </a>
                <?php } ?>
            </td>
        </tr>
        <?php while ($row = mysqli_fetch_array($result)) { ?>
            <tr>
                <td width="50%" class="left-menu">
                    <a href="goster.php?konu=<?php echo $row["entry_baslik"]; ?>&msgid=<?php echo $row["entry_id"]; ?>" target="main">
                        <?php echo $row["entry_baslik"] . " / #" . $row["entry_id"] . " / " . $row["entry_iyi"] . " oy"; ?>
                    </a>
                </td>
            </tr>
        <?php } ?>

        <?php
        // En Kötülenen 10 Entry
        $sql = "SELECT entry_baslik, entry_id, entry_kotu
                FROM tbl_entries 
                WHERE entry_kotu > 0
                ORDER BY entry_kotu DESC
                LIMIT 10";
        $result = mysqli_query($conn, $sql);
        $numrows = mysqli_num_rows($result);
        ?>

        <tr>
            <td width="50%" rowspan="<?php echo $numrows; ?>" style="vertical-align:top;">En Kötülenen 10 Entry:</td>
            <td width="50%" class="left-menu">
                <?php if ($row = mysqli_fetch_array($result)) { ?>
                    <a href="goster.php?konu=<?php echo $row["entry_baslik"]; ?>&msgid=<?php echo $row["entry_id"]; ?>" target="main">
                        <?php echo $row["entry_baslik"] . " / #" . $row["entry_id"] . " / " . $row["entry_kotu"] . " oy"; ?>
                    </a>
                <?php } ?>
            </td>
        </tr>
        <?php while ($row = mysqli_fetch_array($result)) { ?>
            <tr>
                <td width="50%" class="left-menu">
                    <a href="goster.php?konu=<?php echo $row["entry_baslik"]; ?>&msgid=<?php echo $row["entry_id"]; ?>" target="main">
                        <?php echo $row["entry_baslik"] . " / #" . $row["entry_id"] . " / " . $row["entry_kotu"] . " oy"; ?>
                    </a>
                </td>
            </tr>
        <?php } ?>

        <?php
        // En Çok Entry İçeren 10 Başlık
        $sql = "SELECT entry_baslik, COUNT(entry_id) AS toplam
                FROM tbl_entries
                GROUP BY entry_baslik
                ORDER BY toplam DESC
                LIMIT 10";
        $result = mysqli_query($conn, $sql);
        $numrows = mysqli_num_rows($result);
        ?>

        <tr>
            <td width="50%" rowspan="<?php echo $numrows; ?>" style="vertical-align:top;">En Çok Entry İçeren 10 Başlık:</td>
            <td width="50%" class="left-menu">
                <?php if ($row = mysqli_fetch_array($result)) { ?>
                    <a href="goster.php?konu=<?php echo $row["entry_baslik"]; ?>" target="main">
                        <?php echo $row["entry_baslik"] . " / " . $row["toplam"] . " entry"; ?>
                    </a>
                <?php } ?>
            </td>
        </tr>
        <?php while ($row = mysqli_fetch_array($result)) { ?>
            <tr>
                <td width="50%" class="left-menu">
                    <a href="goster.php?konu=<?php echo $row["entry_baslik"]; ?>" target="main">
                        <?php echo $row["entry_baslik"] . " / " . $row["toplam"] . " entry"; ?>
                    </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php
mysqli_close($conn);
?>
