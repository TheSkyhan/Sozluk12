<?php

    require_once('header.php'); 


    $sql = "SELECT *
            FROM tbl_entries
            WHERE entry_iyi > 0
            ORDER BY RAND()
            LIMIT 1";

    $result = mysqli_query($conn, $sql); // Sorgu ve bağlantıyı doğru şekilde geçiyoruz

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        echo '<script>window.location="goster.php?konu=' . urlencode($row["entry_baslik"]) . '&msgid=' . $row["entry_id"] . '"</script>';
    } else {
        echo 'No entries found.';
    }

    mysqli_close($conn);
?>
