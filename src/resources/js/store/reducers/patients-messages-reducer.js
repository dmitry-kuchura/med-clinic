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

const PatientsMessages = (state = initialState, {type, payload = null}) => {
    switch (type) {
        case ActionTypes.PATIENT_MESSAGES_LIST:
            return applyPatientMessages(state, payload);
        default:
            return state;
    }
};

const applyPatientMessages = (state, payload) => {
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

export default PatientsMessages;
