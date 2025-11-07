# ======================================================
#  Minimal Makefile for Ollama Container Setup
#  Cross-Platform: Windows / macOS / Linux
#  Commands: setup | reload | clean | status
# ======================================================

IMAGE_NAME      := ai-email-gen
CONTAINER_NAME  := ollama-qwen-slim
PORT            := 11434
MODEL           := llama3.2:3b

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
    VOLUME_PATH := $(shell powershell -Command "(Resolve-Path .\\ollama-data).Path")
    CONTAINER_ENGINE := $(shell powershell -Command "if (Get-Command podman -ErrorAction SilentlyContinue) { Write-Host podman } elseif (Get-Command docker -ErrorAction SilentlyContinue) { Write-Host docker } else { Write-Host none }")
else
    SHELL := /bin/bash
    NULL := >/dev/null 2>&1
    MKDIR := mkdir -p
    RM := rm -rf
    PY := python3
    VOLUME_PATH := $(shell pwd)/ollama-data
    CONTAINER_ENGINE := $(shell if command -v podman >/dev/null 2>&1; then echo podman; elif command -v docker >/dev/null 2>&1; then echo docker; else echo none; fi)
endif

ifeq ($(CONTAINER_ENGINE),none)
$(error Neither Podman nor Docker found. Please install one to continue.)
endif

.PHONY: setup reload clean status

# ------------------------------------------------------
# Setup: Build image, start container, pull model
# ------------------------------------------------------
setup:
ifeq ($(OS),Windows_NT)
	@if not exist "ollama-data" mkdir ollama-data
else
	@$(MKDIR) ollama-data
endif
	@echo "Building image $(IMAGE_NAME)..."
	$(CONTAINER_ENGINE) build -t $(IMAGE_NAME) .
	@echo "Starting container $(CONTAINER_NAME)..."
	-$(CONTAINER_ENGINE) stop $(CONTAINER_NAME) $(NULL) || true
	-$(CONTAINER_ENGINE) rm $(CONTAINER_NAME) $(NULL) || true
	$(CONTAINER_ENGINE) run -d -p $(PORT):11434 -v "$(VOLUME_PATH):/root/.ollama:Z" \
		-e OLLAMA_HOST=0.0.0.0:11434 \
		--name $(CONTAINER_NAME) $(IMAGE_NAME)
	@echo "Pulling model $(MODEL)..."
	$(CONTAINER_ENGINE) exec -it $(CONTAINER_NAME) ollama pull $(MODEL) || echo "Model pull failed — ensure Ollama is reachable."
	@echo "Setup complete. Container is running on port $(PORT)."

# ------------------------------------------------------
# Reload: Restart container without rebuilding
# ------------------------------------------------------
reload:
	@echo "Restarting container $(CONTAINER_NAME)..."
	-$(CONTAINER_ENGINE) stop $(CONTAINER_NAME) $(NULL) || true
	-$(CONTAINER_ENGINE) rm $(CONTAINER_NAME) $(NULL) || true
	$(CONTAINER_ENGINE) run -d -p $(PORT):11434 -v "$(VOLUME_PATH):/root/.ollama" \
		-e OLLAMA_HOST=0.0.0.0:11434 \
		--name $(CONTAINER_NAME) $(IMAGE_NAME)
	@echo "Reload complete. Container restarted on port $(PORT)."

# ------------------------------------------------------
# Clean: Stop and remove container
# ------------------------------------------------------
clean:
	-$(CONTAINER_ENGINE) stop $(CONTAINER_NAME) $(NULL) || true
	-$(CONTAINER_ENGINE) rm $(CONTAINER_NAME) $(NULL) || true
	@echo "Container cleaned."

# ------------------------------------------------------
# Status: Show running containers
# ------------------------------------------------------
status:
	$(CONTAINER_ENGINE) ps
# ------------------------------------------------------
# Serve PHP locally for testing
# ------------------------------------------------------
serve:
ifeq ($(OS),Windows_NT)
	@echo Starting PHP server on http://localhost:8080
	start powershell -NoExit -Command "php -S localhost:8080 -t ."
else
	@echo "Starting PHP server on http://localhost:8080"
	@xdg-open "http://localhost:8080" >/dev/null 2>&1 || true
	php -S localhost:8080 -t .
endif
