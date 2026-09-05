import React from 'react';
import {Audio, AbsoluteFill, CalculateMetadataFunction, Sequence, interpolate, spring, staticFile, useCurrentFrame, useVideoConfig} from 'remotion';
import {getAudioDurationInSeconds} from '@remotion/media-utils';

export type Section = {text: string; audio: string};
export type VideoProps = {
  title: string;
  sections: Section[];
  format: 'landscape' | 'portrait';
  durations?: number[];
};

export const calculateMetadata: CalculateMetadataFunction<VideoProps> = async ({props}) => {
  const durations = await Promise.all(
    props.sections.map((section) => getAudioDurationInSeconds(staticFile(section.audio))),
  );
  const fps = 30;
  const durationInFrames = Math.max(30, Math.ceil(durations.reduce((a, b) => a + b, 0) * fps));
  return {
    fps,
    width: props.format === 'portrait' ? 1080 : 1920,
    height: props.format === 'portrait' ? 1920 : 1080,
    durationInFrames,
    props: {...props, durations},
    defaultOutName: `smarttoolz-video-${Date.now()}`,
  };
};

const SectionScene: React.FC<{
  title: string;
  text: string;
  index: number;
  total: number;
  startFrame: number;
  durationFrames: number;
}> = ({title, text, index, total, startFrame, durationFrames}) => {
  const frame = useCurrentFrame();
  const {fps, width, height} = useVideoConfig();
  const local = Math.max(0, frame - startFrame);
  const enter = spring({fps, frame: local, config: {damping: 18, stiffness: 110, mass: 0.7}});
  const fadeOut = interpolate(local, [Math.max(0, durationFrames - 18), durationFrames], [1, 0], {extrapolateLeft: 'clamp', extrapolateRight: 'clamp'});
  const progress = Math.min(1, (index + 1) / Math.max(1, total));
  const words = text.trim().split(/\s+/).filter(Boolean);
  const visibleWords = Math.min(words.length, Math.max(1, Math.floor((local / Math.max(1, durationFrames)) * (words.length + 2))));
  const caption = words.slice(0, visibleWords).join(' ');
  const portrait = height > width;

  return (
    <AbsoluteFill style={{opacity: fadeOut, background: 'linear-gradient(135deg,#0b1020 0%,#171f3b 48%,#32286b 100%)', color: '#fff', fontFamily: 'Inter, Arial, sans-serif', overflow: 'hidden'}}>
      <AbsoluteFill style={{background: 'radial-gradient(circle at 80% 18%,rgba(139,124,255,.34),transparent 28%),radial-gradient(circle at 12% 82%,rgba(34,211,238,.16),transparent 25%)'}} />
      <div style={{position: 'absolute', inset: 0, opacity: .08, backgroundImage: 'linear-gradient(rgba(255,255,255,.7) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.7) 1px,transparent 1px)', backgroundSize: '48px 48px'}} />
      <div style={{position: 'relative', height: '100%', padding: portrait ? '90px 62px' : '74px 92px', display: 'flex', flexDirection: 'column'}}>
        <div style={{display: 'flex', alignItems: 'center', justifyContent: 'space-between', transform: `translateY(${interpolate(enter,[0,1],[28,0])}px)`, opacity: enter}}>
          <div style={{fontSize: portrait ? 22 : 20, fontWeight: 900, letterSpacing: 2, color: '#a9a1ff'}}>SMARTTOOLZ</div>
          <div style={{fontSize: portrait ? 19 : 17, fontWeight: 700, opacity: .65}}>{index + 1} / {total}</div>
        </div>

        <div style={{flex: 1, display: 'flex', alignItems: portrait ? 'center' : 'center', justifyContent: 'center'}}>
          <div style={{width: portrait ? '100%' : '86%', transform: `translateY(${interpolate(enter,[0,1],[55,0])}px) scale(${interpolate(enter,[0,1],[.97,1])})`, opacity: enter}}>
            {index === 0 && <div style={{fontSize: portrait ? 24 : 22, fontWeight: 800, color: '#8be9ff', marginBottom: 18}}>TUTORIAL</div>}
            <div style={{fontSize: portrait ? 62 : 72, lineHeight: 1.04, fontWeight: 900, letterSpacing: -2.5, marginBottom: 28}}>{index === 0 ? title : `Step ${index}: ${title}`}</div>
            <div style={{fontSize: portrait ? 32 : 34, lineHeight: 1.42, color: 'rgba(255,255,255,.9)', maxWidth: portrait ? '100%' : 1300}}>{caption}</div>
          </div>
        </div>

        <div style={{height: 8, borderRadius: 99, background: 'rgba(255,255,255,.13)', overflow: 'hidden'}}>
          <div style={{height: '100%', width: `${progress * 100}%`, background: 'rgba(255,255,255,.82)', borderRadius: 99}} />
        </div>
      </div>
      <Audio src={staticFile('audio/section-' + String(index + 1).padStart(3, '0') + '.mp3')} />
    </AbsoluteFill>
  );
};

export const Video: React.FC<VideoProps> = ({title, sections, durations = []}) => {
  let start = 0;
  return (
    <AbsoluteFill>
      {sections.map((section, index) => {
        const durationFrames = Math.max(1, Math.ceil((durations[index] ?? 1) * 30));
        const currentStart = start;
        start += durationFrames;
        return (
          <Sequence key={section.audio} from={currentStart} durationInFrames={durationFrames}>
            <SectionScene title={title} text={section.text} index={index} total={sections.length} startFrame={currentStart} durationFrames={durationFrames} />
          </Sequence>
        );
      })}
    </AbsoluteFill>
  );
};
