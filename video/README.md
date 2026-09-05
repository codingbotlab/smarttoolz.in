# SmartToolz Video

The **Video** tool is a Remotion-based tutorial video generator.

## What it does

1. Accepts a tutorial title and narration script from `/smart-toolz/tools/video.php`.
2. Generates separate male neural voice MP3 files with Microsoft Edge TTS.
3. Uses the real generated audio durations to build the Remotion timeline.
4. Renders a 16:9 YouTube video or 9:16 vertical video.
5. Animates the narration text in sync with the audio and uploads the final MP4 as a GitHub Actions artifact.

The hosting server does **not** need FFmpeg. The render worker is GitHub Actions and Remotion supplies the media tooling it needs.

## One-time hosting setup

For the website's **Generate Video in GitHub** button to dispatch the workflow automatically, set this server environment variable:

`SMARTTOOLZ_GITHUB_TOKEN`

Use a GitHub token that can dispatch workflows in `codingbotlab/smarttoolz.in`. Do not put the token in browser JavaScript or commit it to the repository.

If the environment variable is not set, the tool remains available but shows the configuration message instead of attempting a GitHub API request.

## Manual render

The same workflow can be started from the repository's Actions tab:

`.github/workflows/video-remotion.yml`

Provide the title, script, male voice and format. The finished MP4 is uploaded to the workflow run as `smarttoolz-video-N`.
