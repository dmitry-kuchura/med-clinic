import * as ActionTypes from '../action-types';

const doctor = {
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
    item: doctor
};

const Doctors = (state = initialState, {type, payload = null}) => {
    switch (type) {
        case ActionTypes.DOCTOR_LIST:
            return applyDoctors(state, payload);
        case ActionTypes.DOCTOR_INFO:
            return applyDoctor(state, payload);
        default:
            return state;
    }
};

const applyDoctors = (state, payload) => {
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

const applyDoctor = (state, payload) => {
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

export default Doctors;
