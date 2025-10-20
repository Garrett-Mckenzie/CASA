# ======================================================
#  Makefile for AI Email Generator (Ollama + PHP)
#  Cross-Platform: Windows / macOS / Linux
#  Auto-fallback: Docker if Podman not found
#  Auto-open browser on serve
# ======================================================

IMAGE_NAME      := ai-email-gen
CONTAINER_NAME  := ollama-qwen-slim
PORT            := 11434
MODEL           := qwen3:14b
PHP_PORT        := 8080

# ------------------------------------------------------
# Platform detection
# ------------------------------------------------------
ifeq ($(OS),Windows_NT)
    SHELL := cmd.exe
    .SHELLFLAGS := /C
    NULL := >NUL 2>&1
    MKDIR := if not exist
    RM := del /Q
    PY := python
    CURL := curl.exe
    VOLUME_PATH := $(shell powershell -Command "(Resolve-Path .\\ollama-data).Path")
    OPEN_CMD := powershell -Command "Start-Process"
    CONTAINER_ENGINE := $(shell powershell -Command "if (Get-Command podman -ErrorAction SilentlyContinue) { Write-Host podman } elseif (Get-Command docker -ErrorAction SilentlyContinue) { Write-Host docker } else { Write-Host none }")
else
    SHELL := /bin/bash
    NULL := >/dev/null 2>&1
    MKDIR := mkdir -p
    RM := rm -rf
    PY := python3
    CURL := curl
    VOLUME_PATH := $(shell pwd)/ollama-data
    OPEN_CMD := $(shell if command -v open >/dev/null 2>&1; then echo open; else echo xdg-open; fi)
    CONTAINER_ENGINE := $(shell if command -v podman >/dev/null 2>&1; then echo podman; elif command -v docker >/dev/null 2>&1; then echo docker; else echo none; fi)
endif

ifeq ($(CONTAINER_ENGINE),none)
$(error Neither Podman nor Docker found. Please install one to continue.)
endif

.PHONY: all build run pull tags clean status serve setup pull-model

# ------------------------------------------------------
# Default target
# ------------------------------------------------------
all: build run status

# ------------------------------------------------------
# Build container
# ------------------------------------------------------
build:
	$(CONTAINER_ENGINE) build -t $(IMAGE_NAME) .

# ------------------------------------------------------
# Run container detached with port + volume
# ------------------------------------------------------
run:
ifeq ($(OS),Windows_NT)
	@if not exist "ollama-data" mkdir ollama-data
	-$(CONTAINER_ENGINE) stop $(CONTAINER_NAME) $(NULL)
	-$(CONTAINER_ENGINE) rm $(CONTAINER_NAME) $(NULL)
	$(CONTAINER_ENGINE) run -d -p $(PORT):11434 -v "$(VOLUME_PATH):/root/.ollama" -e OLLAMA_HOST=0.0.0.0:11434 -e OLLAMA_KEEP_ALIVE=1h --name $(CONTAINER_NAME) $(IMAGE_NAME)
	@echo "Preloading model and starting keep-alive ping loop..."
	powershell -Command "podman exec -d $(CONTAINER_NAME) ollama generate -m $(MODEL) 'Ready check' | Out-Null"
	powershell -Command "podman exec -d $(CONTAINER_NAME) sh -c 'while true; do ollama generate -m $(MODEL) \"ping\" >/dev/null 2>&1; sleep 1800; done' | Out-Null"
else
	@$(MKDIR) ollama-data
	-$(CONTAINER_ENGINE) stop $(CONTAINER_NAME) $(NULL) || true
	-$(CONTAINER_ENGINE) rm $(CONTAINER_NAME) $(NULL) || true
	$(CONTAINER_ENGINE) run -d -p $(PORT):11434 \
		-v "$(VOLUME_PATH):/root/.ollama" \
		-e OLLAMA_HOST=0.0.0.0:11434 \
		-e OLLAMA_KEEP_ALIVE=1h \
		--name $(CONTAINER_NAME) $(IMAGE_NAME)
	@echo "Preloading model and starting keep-alive ping loop..."
	$(CONTAINER_ENGINE) exec -d $(CONTAINER_NAME) ollama generate -m $(MODEL) "Ready check" >/dev/null 2>&1
	$(CONTAINER_ENGINE) exec -d $(CONTAINER_NAME) sh -c "while true; do ollama generate -m $(MODEL) \"ping\" >/dev/null 2>&1; sleep 1800; done"
endif


# ------------------------------------------------------
# Pull model inside container
# ------------------------------------------------------
pull:
	$(CONTAINER_ENGINE) exec -it $(CONTAINER_NAME) ollama pull $(MODEL)

# ------------------------------------------------------
# Check models via API
# ------------------------------------------------------
tags:
	$(CURL) http://localhost:$(PORT)/api/tags

# ------------------------------------------------------
# Stop & remove container
# ------------------------------------------------------
clean:
ifeq ($(OS),Windows_NT)
	-$(CONTAINER_ENGINE) stop $(CONTAINER_NAME) $(NULL)
	-$(CONTAINER_ENGINE) rm $(CONTAINER_NAME) $(NULL)
else
	-$(CONTAINER_ENGINE) stop $(CONTAINER_NAME) $(NULL) || true
	-$(CONTAINER_ENGINE) rm $(CONTAINER_NAME) $(NULL) || true
endif

# ------------------------------------------------------
# Show running containers
# ------------------------------------------------------
status:
	$(CONTAINER_ENGINE) ps

# ------------------------------------------------------
# Start local PHP server (auto-open browser)
# ------------------------------------------------------
serve:
ifeq ($(OS),Windows_NT)
	@echo Starting PHP server on http://localhost:$(PHP_PORT)
	start powershell -NoExit -Command "php -S localhost:$(PHP_PORT)"
	@$(OPEN_CMD) "http://localhost:$(PHP_PORT)"
else
	@echo "Starting PHP server on http://localhost:$(PHP_PORT)"
	@$(OPEN_CMD) "http://localhost:$(PHP_PORT)" $(NULL) || true
	php -S localhost:$(PHP_PORT)
endif

# ------------------------------------------------------
# Run the Python model pull script
# ------------------------------------------------------
pull-model:
	@echo Running local Python pull_model.py ...
	@$(PY) pull_model.py

# ------------------------------------------------------
# Full setup automation
# ------------------------------------------------------
setup: build run
	@echo "Running pull_model.py to verify model ..."
	@$(PY) pull_model.py || echo "Could not run pull_model.py — check Python installation."
	@echo "Now pulling model inside the container ..."
	@$(CONTAINER_ENGINE) exec -it $(CONTAINER_NAME) ollama pull $(MODEL)
	@echo "Setup complete! Run 'make serve' to open the web interface."
