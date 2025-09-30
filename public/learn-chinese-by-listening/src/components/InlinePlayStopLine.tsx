import { FC } from "react";
import { Line } from "../data/types";
import { ChineseAudio } from "./ChineseAudio";

export const InlinePlayStopLine: FC<{ line:Line, ignoreKeys?:boolean, hideSlowly?:boolean, hideSource?:boolean }> = ({ line , ignoreKeys, hideSlowly, hideSource }) => {
    return <div ><ChineseAudio line={line} num={0} ignoreKeys={ignoreKeys} hideSlowly={hideSlowly} hideSource={hideSource}/></div>
}