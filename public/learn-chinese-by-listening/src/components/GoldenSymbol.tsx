import { FC, PropsWithChildren } from "react";
import classes from "./GoldenSymbol.module.css";

export const GoldenSymbol :FC<PropsWithChildren<{ size?:number}>> = ({ children, size=200 })=>{
    return <div className={classes.gold} style={{ fontSize:size+"px"}}>{children}</div>
}