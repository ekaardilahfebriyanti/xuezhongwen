import { FC, useEffect, useMemo, useState } from "react"
import hskData from "../data/hsk.json";
import classes from "./HSK.module.css";
import { GoldenSymbol } from "./GoldenSymbol";

type Done = {
    ch:string 
    knewit:boolean // if the user claim to knew it...
}

export const HSK : FC<{ level:number }> = ({ level })=>{
    const chars = useMemo(()=>{
        const dicc = [ hskData.hsk1, hskData.hsk2, hskData.hsk3][level-1];
        return Object.entries(dicc).map( entry=>{
            let key = entry[0];
            let en = entry[1].en;
            let pinyin = entry[1].pinyin;

            if( key.indexOf("（") ) {
                key = key.replace(/\s*（.*?）\s*/g, '');
                en = en.replace(/\s*（.*?）\s*/g, '');
                pinyin = pinyin.replace(/\s*（.*?）\s*/g, '');
            } 

            return { ch:key, pinyin, en }
        })
    }, [level]);
 
    const [check, setCheck] = useState(false);
    const [ pool, setPool ] = useState( chars.slice(1) );
    const [ char, setChar ] = useState( chars[0] );
    const [ dones, setDones] = useState<Done[]>([]);

    useEffect(()=>{

        const onKey = (ev:KeyboardEvent) => {
            if( check )
            { 
                //chekc up or down arrows
                if( ev.key=='ArrowUp') gotIt(true);
                else if( ev.key=="ArrowDown") gotIt(false);
            }
            else 
            {
                if( ev.key=="ArrowRight") {  
                    setCheck(true);
                }
            }
            
        }

        window.addEventListener("keydown", onKey );
        return ()=>window.removeEventListener("keydown", onKey);

    },[check]);

    const gotIt = (correct:boolean) => {
        
        const avail = pool.length? pool : chars.slice(0);
        const randomIndex = Math.floor( avail.length*Math.random() ); 
        const pick = avail.splice(randomIndex,1)[0];

        setChar(pick);
        setPool(avail);
        setCheck(false); 


        setDones([
            ...dones.filter(d=>d.ch!=char.ch),
            {
                ch: char.ch,
                knewit: correct
            }
        ]) 
    }

    return <div>

            <div className='instructions'>
                <strong>ESC</strong> to quit 
                { !check && <><br/><strong>RIGHT →</strong> to continue </>}
            </div>
            <br/>
            <br/>
            <br/>


        {
            check? <div style={{ display:"flex", justifyContent:"center", alignItems:"center", gap:20 }}>
                      <div>
                        <GoldenSymbol size={180}>{char.ch}</GoldenSymbol>
                        <GoldenSymbol size={40}>{ char.pinyin }</GoldenSymbol>
                        <GoldenSymbol size={30}>{ char.en }</GoldenSymbol>
                      </div>
                      <div style={{ display:"flex", flexDirection:"column", gap:10 }}>
                        <h3>Did you got it?</h3>
                        <button onClick={()=>gotIt(true)}>↑ YES</button>
                        <button onClick={()=>gotIt(false)}>↓ NO</button>
                      </div>
                    </div>
            : <GoldenSymbol>{char.ch}</GoldenSymbol>
        }
        <br/>
        <div className={classes.progress}>
            <div style={{ width:`${ (dones.filter(d=>d.knewit).length / dones.length)*100 }%`}}></div>
        </div>
        <div className={ classes.chars }>
            { chars.map( char=><div key={char.ch} className={ classes["res"+dones.find(d=>d.ch==char.ch)?.knewit] }>{char.ch}</div> )}
        </div>
    </div>
}