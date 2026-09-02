#!/usr/bin/env python3
import json
import os
import subprocess
import sys
import time
import urllib.error
import urllib.parse
import urllib.request
from pathlib import Path

API = "https://www.googleapis.com/youtube/v3"
UPLOAD = "https://www.googleapis.com/upload/youtube/v3"
MAX_VIDEO_SECONDS = 60.0
CHUNK_SIZE = 8 * 1024 * 1024


def request(url, method="GET", data=None, headers=None, timeout=120):
    req = urllib.request.Request(url, data=data, method=method, headers=headers or {})
    try:
        with urllib.request.urlopen(req, timeout=timeout) as r:
            return r.status, r.read(), dict(r.headers)
    except urllib.error.HTTPError as e:
        body = e.read().decode("utf-8", "replace")
        raise RuntimeError(f"HTTP {e.code}: {body[:3000]}") from e
    except urllib.error.URLError as e:
        raise RuntimeError(f"Network error: {e.reason}") from e


def oauth_token():
    form = urllib.parse.urlencode({
        "client_id": os.environ["YT_CLIENT_ID"],
        "client_secret": os.environ["YT_CLIENT_SECRET"],
        "refresh_token": os.environ["YT_REFRESH_TOKEN"],
        "grant_type": "refresh_token",
    }).encode()
    _, body, _ = request(
        "https://oauth2.googleapis.com/token",
        "POST",
        form,
        {"Content-Type": "application/x-www-form-urlencoded"},
    )
    data = json.loads(body)
    if not data.get("access_token"):
        raise RuntimeError(f"OAuth token refresh failed: {data}")
    return data["access_token"]


def api_json(token, path, method="GET", payload=None):
    data = None if payload is None else json.dumps(payload, ensure_ascii=False).encode("utf-8")
    headers = {"Authorization": f"Bearer {token}"}
    if data is not None:
        headers["Content-Type"] = "application/json; charset=UTF-8"
    _, body, _ = request(API + path, method, data, headers)
    return json.loads(body)


def retry_call(label, fn, attempts=3):
    last = None
    for attempt in range(1, attempts + 1):
        try:
            return fn()
        except Exception as exc:
            last = exc
            if attempt == attempts:
                break
            delay = attempt * 3
            print(f"{label} failed (attempt {attempt}/{attempts}): {exc}; retrying in {delay}s...", flush=True)
            time.sleep(delay)
    raise RuntimeError(f"{label} failed after {attempts} attempts: {last}") from last


def video_duration(video_path):
    result = subprocess.run([
        "ffprobe", "-v", "error", "-show_entries", "format=duration",
        "-of", "default=noprint_wrappers=1:nokey=1", str(video_path)
    ], capture_output=True, text=True, check=False)
    if result.returncode != 0:
        raise RuntimeError(f"ffprobe failed: {result.stderr.strip()}")
    try:
        return float(result.stdout.strip())
    except ValueError as exc:
        raise RuntimeError(f"Invalid video duration: {result.stdout!r}") from exc


def find_or_create_playlist(token, title, description):
    page = None
    while True:
        params = {"part": "snippet", "mine": "true", "maxResults": "50"}
        if page:
            params["pageToken"] = page
        data = api_json(token, "/playlists?" + urllib.parse.urlencode(params))
        for item in data.get("items", []):
            if item.get("snippet", {}).get("title", "").strip().casefold() == title.casefold():
                return item["id"]
        page = data.get("nextPageToken")
        if not page:
            break
    created = api_json(token, "/playlists?part=snippet,status", "POST", {
        "snippet": {"title": title, "description": description},
        "status": {"privacyStatus": "public"},
    })
    return created["id"]


def upload_video(token, video_path, metadata):
    body = json.dumps({"snippet": metadata["snippet"], "status": metadata["status"]}, ensure_ascii=False).encode("utf-8")
    params = urllib.parse.urlencode({"uploadType": "resumable", "part": "snippet,status"})
    headers = {
        "Authorization": f"Bearer {token}",
        "Content-Type": "application/json; charset=UTF-8",
        "X-Upload-Content-Type": "video/mp4",
        "X-Upload-Content-Length": str(video_path.stat().st_size),
    }
    _, _, response_headers = request(f"{UPLOAD}/videos?{params}", "POST", body, headers)
    location = response_headers.get("Location")
    if not location:
        raise RuntimeError("YouTube did not return a resumable upload URL.")

    total = video_path.stat().st_size
    sent = 0
    print(f"Uploading {total / 1024 / 1024:.2f} MB in {CHUNK_SIZE / 1024 / 1024:.0f} MB chunks...", flush=True)

    with video_path.open("rb") as f:
        while sent < total:
            chunk = f.read(CHUNK_SIZE)
            if not chunk:
                raise RuntimeError(f"Unexpected end of file at byte {sent} of {total}")
            end = sent + len(chunk) - 1
            chunk_headers = {
                "Authorization": f"Bearer {token}",
                "Content-Type": "video/mp4",
                "Content-Length": str(len(chunk)),
                "Content-Range": f"bytes {sent}-{end}/{total}",
            }

            accepted = False
            for attempt in range(1, 6):
                req = urllib.request.Request(location, data=chunk, method="PUT", headers=chunk_headers)
                try:
                    with urllib.request.urlopen(req, timeout=600) as response:
                        status = response.status
                        response_body = response.read()
                        if status in (200, 201):
                            result = json.loads(response_body)
                            video_id = result.get("id")
                            if not video_id:
                                raise RuntimeError(f"YouTube upload returned no video id: {result}")
                            print("Final upload response received.", flush=True)
                            return video_id
                        if status == 308:
                            accepted = True
                except urllib.error.HTTPError as exc:
                    response_body = exc.read().decode("utf-8", "replace")
                    if exc.code == 308:
                        accepted = True
                    elif 500 <= exc.code < 600 and attempt < 5:
                        print(f"Chunk {sent}-{end} got HTTP {exc.code}; retrying...", flush=True)
                        time.sleep(attempt * 2)
                        continue
                    else:
                        raise RuntimeError(f"Chunk {sent}-{end} failed: HTTP {exc.code}: {response_body[:3000]}") from exc
                except urllib.error.URLError as exc:
                    if attempt == 5:
                        raise RuntimeError(f"Chunk {sent}-{end} network failure: {exc.reason}") from exc
                    print(f"Chunk {sent}-{end} network error; retrying...", flush=True)
                    time.sleep(attempt * 2)
                    continue

                if accepted:
                    break

            if not accepted:
                raise RuntimeError(f"Chunk {sent}-{end} was not accepted by YouTube.")

            sent = end + 1
            print(f"Uploaded {sent / total * 100:.0f}%", flush=True)

    raise RuntimeError("Upload ended without a final YouTube response.")


