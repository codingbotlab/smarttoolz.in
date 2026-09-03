# Keddy Brain — server-side local LLM

This directory runs Ollama next to the PHP Keddy bot. Ollama listens only on `127.0.0.1:11434`, so the model API is not exposed directly to the Internet.

## Linux server

The server hosting the PHP site needs Docker Engine + Docker Compose.

From this directory run:

```bash
chmod +x install-linux.sh
./install-linux.sh
```

The installer starts the Ollama container and pulls `qwen3:4b-instruct`.

Keddy PHP calls:

`http://127.0.0.1:11434/api/chat`

Model:

`qwen3:4b-instruct`

Do not open port `11434` publicly. The compose file deliberately binds it to localhost only.
