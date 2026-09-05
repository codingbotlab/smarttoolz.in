import React from 'react';
import {AbsoluteFill, Audio, Img, Sequence, staticFile, useCurrentFrame, useVideoConfig, interpolate, spring, CalculateMetadataFunction} from 'remotion';
import {getAudioDurationInSeconds} from '@remotion/media-utils';

export type Section = {text: string; audio: string; screenshot: string};
export type VideoProps = {title: string; sections: Section[]; format: 'landscape' | 'portrait'};

export const calculateMetadata: CalculateMetadataFunction<VideoProps> = async ({props}) => {
  const fps = 30;
  const durations = await Promise.all(props.sections.map((s) => getAudioDurationInSeconds(staticFile(s.audio))));
  return {
    fps,
    width: props.format === 'portrait' ? 1080 : 1920,
    height: props.format === 'portrait' ? 1920 : 1080,
    durationInFrames: Math.max(30, Math.ceil(durations.reduce((a, b) => a + b, 0) * fps)),
    props: {...props},
  };
};

const Scene: React.FC<{title: string; text: string; screenshot: string; index: number; total: number; durationFrames: number}> = ({title, text, screenshot, index, total, durationFrames}) => {
  const frame = useCurrentFrame();
  const {fps, width, height} = useVideoConfig();
  const enter = spring({fps, frame, config: {damping: 20, stiffness: 90, mass: .8}});
  const scale = interpolate(enter, [0, 1], [1.035, 1]);
  const fade = interpolate(frame, [Math.max(0, durationFrames - 15), durationFrames], [1, 0], {extrapolateLeft: 'clamp', extrapolateRight: 'clamp'});
  const words = text.split(/\s+/).filter(Boolean);
  const count = Math.min(words.length, Math.max(1, Math.floor((frame / Math.max(1, durationFrames)) * (words.length + 1))));
  const caption = words.slice(0, count).join(' ');
  const portrait = height > width;
  return <AbsoluteFill style={{background:'#0b1020', opacity:fade, color:'#fff', fontFamily:'Inter,Arial,sans-serif'}}>
    <AbsoluteFill style={{background:'radial-gradient(circle at 85% 15%,rgba(99,91,255,.34),transparent 28%),radial-gradient(circle at 10% 85%,rgba(34,211,238,.18),transparent 24%)'}} />
    <div style={{position:'relative',height:'100%',padding:portrait?'70px 44px':'58px 76px',display:'flex',flexDirection:'column',gap:24}}>
      <div style={{display:'flex',justifyContent:'space-between',alignItems:'center',fontWeight:900}}><span style={{letterSpacing:2,color:'#a9a1ff'}}>SMARTTOOLZ</span><span style={{opacity:.65,fontSize:18}}>{index+1} / {total}</span></div>
      <div style={{flex:1,display:'flex',flexDirection:'column',justifyContent:'center',gap:24}}>
        <div style={{fontSize:portrait?25:23,fontWeight:850,color:'#8be9ff'}}>TUTORIAL • {title}</div>
        <div style={{fontSize:portrait?44:48,fontWeight:900,lineHeight:1.08}}>{index === 0 ? 'How to use it' : `Step ${index}`}</div>
        <div style={{position:'relative',width:'100%',height:portrait?'42%':'54%',borderRadius:20,overflow:'hidden',border:'1px solid rgba(255,255,255,.16)',boxShadow:'0 25px 80px rgba(0,0,0,.38)',transform:`scale(${scale})`,opacity:enter,background:'#111827'}}>
          <Img src={staticFile(screenshot)} style={{width:'100%',height:'100%',objectFit:'cover'}} />
          <div style={{position:'absolute',left:18,right:18,bottom:18,padding:'14px 18px',borderRadius:13,background:'rgba(10,14,28,.9)',fontSize:portrait?22:24,lineHeight:1.35,fontWeight:700}}>{caption}</div>
        </div>
      </div>
      <div style={{height:7,borderRadius:99,background:'rgba(255,255,255,.14)'}}><div style={{height:'100%',width:`${((index+1)/total)*100}%`,background:'#fff',borderRadius:99}} /></div>
    </div>
    <Audio src={staticFile(`audio/section-${String(index+1).padStart(3,'0')}.mp3`)} />
  </AbsoluteFill>;
};

export const Video: React.FC<VideoProps> = ({title, sections}) => {
  let start = 0;
  return <AbsoluteFill>{sections.map((s, i) => {
    // Audio duration is also used to create the exact section boundaries in generate_audio.py.
    const frames = Math.max(1, Math.ceil((s as any).duration * 30 || 1));
    const from = start; start += frames;
    return <Sequence key={s.audio} from={from} durationInFrames={frames}><Scene title={title} text={s.text} screenshot={s.screenshot} index={i} total={sections.length} durationFrames={frames}/></Sequence>;
  })}</AbsoluteFill>;
};
