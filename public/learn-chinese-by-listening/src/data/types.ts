export interface Line {
    ch: string;
    en: string;
    audio: string;
    source: string
};

export type CharacterInfo = {
    pinyin: string;
    means: string; 
};

export type CharacterDicc = { [key: string]: CharacterInfo } ;