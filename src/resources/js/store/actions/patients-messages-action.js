import * as ActionTypes from '../action-types';

export function getPatientMessages(payload) {
    return {
        type: ActionTypes.PATIENT_MESSAGES_LIST,
        payload
    }
}
