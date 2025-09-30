import { FC } from "react";

export const AnswerResultIcon:FC<{ isOk:boolean }> = ({ isOk }) => {
    return <span style={{ fontSize:"3em", display:"inline-block", width:50, color: isOk?"green":"red"}}>{ isOk?"✔":"𐄂"}</span>
}