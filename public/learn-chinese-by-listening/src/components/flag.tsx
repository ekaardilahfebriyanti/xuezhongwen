/**
 * wave effect credit: https://codepen.io/oj8kay/pen/PBZjpe
 */
import { useEffect, useRef } from "react";


const vertexShader = `uniform float u_Distance;
attribute vec2 a_Position;
varying vec2 v_UV;
varying float v_Slope;

float PI = 3.14159;
float scale = 0.8;

void main() {

  float x = a_Position.x;
  float y = a_Position.y;

  float amplitude = 1.0 - scale; // 振幅
  float period = 1.0;  // 周期
  float waveLength = 2.0 * scale;

// Option 1: Simple linear mapping
v_UV = (a_Position.xy + 1.0) * 0.5;

// Option 2: If you want to maintain some scaling
v_UV = (mat3(0.60,0,0, 0,0.60,0, 0.5,0.5,1) * vec3(x, y, 1.0)).xy;
  y += amplitude * ( (x - (-scale)) / waveLength) * sin(2.0 * PI * (x - u_Distance));

  float x2 = x - 0.001;
  float y2 = a_Position.y + amplitude * ( (x2 - (-scale)) / waveLength) * sin(2.0 * PI * (x2 - u_Distance));

  v_Slope = y - y2;
  gl_Position = vec4(vec2(x, y), 0.0, 1.0);
}`;

const fragmentShader = `precision mediump float;
uniform sampler2D u_Sampler;
varying vec2 v_UV;
varying float v_Slope;

void main() { 

  vec4 color = texture2D( u_Sampler, v_UV );
if (v_Slope > 0.0) {
    color = mix(color, vec4(0.0, 0.0, 0.0, 1.0), v_Slope * 400.0);
  }
  if (v_Slope < 0.0) {
    color = mix(color, vec4(1.0), abs(v_Slope) * 300.0);
  }
  
  // Optional: additional UV coordinate check
  if (v_UV.x < 0.0 || v_UV.x > 1.0 || v_UV.y < 0.0 || v_UV.y > 1.0) {
    discard;
  }
  gl_FragColor = color;
}`;

const ShaderUtil = {

    createShader: function (gl: WebGLRenderingContext, source: string, type: GLenum) {
        const shader = gl.createShader(type)

        if (!shader) { 
            throw new Error('Failed to create shader');
        }

        gl.shaderSource(shader, source)
        gl.compileShader(shader)

        if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {

            console.error('Compile shader source fail:\n\n' + source, '\n\n=====error log======\n\n', gl.getShaderInfoLog(shader))
            gl.deleteShader(shader)
            throw new Error('Compile shader source fail:\n\n' + source);
        }

        return shader
    },

    createProgram: function (gl: WebGLRenderingContext, vertexShader: WebGLShader, fragmentShader: WebGLShader, validate: boolean) {
        const program = gl.createProgram()

        if (!program) throw new Error("Failed to create program...");

        gl.attachShader(program, vertexShader)
        gl.attachShader(program, fragmentShader)
        gl.linkProgram(program)
        if (!gl.getProgramParameter(program, gl.LINK_STATUS)) {
            console.error('Creating shader program fail:\n', gl.getProgramInfoLog(program))
            gl.deleteProgram(program)
            return null
        }

        if (validate) {
            gl.validateProgram(program)
            if (!gl.getProgramParameter(program, gl.VALIDATE_STATUS)) {
                console.error('Error validating shader program:\n', gl.getProgramInfoLog(program))
                gl.deleteProgram(program)
                return null
            }
        }

        gl.detachShader(program, vertexShader)
        gl.detachShader(program, fragmentShader)
        gl.deleteShader(vertexShader)
        gl.deleteShader(fragmentShader)

        return program
    },


    createProgramFromSrc: function (gl: WebGLRenderingContext, vertexShaderSrc: string, fragmentShaderSrc: string, validate: boolean) {

        const vShader = ShaderUtil.createShader(gl, vertexShaderSrc, gl.VERTEX_SHADER)
        const fShader = ShaderUtil.createShader(gl, fragmentShaderSrc, gl.FRAGMENT_SHADER)

        return ShaderUtil.createProgram(
            gl,
            vShader,
            fShader,
            validate
        )
    },

    getSrcFromUrl: function (url: string, callback: (res: string) => void) {
        const xhr = new XMLHttpRequest()
        xhr.open('GET', url, true)
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    callback(xhr.responseText)
                }
            }
        }
        xhr.send()
    }
}

class Shaders {
    program: WebGLProgram;
    private gl: WebGLRenderingContext;

    constructor(gl: WebGLRenderingContext, vShaderSrc: string, fShaderSrc: string) {
        const program = ShaderUtil.createProgramFromSrc(gl, vShaderSrc, fShaderSrc, true);
        
        if (!program) {
            throw new Error('Failed to create WebGL program');
        }

        this.program = program;
        this.gl = gl;
        gl.useProgram(this.program);
    }

    activate(): this {
        this.gl.useProgram(this.program);
        return this;
    }

