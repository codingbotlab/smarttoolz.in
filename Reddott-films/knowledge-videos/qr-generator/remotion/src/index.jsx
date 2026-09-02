import React from 'react';
import fs from 'node:fs';
import path from 'node:path';
import { Composition, registerRoot } from 'remotion';
import { Video } from './Video.jsx';

const durationFile = path.join(process.cwd(), 'public', 'narration-duration.txt');

export const RemotionRoot = () => (
  <Composition
    id="QRGenerator"
    component={Video}
    durationInFrames={60 * 30}
    fps={30}
    width={1280}
    height={720}
    calculateMetadata={() => {
      const seconds = Number.parseFloat(fs.readFileSync(durationFile, 'utf8').trim());
      if (!Number.isFinite(seconds) || seconds <= 0 || seconds > 60) {
        throw new Error(`Invalid narration duration: ${seconds}`);
      }
      return { durationInFrames: Math.max(1, Math.ceil(seconds * 30)) };
    }}
  />
);

registerRoot(RemotionRoot);
