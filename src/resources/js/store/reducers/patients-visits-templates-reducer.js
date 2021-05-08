import * as ActionTypes from '../action-types';

const initialState = {
    list: [],
    approved: [],
};

const PatientVisitsTemplates = (state = initialState, {type, payload = null}) => {
    switch (type) {
        case ActionTypes.PATIENT_VISITS_APPROVED_TEMPLATES_LIST:
            return applyPatientVisits(state, payload);
        default:
            return state;
    }
};

const applyPatientVisits = (state, payload) => {
    state = Object.assign({}, state, {
        list: payload.list,
        approved: payload.approved
    });

    return state;
};

export default PatientVisitsTemplates;
