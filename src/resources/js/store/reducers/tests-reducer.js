import * as ActionTypes from '../action-types';

const test = {
    id: null,
    name: null,
    cost: null,
    reference_value: null,
    createdAt: null,
    updatedAt: null,
};

const initialState = {
    from: null,
    to: null,
    perPage: null,
    currentPage: null,
    lastPage: null,
    total: null,
    list: [],
    item: test
};

const Tests = (state = initialState, {type, payload = null}) => {
    switch (type) {
        case ActionTypes.TEST_ALL:
            return applyAllTests(state, payload);
        case ActionTypes.TEST_LIST:
            return applyTests(state, payload);
        case ActionTypes.TEST_INFO:
            return applyTest(state, payload);
        default:
            return state;
    }
};

const applyTests = (state, payload) => {
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

const applyAllTests = (state, payload) => {
    state = Object.assign({}, state, {
        list: payload
    });

    return state;
};

const applyTest = (state, payload) => {
    state = Object.assign({}, state, {
        item: payload
    });

    return state;
};

export default Tests;
