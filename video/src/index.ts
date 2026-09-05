import {Composition, registerRoot} from 'remotion';
import {Video, calculateMetadata} from './video';

export const RemotionRoot = () => {
  return (
    <Composition
      id="Video"
      component={Video}
      width={1920}
      height={1080}
      fps={30}
      durationInFrames={30}
      defaultProps={{
        title: 'SmartToolz Tutorial',
        sections: [{text: 'Welcome to SmartToolz.', audio: 'audio/section-001.mp3'}],
        format: 'landscape',
      }}
      calculateMetadata={calculateMetadata}
    />
  );
};

registerRoot(RemotionRoot);
