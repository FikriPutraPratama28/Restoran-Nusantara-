<?php
$html = file_get_contents('http://localhost/smart-digital-restaurant-experience/smart-digital-restaurant-experience/public/');
// Cari semua link/script tags
preg_match_all('/<link[^>]+href=["\']([^"\']+)["\'][^>]*>/i', $html, $links);
preg_match_all('/<script[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $html, $scripts);
echo "=== CSS/LINK TAGS ===" . PHP_EOL;
foreach ($links[1] as $l) { if (str_contains($l, 'app') || str_contains($l, 'build')) echo $l . PHP_EOL; }
echo "=== JS SCRIPT TAGS ===" . PHP_EOL;
foreach ($scripts[1] as $s) { if (str_contains($s, 'app') || str_contains($s, 'build')) echo $s . PHP_EOL; }
