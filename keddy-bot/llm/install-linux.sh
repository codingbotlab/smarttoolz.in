#!/usr/bin/env bash
set -euo pipefail

# Keddy Brain: server-side Ollama installer.
# Run this ON THE LINUX SERVER that hosts the PHP site.
# It does not expose Ollama publicly; PHP talks to 127.0.0.1:11434.

command -v docker >/dev/null 2>&1 || {
  echo "Docker is required. Install Docker Engine first."
  exit 1
}

dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$dir"

docker compose up -d

echo "Waiting for Ollama..."
for i in $(seq 1 30); do
  if curl -fsS http://127.0.0.1:11434/api/tags >/dev/null 2>&1; then
    break
  fi
  sleep 2
done

curl -fsS http://127.0.0.1:11434/api/tags >/dev/null

echo "Pulling Qwen3 4B instruct model..."
docker exec keddy-ollama ollama pull qwen3:4b-instruct

echo "Keddy Ollama is ready."
echo "Endpoint: http://127.0.0.1:11434/api/chat"
echo "Model: qwen3:4b-instruct"
