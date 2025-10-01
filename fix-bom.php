<?php
/**
 * Script loại bỏ BOM khỏi tất cả file PHP trong class/Providers/
 */

$dir = __DIR__ . '/class/Providers/';

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($files as $file) {
    if ($file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());

        // Nếu file bắt đầu bằng BOM (EF BB BF)
        if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
            $newContent = substr($content, 3);
            file_put_contents($file->getPathname(), $newContent);
            echo "Đã xóa BOM trong file: " . $file->getFilename() . PHP_EOL;
        }
    }
}

echo "✅ Hoàn tất kiểm tra và xử lý BOM.\n";
