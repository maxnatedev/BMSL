<?php
echo 'PHP: ' . phpversion() . "\n";
echo 'GD: ' . (extension_loaded('gd') ? 'YES' : 'NO') . "\n";
echo 'Imagick: ' . (extension_loaded('imagick') ? 'YES' : 'NO') . "\n";
if (extension_loaded('gd')) {
    $info = gd_info();
    echo 'GD FreeType: ' . ($info['FreeType Support'] ? 'YES' : 'NO') . "\n";
    echo 'GD WebP: ' . ($info['WebP Support'] ? 'YES' : 'NO') . "\n";
}
echo 'mime_content_type: ' . (function_exists('mime_content_type') ? 'YES' : 'NO') . "\n";
