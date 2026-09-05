import React from 'react';
import {AbsoluteFill, Audio, Video as RemotionVideo, Sequence, staticFile, useCurrentFrame, useVideoConfig, interpolate, spring, CalculateMetadataFunction} from 'remotion';
import {getAudioDurationInSeconds, getVideoDurationInSeconds} from '@remotion/media-utils';

export type Section = {text: string; audio: string; screenshot: string; duration: number};
export type VideoProps = {title: string; sections: Section[]; format: 'landscape' | 'portrait'; recording?: string; recordingDuration?: number};

export const calculateMetadata: CalculateMetadataFunction<VideoProps> = async ({props}) => {
  const fps = 30;
  const durations = await Promise.all(props.sections.map((s) => getAudioDurationInSeconds(staticFile(s.audio))));
  const audioDuration = durations.reduce((a, b) => a + b, 0);
  const recording = props.recording || 'recording/tutorial.webm';
  let recordingDuration = 1;
  try { recordingDuration = await getVideoDurationInSeconds(staticFile(recording)); } catch {}
  return {
    fps,
    width: props.format === 'portrait' ? 1080 : 1920,
    height: props.format === 'portrait' ? 1920 : 1080,
    durationInFrames: Math.max(30, Math.ceil(audioDuration * fps)),
    props: {...props, recording, recordingDuration, sections: props.sections.map((s, i) => ({...s, duration: durations[i]}))},
  };
};

const Scene: React.FC<{title: string; text: string; index: number; total: number; durationFrames: number}> = ({title, text, index, total, durationFrames}) => {
  const frame = useCurrentFrame();
  const {fps, width, height} = useVideoConfig();
  const enter = spring({fps, frame, config: {damping: 20, stiffness: 90, mass: .8}});
  const fade = interpolate(frame, [Math.max(0, durationFrames - 15), durationFrames], [1, 0], {extrapolateLeft: 'clamp', extrapolateRight: 'clamp'});
  const words = text.split(/\s+/).filter(Boolean);
  const count = Math.min(words.length, Math.max(1, Math.floor((frame / Math.max(1, durationFrames)) * (words.length + 1))));
  const caption = words.slice(0, count).join(' ');
  const portrait = height > width;
  return <AbsoluteFill style={{opacity: fade, color:'#fff', fontFamily:'Inter,Arial,sans-serif'}}>
    <div style={{position:'absolute',top:0,left:0,right:0,padding:portrait?'60px 44px':'48px 76px',display:'flex',justifyContent:'space-between',fontWeight:900,textShadow:'0 2px 12px rgba(0,0,0,.8)'}}>
      <span style={{letterSpacing:2}}>SMARTTOOLZ</span><span style={{opacity:.8,fontSize:18}}>{index+1} / {total}</span>
    </div>
    <div style={{position:'absolute',left:portrait?'35px':'70px',right:portrait?'35px':'70px',bottom:portrait?'70px':'55px',padding:'18px 22px',borderRadius:16,background:'rgba(5,10,22,.88)',fontSize:portrait?25:27,lineHeight:1.35,fontWeight:750,boxShadow:'0 15px 45px rgba(0,0,0,.35)'}}>
      <div style={{fontSize:portrait?20:18,fontWeight:850,opacity:.75,marginBottom:7}}>TUTORIAL • {title}</div>
      {caption}
    </div>
  </AbsoluteFill>;
};

export const Video: React.FC<VideoProps> = ({title, sections, recording = 'recording/tutorial.webm', recordingDuration = 1}) => {
  const {durationInFrames, fps} = useVideoConfig();
  const targetSeconds = durationInFrames / fps;
  const playbackRate = Math.max(0.25, Math.min(4, recordingDuration / Math.max(1, targetSeconds)));
  let start = 0;
  return <AbsoluteFill style={{background:'#080c16'}}>
    <RemotionVideo src={staticFile(recording)} muted volume={0} playbackRate={playbackRate} style={{width:'100%',height:'100%',objectFit:'contain',background:'#080c16'}} />
    {sections.map((s, i) => {
      const frames = Math.max(1, Math.ceil(s.duration * fps));
      const from = start;
      start += frames;
      return <Sequence key={s.audio} from={from} durationInFrames={frames}><Scene title={title} text={s.text} index={i} total={sections.length} durationFrames={frames}/><Audio src={staticFile(s.audio)} /></Sequence>;
    })}
  </AbsoluteFill>;
};
