import * as ActionTypes from '../action-types';

export function getPatientTests(payload) {
    return {
        type: ActionTypes.PATIENT_TESTS_LIST,
        payload
    }
}
