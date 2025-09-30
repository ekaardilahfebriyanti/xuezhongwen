import { FC, useMemo } from "react";
import { CharacterDicc, CharacterInfo, Line } from "../data/types";
import { removeNonChinese } from "../data/data";
import characterMap from "../data/characterMap.json";


type Ref = {
    ch:string
} & CharacterInfo;

export const ChineseSymbolsRef : FC<{ line:Line, num:number }> = ({ line, num  })=>{
    const refs = useMemo(()=>{

        const tokens : Set<string> = new Set();
        const words   = line.ch.split(" ");

        words.forEach( word => {  
            tokens.add( removeNonChinese(word) );
        });

        for (const char of line.ch) {
            // Skip characters that are not Chinese (e.g., punctuation)
            const c = removeNonChinese(char);
            if ( c!=="" ) { 
 
                tokens.add(char); 
            }
        } 

        const _chars: Ref[] = [];

        tokens.forEach( token => {

            if( token=="") return; 

            const dicc : CharacterDicc = characterMap as CharacterDicc; 
            const info : CharacterInfo = dicc[ token ];

            _chars.push( {
                ch: token,
                ...info
            } )

        });

        return _chars;

    }, [line, num]);

    return <div style={{ display:"flex", flexWrap:"wrap", gap:10, justifyContent:"center" }}>
        {
            refs.map( (info, i)=>{
                return <div key={i} style={{ display:"flex", gap:10, padding:20, border:"1px solid rgba(255,255,255,0.3)" }}>
                    <div style={{ }}>{ info.ch}</div>
                    <div style={{ display:"flex", flexDirection:"column", alignItems:"flex-start", fontSize:"0.5em" }}>
                        <div>{ info.pinyin }</div>
                        <div style={{ color:"yellow"}}>{ info.means }</div>
                    </div>
                </div>
            })
        }
    </div>
}