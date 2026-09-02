import React from 'react';
import fs from 'node:fs';
import { Composition, registerRoot } from 'remotion';
import { Video } from './Video.jsx';

const durationFile = new URL('../public/narration-duration.txt', import.meta.url);

export const RemotionRoot = () => (
  <Composition
    id="QRGenerator"
    component={Video}
    durationInFrames={Math.ceil(60 * 30)}
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
