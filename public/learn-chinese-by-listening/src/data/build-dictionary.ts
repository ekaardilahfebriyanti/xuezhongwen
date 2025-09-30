import { data, removeNonChinese } from "./data";
import * as fs from "fs";
import type { CharacterInfo, Line } from "./types";  

type Result = Record<string, CharacterInfo>;



/**
 * Scan the data lines, extract each word and then each character, to create a dictionary template that you
 * will have to complete with the translations and such...
 */
function generateCharacterMap(data: { lines: Line[] }, characterMap:Result ): Result { 

    for (const line of data.lines) {
 
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

        tokens.forEach( token => {

            if( token=="") return;

            if( !characterMap[ token ] )
            {
                characterMap[ token ] = {
                    pinyin:"",
                    means:"" 
                }
            } 

        });


        
    }

    return characterMap;
}


const filePath = new URL('./characterMap.json', import.meta.url).pathname; 
let dicc :Result = {};

if( fs.existsSync(filePath) )
{ 
    dicc = JSON.parse( fs.readFileSync(filePath,{ encoding:"utf-8"}) );
}

// Generate the result
const result = generateCharacterMap(data, dicc);
 

fs.writeFileSync(filePath, JSON.stringify(result, null, 2), "utf-8");

