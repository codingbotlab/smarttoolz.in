#!/usr/bin/env python3
import json
import os
import sys
import time
import urllib.error
import urllib.parse
import urllib.request
from pathlib import Path

API = "https://www.googleapis.com/youtube/v3"
UPLOAD = "https://www.googleapis.com/upload/youtube/v3"


def request(url, method="GET", data=None, headers=None, timeout=120):
    req = urllib.request.Request(url, data=data, method=method, headers=headers or {})
    try:
        with urllib.request.urlopen(req, timeout=timeout) as r:
            return r.status, r.read(), dict(r.headers)
    except urllib.error.HTTPError as e:
        body = e.read().decode("utf-8", "replace")
        raise RuntimeError(f"HTTP {e.code}: {body[:2000]}") from e


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

    with video_path.open("rb") as f:
        video = f.read()
    put_headers = {
        "Authorization": f"Bearer {token}",
        "Content-Type": "video/mp4",
        "Content-Length": str(len(video)),
    }
    _, body, _ = request(location, "PUT", video, put_headers, timeout=600)
    result = json.loads(body)
    video_id = result.get("id")
    if not video_id:
        raise RuntimeError(f"YouTube upload returned no video id: {result}")
    return video_id


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

    title = "QR Code Generator Tutorial | Create & Download QR Codes Free | SmartToolz"
    description = """Learn how to create a QR code quickly with SmartToolz QR Code Generator.

In this step-by-step tutorial, we use the real SmartToolz QR Generator to enter content, customize the available options, generate the QR code, preview the result, and download it.

🔗 Try the free QR Code Generator:
https://smarttoolz.in/smart-toolz/tools/qr-generator.php

📚 Read the full written guide:
https://smarttoolz.in/knowledge-base/qr-generator/article/

SmartToolz provides practical online tools for everyday tasks. Subscribe for more real tool tutorials and how-to videos.

#QRCode #QRGenerator #SmartToolz #QRCodeGenerator #OnlineTools"""
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

    token = oauth_token()
    playlist_title = "Smart Toolz"
    playlist_description = "SmartToolz tutorials: practical step-by-step guides for SmartToolz online tools."
    playlist_id = find_or_create_playlist(token, playlist_title, playlist_description)
    print(f"Playlist ready: {playlist_title} ({playlist_id})", flush=True)

    video_id = upload_video(token, video, metadata)
    print(f"Video uploaded: https://www.youtube.com/watch?v={video_id}", flush=True)

    set_thumbnail(token, video_id, thumb)
    print("Thumbnail uploaded.", flush=True)

    add_to_playlist(token, playlist_id, video_id)
    print(f"Added to playlist: {playlist_title}", flush=True)
    print(json.dumps({"video_id": video_id, "playlist_id": playlist_id}, indent=2))


if __name__ == "__main__":
    try:
        main()
    except Exception as exc:
        print(f"UPLOAD FAILED: {exc}", file=sys.stderr)
        sys.exit(1)
