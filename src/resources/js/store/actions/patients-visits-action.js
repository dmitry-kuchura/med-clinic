import * as ActionTypes from '../action-types';

export function getPatientVisits(payload) {
    return {
        type: ActionTypes.PATIENT_VISITS_LIST,
        payload
    }
}
