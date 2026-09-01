import fs from 'node:fs';
import { google } from 'googleapis';

const videoPath = process.env.VIDEO_PATH;
const thumbnailPath = process.env.THUMBNAIL_PATH;
const clientId = process.env.YT_CLIENT_ID;
const clientSecret = process.env.YT_CLIENT_SECRET;
const refreshToken = process.env.YT_REFRESH_TOKEN;

if (!videoPath || !thumbnailPath || !clientId || !clientSecret || !refreshToken) {
  throw new Error('Missing VIDEO_PATH, THUMBNAIL_PATH or YouTube OAuth secrets.');
}

const auth = new google.auth.OAuth2(clientId, clientSecret);
auth.setCredentials({ refresh_token: refreshToken });
const youtube = google.youtube({ version: 'v3', auth });

const title = 'How to Create a QR Code with SmartToolz';
const description = `Learn how to create a QR code using the SmartToolz QR Code Generator.\n\nTool: https://smarttoolz.in/smart-toolz/tools/qr-generator.php\nGuide: https://smarttoolz.in/knowledge-base/qr-generator/article/\n\nThis tutorial demonstrates the real SmartToolz workflow from entering content to generating and checking the QR code.`;

const uploaded = await youtube.videos.insert({
  part: ['snippet', 'status'],
  requestBody: {
    snippet: {
      title,
      description,
      tags: ['SmartToolz', 'QR Code Generator', 'QR code', 'how to'],
      categoryId: '27'
    },
    status: { privacyStatus: 'public', selfDeclaredMadeForKids: false }
  },
  media: { body: fs.createReadStream(videoPath) }
});

const id = uploaded.data.id;
if (!id) throw new Error('YouTube upload returned no video id.');
await youtube.thumbnails.set({ videoId: id, media: { body: fs.createReadStream(thumbnailPath) } });
console.log(`YouTube upload complete: https://www.youtube.com/watch?v=${id}`);
