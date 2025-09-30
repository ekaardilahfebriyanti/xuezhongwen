import { FC, useEffect, useRef, useState } from "react";
import type { Line } from "../data/types";

 
let $stopCurrentSound : ()=>void;
 
export const ChineseAudio : FC<{ line:Line, autoplay?:boolean, num:number, ignoreKeys?:boolean, hideSlowly?:boolean, hideSource?:boolean }> = ({ line, autoplay, num, ignoreKeys, hideSlowly, hideSource }) => { 
    const audioRef = useRef<HTMLAudioElement | null>(null);  
    const [isPlaying, setIsPlaying] = useState<boolean>(false); // State to track if audio is playing

    useEffect(() => {
      // Force the audio element to reload the new source
      if (audioRef.current) {
        audioRef.current.load();
      }
    }, [line.audio]); // Run effect whenever line.audio changes

    // Play the audio when the component is mounted
    useEffect(() => {
        if (audioRef.current ) { 

            if( autoplay )
                handleReplay(true);

            // Event listener for when the audio ends
            audioRef.current.onended = () => {
                setIsPlaying(false);         // Update play state 
            };

            return ()=>{
                if( audioRef.current )
                    audioRef.current.onended = null;
            }
        }
        
    }, [num]); // Empty dependency array ensures this runs only once on mount

    useEffect(() => {

        if( ignoreKeys ) return;

        const onKeyDown = (ev: KeyboardEvent) => {
          if (ev.code == 'ArrowLeft') { 
            handleReplay(true)
          }
        }
    
        window.addEventListener("keydown", onKeyDown);
        return () => {
          window.removeEventListener("keydown", onKeyDown);
        }
    
      });

    const handleReplay = ( forcePlay?:boolean, speed?:number ) => {
        if (audioRef.current) {   

          if(  $stopCurrentSound )
               $stopCurrentSound();

          if( forcePlay || !isPlaying )
          {
            audioRef.current.currentTime = 0; // Reset audio to the beginning
            setIsPlaying(true);  
  
            audioRef.current.playbackRate = speed ?? 1;
            audioRef.current.play(); // Play from the start  
            $stopCurrentSound = ()=>{
                audioRef.current?.pause();
                setIsPlaying(false);
            }
          } 
          else 
          {
            setIsPlaying(false);
          }

        }
    }; 

    return <div > 
                <button onClick={()=>handleReplay(false, 1)}>{ isPlaying?"Stop":"Play"}</button> 
               { !hideSlowly && !isPlaying && <button style={{ marginLeft:2}} onClick={()=>handleReplay(false, 0.5)}>Slowly</button> }
                { !hideSource && <div className="source" style={{ display:"inline", marginLeft:5, whiteSpace:"nowrap"}}>  <strong><a href={line.source} target="_blank">Source ⍈</a></strong></div> }
             
              
                  <audio ref={audioRef} >
                      <source src={`/audio/${line.audio}.mp3`} type="audio/mp3" /> 
                  </audio>
          </div>
}