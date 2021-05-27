import * as ActionTypes from '../action-types';

export function getMessages(payload) {
    return {
        type: ActionTypes.MESSAGES_LIST,
        payload
    }
}

