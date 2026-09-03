# Keddy Bot

Keddy is a lightweight YouTube Live instructor bot for SmartToolz. It can run a seated-task session, respond to chat commands, and post the next task on a configurable interval.

## Features

- YouTube OAuth 2.0 connection
- Detects the active YouTube live broadcast
- Posts scheduled instructor messages to live chat
- `!task`, `!next`, `!status`, `!break`, and `!help` commands
- Seated-only task presets
- Configurable task interval and session length
- Optional OpenAI-compatible AI replies via environment variables
- SQLite state storage
- No framework required; plain PHP + cURL

## Requirements

- PHP 8.1+ with cURL and SQLite enabled
- A Google Cloud OAuth 2.0 Web application client
- YouTube Data API v3 enabled
- A writable directory for SQLite/token storage
- HTTPS in production

## Setup

1. Copy `config.php.example` to `config.php`.
2. Put your Google OAuth client ID/secret and an application secret in environment variables or `config.php`.
3. Set the OAuth redirect URI to:
   `https://YOUR-DOMAIN/keddy-bot/oauth-callback.php`
4. Open `/keddy-bot/` and click **Connect YouTube**.
5. Authorize the YouTube account that owns the live channel.
6. Start a YouTube live stream, then use the Keddy dashboard to start the instructor session.
7. Configure a server cron every minute to run `cron.php` (the script only sends when a 2-minute slot is due):
   `* * * * * php /path/to/keddy-bot/cron.php >/dev/null 2>&1`

## Commands

- `!task` — current task
- `!next` — move to the next task
- `!status` — session status
- `!break` — announce a seated break
- `!help` — show commands

## Optional AI

Set `KEDDY_AI_URL`, `KEDDY_AI_KEY`, and `KEDDY_AI_MODEL` to an OpenAI-compatible chat-completions endpoint. AI is optional; the bot works with the built-in instructor logic without it.

Never commit real API keys, OAuth secrets, refresh tokens, or `config.php` to Git.
