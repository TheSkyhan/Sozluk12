<?php
require_once('header.php');

if (isset($_GET["q"])) {
    $sayfa = $_GET["q"];

    switch ($sayfa) {
        case "sub-etha":
            echo "fasilite mi? fasilite ne arar la bazarda";
            break;

        case "kontrol merkezi":
            require("kontrol.php");
            break;

        case "iletişim":
            require("iletisim.php");
            break;

        case "ben":
            echo "ben get verisi aldım";
            break;

        case "?":
            require("saibe.php");
            break;

        case ":)":
            require("sukela.php");
            break;

        case "yeni kullanıcı":
            require("yeni_kullanici.php");
            break;

        case "istatistikler":
            require("istatistikler.php");
            break;

        default:
            echo "ben main default action.";
    }
} else {
    // SQL sorgusu
    $sql = "SELECT entry_baslik FROM tbl_entries ORDER BY RAND() LIMIT 1";

    // Sorguyu çalıştırma
    $result = mysqli_query($conn, $sql);

    // Sorgu sonucunu kontrol etme
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        // JavaScript ile yönlendirme
        echo "<script>";
        echo "window.location='goster.php?konu=" . urlencode($row["entry_baslik"]) . "';";
        echo "</script>";
    } else {
        echo "Veri bulunamadı.";
    }

    // Bağlantıyı kapatma
    mysqli_close($conn);
}
?>
</body>
</html>
