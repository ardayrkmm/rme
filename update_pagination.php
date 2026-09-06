<?php
$dir = 'e:/Bisnis/RekamMedis/backend_rekammedis/app/Http/Controllers/Api/V1';
$files = glob($dir . '/*.php');
foreach($files as $file) {
    $content = file_get_contents($file);
    $newContent = preg_replace('/(\$perPage = \$request->input\(\'per_page\', (\d+)\));/', '$perPage = min(100, (int) $request->input(\'per_page\', $2));', $content);
    if ($content !== $newContent) {
        file_put_contents($file, $newContent);
        echo "Updated " . basename($file) . "\n";
    }
}
