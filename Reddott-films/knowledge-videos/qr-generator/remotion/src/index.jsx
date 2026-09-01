import React from 'react';
import { Composition, registerRoot } from 'remotion';
import { Video } from './Video.jsx';

export const RemotionRoot = () => (
  <Composition
    id="QRGenerator"
    component={Video}
    durationInFrames={300 * 30}
    fps={30}
    width={1280}
    height={720}
  />
);

registerRoot(RemotionRoot);
