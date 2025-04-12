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

    echo "✅ Merge completed: $outputFile\n";
}

$docName = $argv[1] ?? null;
if (!$docName) {
    echo "❌ Error: Document name is required. Example: php merge_markdown.php document_name\n";
    exit(1);
}

$inputDirectory = __DIR__ . '/../documents/' . $docName;
if (!is_dir($inputDirectory)) {
    echo "❌ Error: Input directory not found: $inputDirectory\n";
    exit(1);
}

$timestamp = date('Ymd_His');
$outputDir = __DIR__ . '/../result/' . $docName;
$outputFile = $outputDir . "/merged_{$timestamp}.md";

mergeMarkdownFiles($inputDirectory, $outputFile);