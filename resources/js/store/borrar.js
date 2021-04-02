const fen = function fn(){
    function fn2(...arg){
        let result = fn2(...arg)
        while(typeof result === 'function') result = result();
    
        return result;
    }   
}

const trampoline = fen();
// fen => (...arg) => {
//     let result = fen(...arg);
//     while(typeof result === 'function') result = result();

//     return result;
// }

const suma = (number, sum = 0) => (
    number === 0 ? sum : () => suma(number - 1, sum + number)
)

const tsuma = trampoline(suma)

console.log(tsuma(10000));

function creaFunc() {
    var nombre = "Mozilla";
    function muestraNombre() {
        console.log(nombre);
    }
    return muestraNombre;
  }
  
  var miFunc = creaFunc();
//   miFunc(); 