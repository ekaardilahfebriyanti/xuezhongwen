import React, { FC, MouseEvent } from "react";
import style from "./CheckBox.module.css";

export type CheckBoxState = [ number[], React.Dispatch<React.SetStateAction<number[]>> ];

export const CheckBox : FC<{ index:number, onlyOne?:boolean, shiftForSingleSelection?:boolean, state:CheckBoxState, correction?:boolean|undefined, disabled?:boolean }> = ({ index, onlyOne, state, correction, disabled, shiftForSingleSelection })=>{
    const marked = state[0].indexOf(index)>-1;

    const handleMouseDown = (event:MouseEvent) => {
        if (event.shiftKey) {
          event.preventDefault(); // Prevents text selection
        }
      };

    const onClick = (ev:MouseEvent) => {

        if( disabled || correction!==undefined ) return;

        if( ev.shiftKey && shiftForSingleSelection )
        {
            ev.preventDefault(); 
            state[1]([ index ]);
            return;
        }
        
        if( onlyOne )
        {
            state[1]( marked? [] : [ index ]);
        } else 
        {
            if( marked )
            {
                state[1]( state[0].filter((i)=>i!==index) )
            }
            else 
            {
                state[1]( [ ...state[0], index ] )
            }
        }
    }
//style={{ background: marked ?"black" : "white", width:20, height:20, cursor:"pointer", border:"10px solid white"}}
    return <div onMouseDown={handleMouseDown} onClick={onClick} className={ [ style.checkbox, marked? style.marked : "", correction!==undefined? correction? style.correct : style.incorrect : "" ].join(" ") }>
            </div>
}