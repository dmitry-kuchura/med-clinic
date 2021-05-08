import * as ActionTypes from '../action-types';

export function getPatientVisits(payload) {
    return {
        type: ActionTypes.PATIENT_VISITS_LIST,
        payload
    }
}

export function getApprovedPatientsVisitsTemplate(payload) {
    return {
        type: ActionTypes.PATIENT_VISITS_APPROVED_TEMPLATES_LIST,
        payload
    }
}
