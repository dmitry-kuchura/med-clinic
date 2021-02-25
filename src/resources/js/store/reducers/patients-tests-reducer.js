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
        from: payload.from,
        to: payload.to,
        perPage: payload.per_page,
        currentPage: payload.current_page,
        lastPage: payload.last_page,
        total: payload.total,
        list: payload.data
    });

    return state;
};

export default PatientsTests;
