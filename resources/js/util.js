
export const fieldRequire = [ v => ( v === 0 || !!v ) || 'Este campo es requerido' ];
export const emailRequire = [
    v => !!v || 'Este campo es requerido',
    v => /.+@.+/.test(v) || 'El email no es válido',
];

export function isNumber(keyCode) {
    return !((keyCode < 48 || (keyCode > 57 && keyCode < 96 || keyCode > 105)) && (keyCode != 8 && keyCode != 46 && keyCode != 37 && keyCode != 39 && keyCode != 13))?true:false;
}

export function getImage(path){

    let protocol = location.protocol;
    let slashes = protocol.concat("//");
    let host = slashes.concat(window.location.hostname);
    return host + '/'+path
}

export function objectToGetParameters(object){
    return Object.entries(object).filter(item => item[1]).map(([key, val]) => val?`${key}=${val}`:false).join('&');
}