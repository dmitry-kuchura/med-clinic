import * as ActionTypes from '../action-types';

export function getDoctors(payload) {
    return {
        type: ActionTypes.DOCTOR_LIST,
        payload
    }
}

export function search() {
    return {
        type: ActionTypes.DOCTOR_SEARCH
    }
}

export function getOneDoctor(payload) {
    return {
        type: ActionTypes.DOCTOR_INFO,
        payload
    }
}
