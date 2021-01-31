import * as ActionTypes from "../action-types";

const category = {
    id: null,
    name: null,
    status: null,
    createdAt: null,
};

const initialState = {
    list: [],
    item: category,
};

const Records = (state = initialState, {type, payload = null}) => {
    switch (type) {
        case ActionTypes.CATEGORIES_LIST:
            return applyCategories(state, payload);
        case ActionTypes.CATEGORIES_SHORT_LIST:
            return applyShortCategories(state, payload);
        case ActionTypes.CATEGORIES_INNER:
            return applyRecord(state, payload);
        default:
            return state;
    }
};

const applyCategories = (state, payload) => {
    state = Object.assign({}, state, {
        list: payload.data
    });

    return state;
};

const applyShortCategories = (state, payload) => {
    state = Object.assign({}, state, {
        list: payload
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
