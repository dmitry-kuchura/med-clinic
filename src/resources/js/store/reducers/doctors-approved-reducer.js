import * as ActionTypes from '../action-types';

const approved = {
    id: null,
    first_name: null,
    doctor,
    created_at: null,
    updated_at: null
};
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
    item: approved
};

const DoctorsApproved = (state = initialState, {type, payload = null}) => {
    switch (type) {
        case ActionTypes.DOCTOR_APPROVED_LIST:
            return applyDoctors(state, payload);
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

export default DoctorsApproved;
