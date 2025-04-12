# Makefile

.PHONY: run

# Run the Markdown merge script
# Usage: make run DOCUMENT=document_name
run:
	@if [ -z "$(DOCUMENT)" ]; then \
		echo "❌ Error: Please specify a document name. Example: make run DOCUMENT=your_document"; \
		exit 1; \
	fi
	@php script/merge_markdown.php $(DOCUMENT)