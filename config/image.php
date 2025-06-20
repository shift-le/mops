<?php

return [
    'thumbnail_path' => 'storage/images/thumbnails', // 公開URLで使用
    'thumbnail_disk_path' => 'public/images/thumbnails', // Storage::disk()->put() 用

    'pdf_path' => 'storage/images/pdfs',
    'pdf_disk_path' => 'public/images/pdfs',
];
