import { useEffect, useRef, useState } from 'react'
import './App.css'
import { data } from './data/data'
import type { Line } from './data/types'
import { ChineseAudio } from './components/ChineseAudio'
import { Chinese } from './components/Chinese'
import { AudiosFilter } from './components/AudiosFilter'
import { Flag } from './components/flag'
import { TypeCharacter } from './components/TypeCharacter'
import { CharacterUseStats } from './components/CharacterUseStats'
import hsk from "./data/hsk.json"
import { HSK } from './components/HSK'

//const props = ["audio", "ch", "en"];

type Question = {
  line: Line
  //subject: Prop
  // guess: Prop
  num: number
}

function App() {
  const [showDetails, setShowDetails] = useState(false);
  const [myLines, setMyLines] = useState<Line[]>([]);
  const [myAvailableLines, setAvailableLines] = useState<Line[]>([]);
  const [question, setQuestion] = useState<Question | null>(null);
  const typeIndex = useRef(0); 
  const selectedLinesState = useState<number[]>(data.lines.map((_,i)=>i));
  const [mode, setMode] = useState(0);

  useEffect(() => {

    if (myLines.length > 0 && !question) {
      pickRandomQuestion();
    }

  }, [myLines, question]);

  //
  // handle keyboard input
  //
  useEffect(() => {

    const onKeyDown = (ev: KeyboardEvent) => {

      if( ev.code == 'Escape') {
        setQuestion(null);
        setAvailableLines([]); 
        setMyLines([]);
        setMode(0);
        return;
      }

      if( mode>1 ) return;
 
      if( myLines.length == 0 )
      {
        if (ev.code == 'ArrowRight') { 
          startQuiz(0);
        }
        else if ( ev.code == 'ArrowLeft') { 
          startQuiz(1);
        }
        return;
      }



      if (ev.code == 'ArrowRight' && mode==0 ) { 

          if (!showDetails) {
            setShowDetails(true);
            return;
          }
  
          setShowDetails(false);

          pickRandomQuestion();  
      }
      else if( ev.code == 'ArrowUp' )
      {
        addRandomLine()
      }
      else if(ev.code == 'ArrowDown') {
        addRandomLine(true)
      } 
    }

    window.addEventListener("keydown", onKeyDown);
    return () => {
      window.removeEventListener("keydown", onKeyDown);
    }

  }, [myLines, showDetails, mode]);

  useEffect(()=>{

    if( myAvailableLines.length>0 && !myLines.length )
    { 
      addRandomLine();
    }

  }, [myAvailableLines, question]);

  useEffect(()=>{
    if( selectedLinesState[0].length==0 ) {
      selectedLinesState[1](data.lines.map((_,i)=>i))
    }
  }, [selectedLinesState])

  const addRandomLine = ( quitar?:boolean ) => {
    const i = Math.floor(Math.random() * myAvailableLines.length);
    const lines = myAvailableLines.slice(0);


    if( quitar )
    {
      if( myLines.length>1 ) {
        const last = myLines.pop()!;
        setAvailableLines([ ...myAvailableLines, last ]);
        setMyLines([ ...myLines ]);
      }
      return;
    }

    if( myAvailableLines.length==0 )
    {
      return;
    }

    
    const itm = lines.splice(i, 1)[0];

    setAvailableLines(lines);
    setMyLines([...myLines, itm]);
  }

  const startQuiz = ( inMode:number=0 ) => {

    setMode(inMode);
    //addRandomLine();
    const lines = data.lines.filter( (_, i)=>selectedLinesState[0].indexOf(i)>-1 );
    if( !lines.length){
      alert("Select some lines to use...");
      return;
    };

    setAvailableLines( lines ); 
  }

  const startHSK = (v:number) => {
    setMode(1+v); 
  }

  const pickRandomQuestion = () => {
    // const x = typeIndex.current % props.length;
    // const y = Math.floor(typeIndex.current / props.length) % props.length;

    // if (x == y) {
    //   typeIndex.current++;
    //   pickRandomQuestion();
    //   return;
    // }

    
    const randomIndex = Math.floor(Math.random() * myLines.length);
    const newLine = myLines[randomIndex]; 

    

    if( newLine==question?.line && myLines.length>1){  
      pickRandomQuestion();
      return;
    }    

    setQuestion({
      line: newLine,
      // subject: props[x] as Prop,
      // guess: props[y] as Prop,
      num: question? question.num+1 : 1
    });


    typeIndex.current++ ;
  } 

  return (
    <>
      {/* <QuizContext.Provider value={{ nextQuestion:pickRandomQuestion }}>
        {question != null && <GuessCorrectOption line={question.line} subject={question.subject} guess={question.guess} availableLines={myLines}/>}
      </QuizContext.Provider> */}

      <div className='logo'>
      通<br/>过<br/>听<br/>来<br/>学<br/>习
      </div> 

      {
        question && <div>

          { mode==0? <>
                      <div style={{ fontSize:"2em"}}>
                      <ChineseAudio line={question.line} autoplay num={question.num}/>
                      </div>
                      {
                        showDetails && <div style={{ fontSize:"2em"}}>
                          <h6 style={{ color:"yellow"}}>{ question.line.en }</h6>
                          <Chinese line={question.line} pinyin/>
                          
                        </div>
                      }
                      </> 
            : 
            <>
            <TypeCharacter line={question.line} num={question.num} onFinish={()=>pickRandomQuestion()}/>
            </>}
          

          <div>
            <br/>
            <br/>
            <h3>Lines in use: <strong className='statNum'>{ myLines.length }</strong>  --- Lines available: <strong className='statNum'>{myAvailableLines.length}</strong></h3>
            <br/>
            <br/>
            <div className='instructions'><strong>UP ↑</strong> to add a new line.
            <br/> <strong>DOWN ↓</strong> to remove a line.
            <br/> <strong>LEFT ←</strong> to repeat sound.
            <br/> <strong>RIGHT →</strong> to move next.
            <br/> <strong>ESC</strong> quit
            </div>
          </div>
        </div>
      }

      {
        mode>1 && <div><HSK level={mode-1}/></div>
      }

      {
        (mode<2 && myLines.length == 0) && <>
        
        <Flag/> 
 
        <div className='menu'> 
          <div>
            Write...<br/><br/>
            <button onClick={()=>startQuiz(1)}>
            ← 字符 
            </button>
          </div>
          <div>
            Hear...<br/><br/>
            <button onClick={()=>startQuiz(0)}>
            听到  →
            </button>
          </div> 
        </div>

        <div className='menu'> 
          <div>
            Test your vocabulary<br/><a href={hsk.source} target='_blank'>Source</a>
          </div> 
            <button onClick={()=>startHSK(1)}>HSK 1</button>
            <button onClick={()=>startHSK(2)}>HSK 2</button>
            <button onClick={()=>startHSK(3)}>HSK 3</button>
        </div>
        

        <div className='appStats'>
          <div>
            <h3>Characters used by audios...</h3> 
            <CharacterUseStats/>
          </div>
          <div >
            <h3>Select audio lines to use (SHIFT+select for single selection)</h3> 
            
            <div style={{ display:"block"}}>
              <AudiosFilter audioLinesState={selectedLinesState}/>
            </div>
          </div> 
        </div>
        </>
      } 
      

      <div style={{ position:"absolute", top: -10, left: 0, transform:"rotate(90deg)", transformOrigin:"bottom left", opacity: 0.95}}>
        by <a href="https://github.com/bandinopla" target='_blank'>Bandinopla</a> | <a href="https://github.com/bandinopla/learn-chinese-by-listening" target='_blank'>open source</a></div>


    </>
  )
}

export default App
