<?php 
 if (isset($_GET['qrcode_docs_id']) && isset($_GET['qrcode_docs_link'])) {
    header("Content-Type: image/png");
    $imgsrc = media_root."/docs/{$_GET['qrcode_docs_link']}";
    $domain = MY_DOMAIN;
    $text = "/$imgsrc";
    $ecc = 'H';
    $pixel_size = 15;
    $frame_size = 2;
    // Generates QR Code and Save as PNG
    QRcode::png($text, $file=false, $ecc, $pixel_size, $frame_size);
    return;
    }