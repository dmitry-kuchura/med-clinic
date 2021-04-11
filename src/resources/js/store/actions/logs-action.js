import * as ActionTypes from '../action-types';

export function getLogs(payload) {
    return {
        type: ActionTypes.LOGS_LIST,
        payload
    }
}
