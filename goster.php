<?php
session_start();

// Hata raporlama
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Veritabanı bağlantısı
require_once 'config.php';
$db = new Database();
$conn = $db->getConnection();

// Yardımcı fonksiyonlar
require_once 'helpers.php';

// Header'ı dahil et
require_once('header.php');

// Kullanıcı yönetimi
$userManager = new UserManager($conn);
$isLoggedIn = $userManager->isLoggedIn();
$currentUser = $isLoggedIn ? $userManager->getCurrentUser() : null;

// Entry yönetimi
$entryManager = new EntryManager($conn);

// Konu ve diğer parametreleri al
$konu = isset($_GET["konu"]) ? strtolower_tr($_GET["konu"]) : '';
$msgId = isset($_GET["msgid"]) ? intval($_GET["msgid"]) : null;
$keyword = isset($_GET["kw"]) ? strtolower_tr($_GET["kw"]) : null;

// Entry ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isLoggedIn) {
    $entryManager->handleEntrySubmission($_POST, $currentUser);
}

// Entryleri getir
$entries = $entryManager->getEntries($konu, $msgId, $keyword);

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($konu) . " - Arşiv Sözlük"; ?></title>
    <meta name="description" content="<?php echo e($konu) . " hakkında sözlük yazarlarının düşündükleri"; ?>"/>
    <meta name="keywords" content="<?php echo e(implode(", ", explode(" ", $konu))); ?>"/>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="scripts.js"></script>
</head>

<body>
    <div class="container">
        <h1><?php echo formatTitle($konu); ?></h1>

        <?php if (empty($entries)): ?>
            <?php echo $entryManager->handleEmptyEntries($konu, $isLoggedIn, $currentUser); ?>
        <?php else: ?>
            <div class="entries">
                <?php foreach ($entries as $index => $entry): ?>
                    <?php echo renderEntry($entry, $index, $isLoggedIn, $currentUser); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($isLoggedIn): ?>
            <?php echo renderEntryForm($konu); ?>
        <?php endif; ?>
    </div>

    <script>
        $(document).ready(function() {
            initializeEntryFunctions();
        });
    </script>
</body>
</html>
