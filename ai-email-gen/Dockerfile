FROM debian:bookworm-slim

# Basic dependencies
RUN apt-get update && apt-get install -y \
    bash curl ca-certificates git libc6 libstdc++6 \
    && rm -rf /var/lib/apt/lists/*

# Install Ollama
RUN curl -fsSL https://ollama.ai/install.sh | bash

# Ensure model path alignment
ENV OLLAMA_MODELS=/root/.ollama/models
RUN mkdir -p /usr/share/ollama/.ollama && \
    ln -s /root/.ollama/models /usr/share/ollama/.ollama/models

# Expose Ollama API
EXPOSE 11434

# Start Ollama with the correct environment
CMD ["bash", "-c", "OLLAMA_MODELS=/root/.ollama/models ollama serve"]
