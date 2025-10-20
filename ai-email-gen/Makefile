# ======================================================
#  Makefile for AI Email Generator (Podman + Ollama + PHP)
#  Windows + PowerShell Compatible
# ======================================================

IMAGE_NAME      := ai-email-gen
CONTAINER_NAME  := ollama-qwen-slim
PORT            := 11434
MODEL           := qwen3:14b
PHP_PORT        := 8080

# Windows-safe volume path
VOLUME_PATH     := $(shell powershell -Command "(Resolve-Path .\\ollama-data).Path")

.PHONY: all build run pull tags clean status serve setup check-podman

# ------------------------------------------------------
# Check for Podman installation
# ------------------------------------------------------
check-podman:
	@powershell -Command "if (-not (Get-Command podman -ErrorAction SilentlyContinue)) { \
		Write-Host '❌ Podman is not installed. Please install it from https://podman.io/getting-started/installation'; \
		exit 1; \
	} else { Write-Host '✅ Podman detected.' }"

# ------------------------------------------------------
# Default target
# ------------------------------------------------------
all: build run status

# ------------------------------------------------------
# Build Ollama container
# ------------------------------------------------------
build:
	podman build -t $(IMAGE_NAME) .

# ------------------------------------------------------
# Run container detached with port + volume
# ------------------------------------------------------
run:
	@if not exist "ollama-data" mkdir ollama-data
	-podman stop $(CONTAINER_NAME) >NUL 2>&1 || powershell -Command "exit 0"
	-podman rm $(CONTAINER_NAME) >NUL 2>&1 || powershell -Command "exit 0"
	podman run -d -p $(PORT):11434 \
		-v "$(VOLUME_PATH):/root/.ollama" \
		--name $(CONTAINER_NAME) $(IMAGE_NAME)

# ------------------------------------------------------
# Pull model into container
# ------------------------------------------------------
pull:
	podman exec -it $(CONTAINER_NAME) ollama pull $(MODEL)

# ------------------------------------------------------
# Check models via API
# ------------------------------------------------------
tags:
	curl.exe http://localhost:$(PORT)/api/tags

# ------------------------------------------------------
# Stop & remove container
# ------------------------------------------------------
clean:
	-podman stop $(CONTAINER_NAME) >NUL 2>&1 || powershell -Command "exit 0"
	-podman rm $(CONTAINER_NAME) >NUL 2>&1 || powershell -Command "exit 0"

# ------------------------------------------------------
# Show running containers
# ------------------------------------------------------
status:
	podman ps

# ------------------------------------------------------
# Start local PHP server (Windows safe)
# ------------------------------------------------------
serve:
	@echo Starting PHP server on http://localhost:$(PHP_PORT)
	powershell -Command "Start-Process powershell -ArgumentList '-NoExit','-Command','php -S localhost:$(PHP_PORT)'"

# ------------------------------------------------------
# Run the Python model pull script
# ------------------------------------------------------
pull-model:
	@echo Running local Python pull_model.py ...
	@python pull_model.py

# ------------------------------------------------------
# Full setup automation
# ------------------------------------------------------
setup: check-podman build run
	@echo Running pull_model.py to verify model ...
	@python pull_model.py || echo "Could not run pull_model.py — check Python installation."
	@echo Now pulling model inside the container ...
	@podman exec -it $(CONTAINER_NAME) ollama pull $(MODEL)
	@echo Setup complete! Run 'make serve' to open the web interface.