    deactivate(): this {
        this.gl.useProgram(null);
        return this;
    }

    dispose(): void {
        // Fix the syntax error in the original getParameter check
        if (this.gl.getParameter(this.gl.CURRENT_PROGRAM) === this.program) {
            this.deactivate();
        }
        this.gl.deleteProgram(this.program);
    }
}



function createTexture(
    gl: WebGLRenderingContext, 
    image: HTMLImageElement
): WebGLTexture {
    const texture = gl.createTexture();

    if (!texture) {
        throw new Error('Failed to create WebGL texture');
    }

    gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, 1);
    gl.activeTexture(gl.TEXTURE0);
    gl.bindTexture(gl.TEXTURE_2D, texture);
    
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
    
    gl.texImage2D(
        gl.TEXTURE_2D, 
        0, 
        gl.RGB, 
        gl.RGB, 
        gl.UNSIGNED_BYTE, 
        image
    );

    return texture;
}


export const Flag = () => { 
    const imgRef = useRef<HTMLImageElement | null>(null);
    const canvasRef = useRef<HTMLCanvasElement | null>(null);

    useEffect(()=>{

        if (imgRef.current && canvasRef.current) {
             
            // Now you can safely use imgRef.current and canvasRef.current
            const image = imgRef.current;
            const canvas = canvasRef.current;
            const gl = canvas.getContext('webgl');
            let shader : Shaders | null;
            let eleSize = 0  
            let vertexCount = 0

            if( gl )
            {
                //const loseContextExt = gl.getExtension("WEBGL_lose_context");
                let speed = 1 
                let stop = false
                let timeLast = Date.now()
                let timeNow
                let delta
                let fps = 60
                let interval = 1000 / fps 
                let distance = 0 

                const initializeFlagWidget = ()=>{
                    image.style.display = "none";
                    canvas.style.display = "inline-block";
                    canvas.width = image.width;
                    canvas.height = image.height; 

                    try {
                        shader = new Shaders(gl, vertexShader, fragmentShader)
                    }
                    catch( err ) {
                        //silent error
                        console.error( err );
                        return;
                    }
    
                    const aPosition = gl.getAttribLocation(shader.program, 'a_Position')
                    const uDistance = gl.getUniformLocation(shader.program, 'u_Distance')
    
                    createVerticesBuffer(gl, image.width)
                    gl.vertexAttribPointer(aPosition, 2, gl.FLOAT, false, eleSize * 2, 0)
                    gl.enableVertexAttribArray(aPosition)

                    createTexture(gl, image)
                    const uSampler = gl.getUniformLocation(shader.program, 'u_Sampler')
                    gl.uniform1i(uSampler, 0)

                    function createVerticesBuffer(gl: WebGLRenderingContext, imgWidth: number): WebGLBuffer {
                        const vertices: number[] = []; 
                    
                        for (let i = 0; i <= imgWidth; i++) {
                            const x = -1 + 2 * i / imgWidth;
                            vertices.push(x, -1, x, 1);
                        }
                    
                        vertexCount = 2 * (imgWidth + 1);
                        const verticesArray = new Float32Array(vertices);
                        eleSize = verticesArray.BYTES_PER_ELEMENT;
                    
                        const buffer = gl.createBuffer();
                        
                        if (!buffer) {
                            throw new Error('Failed to create WebGL buffer');
                        }
                    
                        gl.bindBuffer(gl.ARRAY_BUFFER, buffer);
                        gl.bufferData(gl.ARRAY_BUFFER, verticesArray, gl.STATIC_DRAW);
                        
                        return buffer;
                    }

                    function draw () {
                        gl!.clear(gl!.COLOR_BUFFER_BIT)
                        gl!.drawArrays(gl!.TRIANGLE_STRIP, 0, vertexCount)
                    }
    
                    function tick () {
                        if (stop) return false
                        timeNow = Date.now()
                        delta = timeNow - timeLast
                        if (delta > interval) {
                          timeLast = timeNow
                          distance += delta * 0.001 * speed
                          gl!.uniform1f(uDistance, distance)
                          draw()
                        } 
                        requestAnimationFrame(tick)
                      }

                    draw()
                    tick()
                }
 
                // Your logic here 
                image.crossOrigin = 'anonymous'
                image.onload = ()=>{
                    if( !gl ) return;

                    initializeFlagWidget();
                } 

                //image.style.display = "none";
 

                const onWebGlContextLost = (event:Event) =>{
                    event.preventDefault(); 
                    canvas.style.display = "none";
                    image.style.display = "inline-block";
                }
                canvas.addEventListener("webglcontextlost", onWebGlContextLost);
                

                return ()=> {  
                    canvas.removeEventListener("webglcontextlost", onWebGlContextLost); 
                    //loseContextExt?.loseContext(); 
                }
            }  
        }

    }, [imgRef, canvasRef]);

    return <div>
        <canvas ref={canvasRef} style={{ display:"none"}}></canvas>
        <img ref={imgRef} src="/flag-400.png" width={300} height={220} alt='flag'/>
        </div>
}