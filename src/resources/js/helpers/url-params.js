export function getParamFromUrl(props, param) {
    if (props.hasOwnProperty('match')) {
        if (props.match.hasOwnProperty('params')) {
            return props.match.params[param];
        }
        
        return null;
    }

    return null;
}
