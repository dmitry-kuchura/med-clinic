import * as ActionTypes from '../action-types';

const patient = {
    id: null,
    first_name: null,
    last_name: null,
    address: null,
    phone: null,
    email: null,
    gender: 'male',
    birthday: null,
    createdAt: null,
    updatedAt: null
};

const initialState = {
    from: null,
    to: null,
    perPage: null,
    currentPage: null,
    lastPage: null,
    total: null,
    list: [],
    item: patient
};

const Patients = (state = initialState, {type, payload = null}) => {
    switch (type) {
        case ActionTypes.PATIENT_LIST:
            return applyPatients(state, payload);
        case ActionTypes.PATIENT_INFO:
            return applyPatient(state, payload);
        default:
            return state;
    }
};

const applyPatients = (state, payload) => {
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

const applyPatient = (state, payload) => {
    state = Object.assign({}, state, {
        item: {
            id: payload.id,
            first_name: payload.first_name,
            middle_name: payload.middle_name,
            last_name: payload.last_name,
            address: payload.address,
            phone: payload.phone,
            email: payload.user ? payload.user.email : null,
            gender: payload.gender,
            birthday: payload.birthday,
            createdAt: payload.createdAt,
            updatedAt: payload.updatedAt,
            tests: [],
        }
    });

    return state;
};

export default Patients;
