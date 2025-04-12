# Makefile

.PHONY: run

run:
	@if [ -z "$(DOCUMENT)" ]; then \
		echo "❌ エラー: ドキュメント名を指定してください（例: make run DOCUMENT=document_name）"; \
		exit 1; \
	fi
	@php script/merge_markdown.php $(DOCUMENT)