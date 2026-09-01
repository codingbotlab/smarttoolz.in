import React from 'react';
import { Composition, registerRoot } from 'remotion';

const Demo: React.FC = () => (
  <div style={{ flex: 1, background: '#10131a', color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Arial' }}>
    SmartToolz How-to
  </div>
);

const Root: React.FC = () => (
  <Composition
    id="SmartToolzHowTo"
    component={Demo}
    durationInFrames={30 * 10}
    fps={30}
    width={1920}
    height={1080}
  />
);

registerRoot(Root);
