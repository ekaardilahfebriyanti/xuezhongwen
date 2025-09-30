import { useEffect, useMemo, useRef } from "react"
import { CharacterDicc, CharacterInfo } from "../data/types"
import { data, removeNonChinese } from "../data/data"
import dicc from "../data/characterMap.json"; 
import * as d3 from "d3";
import cloud, { Word } from "d3-cloud";

type CloudChar = Word & {text: string;
    size: number;
}
type CharStat = {
    use:number
    lastLine:number,
    info:CharacterInfo & { ch:string }
}
type Stats = {
    total:number 
    perChar:CharStat[]
}

function normalizeFontSize(charStats:CharStat[], minSize:number, maxSize:number) {
    const maxUse = Math.max(...charStats.map(stat => stat.use));
    return charStats.map(stat => ({
        ...stat,
        fontSize: Math.round(minSize + (stat.use / maxUse) * (maxSize - minSize)),
    }));
} 


export const CharacterUseStats = ()=>{
    const divRef = useRef<HTMLDivElement>(null);
    const stats = useMemo(()=>{

        const _stats : Stats = { total:data.lines.length, perChar:[] };
        
        data.lines.forEach( (line, lineNum)=>{

            removeNonChinese( line.ch ).split("").forEach( symbol=>{
                    
                const info = _stats.perChar.find(c=>c.info.ch==symbol);

                if( !info )
                { 
                    _stats.perChar.push({
                        use: 1,
                        lastLine: lineNum,
                        info: {
                            ch:symbol,
                            ...(dicc as CharacterDicc)[symbol],
                        }
                    })
                }
                else 
                {
                    if( info.lastLine!=lineNum )
                    {
                        info.use++;
                        info.lastLine = lineNum;
                    }
                    
                }
            });

        })
                

                _stats.perChar.sort( (a, b)=>b.use-a.use);

        return _stats;

    }, []);

    useEffect(()=>{

        const charStats = normalizeFontSize(stats.perChar, 8, 120);

        function draw(words:CloudChar[]) {
            d3.select( divRef.current )
              .append("svg")
              .attr("width", 400)
              .attr("height", 800)
              .attr("viewBox", "0 0 400 800")
              .append("g")
              .attr("transform", "translate(200,400)")
              .selectAll("text")
              .data(words)
              .enter()
              .append("text")
              .style("font-size", d => `${d.size}px`)
              .style("font-family", "Impact")
              .style("fill", "#eee")
              .attr("text-anchor", "middle")
              .attr("transform", d => `translate(${d.x},${d.y}) rotate(${d.rotate})`)
              .text(d => d.text)
              .on("click", (event, d) => console.log(event, d));

        }

        if( divRef.current )
            divRef.current.innerHTML = "";

        const myCloud = cloud<{ text:string, size:number }>()
            .size([400, 800])
            .words(charStats.map(stat => ({
                text: stat.info.ch,
                size: stat.fontSize,
            })))
            .padding(5)
            .rotate(() => (Math.random() > 0.5 ? -5 : 5))
            .font("Impact")
            .fontSize(d => d.size)
            .on("end", draw)
            .start();

        return ()=>{
            myCloud.stop();
        }

    }, [ stats ])

    return <div ref={divRef}></div> 
}