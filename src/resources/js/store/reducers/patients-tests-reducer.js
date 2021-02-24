import * as ActionTypes from '../action-types';

const initialState = {
    from: null,
    to: null,
    perPage: null,
    currentPage: null,
    lastPage: null,
    total: null,
    list: [],
};

const PatientsTests = (state = initialState, {type, payload = null}) => {
    switch (type) {
        case ActionTypes.PATIENT_TESTS_LIST:
            return applyPatientTests(state, payload);
        default:
            return state;
    }
};

const applyPatientTests = (state, payload) => {
    state = Object.assign({}, state, {
        list: payload,
    });

    return state;
};

export default PatientsTests;
