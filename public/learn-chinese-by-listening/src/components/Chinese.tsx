import { FC, useMemo } from "react";
import dicc from "../data/characterMap.json";
import { removeNonChinese } from "../data/data";
import type { CharacterDicc, CharacterInfo, Line } from "../data/types";
import classes from "./Chinese.module.css";

const typedDicc: CharacterDicc = dicc;
type CharInfo = { ch:string } & CharacterInfo;

export const Chinese: FC<{ line: Line, pinyin?:boolean }> = ({ line, pinyin }) => {
    const words = line.ch.split(" ");

    
    const wordInfo = useMemo(() => {

        //return words.map(word => typedDicc[removeNonChinese(word)])
        return words.reduce<{ [letter:string]:CharInfo[] }>( (out, word)=>{

            const chars:CharInfo[] = [];
            const _word = removeNonChinese(word);

            if( _word != "" )
            {
                chars.push( {
                    ch: word,
                    ...typedDicc[removeNonChinese(word)]
                } );

                if( _word.length>1 )
                {
                    word.split("").forEach( letter => {
                        chars.push( {
                            ch:letter, 
                            ...typedDicc[letter]
                        } );
                    });

                } 

                out[ _word ] = chars;
            }


            return out;
        } , {});

    }, [line]);


    return <div>

        <div className={classes.line}>
            {words.map((word, i) => <div key={i} className={classes.char} >
                <div style={{ fontSize: "1.5em" }}>{word}</div>
                { pinyin && <div style={{ opacity:0.8}}>
                    { wordInfo[ removeNonChinese(word) ]?.map( (info, j)=><div key={j}>
                        { j>0 && <div>{info.ch}</div> }
                        <div style={{ opacity: 0.6, fontSize:"0.8em" }}>{info.pinyin}</div>
                        <div style={{ color:"yellow", fontSize:"0.5em"}}>{info.means}</div>
                    </div> )}
                </div> }
            </div>)}
        </div>
    </div>
}