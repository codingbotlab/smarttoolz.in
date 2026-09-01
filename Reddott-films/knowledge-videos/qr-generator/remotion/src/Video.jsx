import React from 'react';
import { AbsoluteFill, Audio, OffthreadVideo, staticFile } from 'remotion';

export const Video = () => (
  <AbsoluteFill style={{ background: '#000' }}>
    <OffthreadVideo
      src={staticFile('browser-demo.webm')}
      style={{ width: '100%', height: '100%', objectFit: 'cover' }}
    />
    <Audio src={staticFile('narration.wav')} />
  </AbsoluteFill>
);