def set_thumbnail(token, video_id, thumbnail_path):
    data = thumbnail_path.read_bytes()
    url = f"{UPLOAD}/thumbnails/set?videoId={urllib.parse.quote(video_id)}"
    request(url, "POST", data, {
        "Authorization": f"Bearer {token}",
        "Content-Type": "image/png",
        "Content-Length": str(len(data)),
    }, timeout=120)


def add_to_playlist(token, playlist_id, video_id):
    return api_json(token, "/playlistItems?part=snippet", "POST", {
        "snippet": {
            "playlistId": playlist_id,
            "resourceId": {"kind": "youtube#video", "videoId": video_id},
        }
    })


def main():
    video = Path(os.environ.get("VIDEO_PATH", "remotion/out/qr-generator.mp4"))
    thumb = Path(os.environ.get("THUMBNAIL_PATH", "output/thumbnail.png"))
    if not video.is_file() or video.stat().st_size == 0:
        raise RuntimeError(f"Video missing or empty: {video}")
    if not thumb.is_file() or thumb.stat().st_size == 0:
        raise RuntimeError(f"Thumbnail missing or empty: {thumb}")

    duration = video_duration(video)
    print(f"Verified MP4 duration: {duration:.3f} seconds", flush=True)
    if duration > MAX_VIDEO_SECONDS + 0.25:
        raise RuntimeError(f"Refusing to upload a video longer than 60 seconds: {duration:.3f}s")

    title = "QR Code Generator in Under 1 Minute | Create QR Codes Free | SmartToolz"
    description = """Create a QR code in under a minute with the real SmartToolz QR Code Generator.

This quick step-by-step tutorial shows the real SmartToolz tool: enter your content, review the available options, generate the QR code, check the result, and test it before publishing.

🔗 Try the free QR Code Generator:
https://smarttoolz.in/smart-toolz/tools/qr-generator.php

📚 Read the full written guide:
https://smarttoolz.in/knowledge-base/qr-generator/article/

SmartToolz provides practical online tools for everyday tasks. Subscribe for more real tool tutorials and quick how-to guides.

#QRCode #QRGenerator #SmartToolz #QRCodeGenerator #OnlineTools #Tutorial"""
    tags = [
        "qr code generator", "qr code", "qr generator", "create qr code", "make qr code",
        "free qr code generator", "online qr code generator", "qr code maker", "SmartToolz",
        "Smart Toolz", "qr code tutorial", "how to create qr code", "download qr code",
        "free online tools", "online tools", "technology tutorial"
    ]
    metadata = {
        "snippet": {
            "title": title,
            "description": description,
            "tags": tags,
            "categoryId": "28",
            "defaultLanguage": "en-IN",
            "defaultAudioLanguage": "en-IN",
        },
        "status": {"privacyStatus": "public", "selfDeclaredMadeForKids": False},
    }

    print("Refreshing YouTube OAuth token...", flush=True)
    token = oauth_token()
    print("OAuth token refreshed successfully.", flush=True)

    playlist_title = "Smart Toolz"
    playlist_description = "SmartToolz tutorials: practical step-by-step guides for SmartToolz online tools."
    playlist_id = retry_call(
        "Find/create Smart Toolz playlist",
        lambda: find_or_create_playlist(token, playlist_title, playlist_description),
    )
    print(f"Playlist ready: {playlist_title} ({playlist_id})", flush=True)

    video_id = upload_video(token, video, metadata)
    print(f"Video uploaded: https://www.youtube.com/watch?v={video_id}", flush=True)

    retry_call("Thumbnail upload", lambda: set_thumbnail(token, video_id, thumb))
    print("Thumbnail uploaded.", flush=True)

    retry_call("Playlist add", lambda: add_to_playlist(token, playlist_id, video_id))
    print(f"Added to playlist: {playlist_title}", flush=True)
    print(json.dumps({"video_id": video_id, "playlist_id": playlist_id}, indent=2))


if __name__ == "__main__":
    try:
        main()
    except Exception as exc:
        print(f"UPLOAD FAILED: {exc}", file=sys.stderr)
        sys.exit(1)
