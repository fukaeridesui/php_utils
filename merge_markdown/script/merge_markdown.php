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

// 引数で document_name を取得
$docName = $argv[1] ?? null;
if (!$docName) {
    echo "❌ エラー: ドキュメント名が指定されていません。\n";
    exit(1);
}

// 入力ディレクトリ：project_root/documents/document_name
$inputDirectory = __DIR__ . '/../documents/' . $docName;
if (!is_dir($inputDirectory)) {
    echo "❌ エラー: 入力ディレクトリが存在しません: $inputDirectory\n";
    exit(1);
}

// 出力ファイル名（タイムスタンプ付き）
$timestamp = date('Ymd_His');
$outputDir = __DIR__ . '/../result/' . $docName;
$outputFile = $outputDir . "/merged_{$timestamp}.md";

mergeMarkdownFiles($inputDirectory, $outputFile);