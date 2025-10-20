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
    SHELL := powershell.exe
    NULL := >NUL 2>&1
    MKDIR := if not exist
    RM := del /Q
    PY := python
    CURL := curl.exe
    OPEN := start
    VOLUME_PATH := $(shell powershell -Command "(Resolve-Path .\\ollama-data).Path")
else
    SHELL := /bin/bash
    NULL := >/dev/null 2>&1
    MKDIR := mkdir -p
    RM := rm -rf
    PY := python3
    CURL := curl
    # macOS uses 'open', Linux uses 'xdg-open'
    OPEN := $(shell if command -v open >/dev/null 2>&1; then echo open; else echo xdg-open; fi)
    VOLUME_PATH := $(shell pwd)/ollama-data
endif

# ------------------------------------------------------
# Determine container engine (Podman or Docker)
# ------------------------------------------------------
CONTAINER_ENGINE := $(shell \
	if command -v podman >/dev/null 2>&1; then echo podman; \
	elif command -v docker >/dev/null 2>&1; then echo docker; \
	else echo none; fi)

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
	-$(CONTAINER_ENGINE) stop $(CONTAINER_NAME) $(NULL) || powershell -Command "exit 0"
	-$(CONTAINER_ENGINE) rm $(CONTAINER_NAME) $(NULL) || powershell -Command "exit 0"
else
	@$(MKDIR) ollama-data
	-$(CONTAINER_ENGINE) stop $(CONTAINER_NAME) $(NULL) || true
	-$(CONTAINER_ENGINE) rm $(CONTAINER_NAME) $(NULL) || true
endif
	$(CONTAINER_ENGINE) run -d -p $(PORT):11434 \
		-v "$(VOLUME_PATH):/root/.ollama" \
		--name $(CONTAINER_NAME) $(IMAGE_NAME)

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
	-$(CONTAINER_ENGINE) stop $(CONTAINER_NAME) $(NULL) || powershell -Command "exit 0"
	-$(CONTAINER_ENGINE) rm $(CONTAINER_NAME) $(NULL) || powershell -Command "exit 0"
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
	powershell -Command "Start-Process powershell -ArgumentList '-NoExit','-Command','php -S localhost:$(PHP_PORT)'"
	@$(OPEN) http://localhost:$(PHP_PORT)
else
	@echo "Starting PHP server on http://localhost:$(PHP_PORT)"
	@$(OPEN) "http://localhost:$(PHP_PORT)" $(NULL) || true
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
