import * as ActionTypes from "../action-types";

const record = {
    id: null,
    name: null,
    status: null,
    views: null,
    translation: null,
    translations: [],
    createdAt: null,
};

const initialState = {
    from: null,
    to: null,
    perPage: null,
    currentPage: null,
    lastPage: null,
    total: null,
    list: [],
    item: record
};

const Records = (state = initialState, {type, payload = null}) => {
    switch (type) {
        case ActionTypes.BLOG_LIST:
            return applyRecords(state, payload);
        case ActionTypes.BLOG_INNER:
            return applyRecord(state, payload);
        default:
            return state;
    }
};

const applyRecords = (state, payload) => {
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

const applyRecord = (state, payload) => {
    state = Object.assign({}, state, {
        item: payload
    });

    return state;
};

export default Records;
