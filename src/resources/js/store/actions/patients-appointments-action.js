import * as ActionTypes from '../action-types';

export function getPatientAppointments(payload) {
    return {
        type: ActionTypes.PATIENT_APPOINTMENTS_LIST,
        payload
    }
}
