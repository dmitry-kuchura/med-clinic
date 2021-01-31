import * as ActionTypes from "../action-types";

export function getCategories(payload) {
    return {
        type: ActionTypes.CATEGORIES_LIST,
        payload
    }
}

export function getCategoriesShort(payload) {
    return {
        type: ActionTypes.CATEGORIES_SHORT_LIST,
        payload
    }
}

export function getOneCategory(payload) {
    return {
        type: ActionTypes.CATEGORIES_INNER,
        payload
    }
}
