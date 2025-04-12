<?php

function mergeMarkdownFiles($inputDir, $outputFile)
{
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($inputDir));
    $markdownFiles = [];

    foreach ($rii as $file) {
        if (
            $file->isFile() &&
            strtolower($file->getExtension()) === 'md' &&
            strtolower($file->getBasename()) !== 'readme.md'
        ) {
            $markdownFiles[] = $file->getPathname();
        }
    }

    sort($markdownFiles);

    $outputDir = dirname($outputFile);
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0777, true);
    }

    $output = fopen($outputFile, 'w');

    foreach ($markdownFiles as $filePath) {
        $filename = basename($filePath);
        fwrite($output, "\n\n# {$filename}\n\n");
        fwrite($output, file_get_contents($filePath));
        fwrite($output, "\n\n---\n");
    }

    fclose($output);
    echo "✅ 統合完了: $outputFile\n";
}

// 入力ディレクトリ（引数で指定 or デフォルト）
$inputDirectory = $argv[1] ?? __DIR__;
if (!is_dir($inputDirectory)) {
    echo "❌ エラー: 指定されたディレクトリが存在しません。\n";
    exit(1);
}

// document_name（例: document_name）を取得
$documentName = basename(realpath($inputDirectory));

// タイムスタンプ（例: 20250410_153012）
$timestamp = date('Ymd_His');

// 出力ファイルパス: result/document_name/merged_YYYYMMDD_HHMMSS.md
$outputDir = __DIR__ . '/../result/' . $documentName;
$outputFile = $outputDir . "/merged_{$timestamp}.md";

mergeMarkdownFiles($inputDirectory, $outputFile);