import * as ActionTypes from '../action-types';

export function getPatients(payload) {
    return {
        type: ActionTypes.PATIENT_LIST,
        payload
    }
}

export function search() {
    return {
        type: ActionTypes.PATIENT_SEARCH
    }
}

export function getOnePatient(payload) {
    return {
        type: ActionTypes.PATIENT_INFO,
        payload
    }
}
