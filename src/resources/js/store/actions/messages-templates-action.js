import * as ActionTypes from '../action-types';

export function getMessagesTemplates(payload) {
    return {
        type: ActionTypes.MESSAGES_TEMPLATES_LIST,
        payload
    }
}
