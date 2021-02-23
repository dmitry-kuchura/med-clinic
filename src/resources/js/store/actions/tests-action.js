import * as ActionTypes from '../action-types';

export function getAllTests(payload) {
    return {
        type: ActionTypes.TEST_ALL,
        payload
    }
}

export function getTests(payload) {
    return {
        type: ActionTypes.TEST_LIST,
        payload
    }
}

export function getOneTest(payload) {
    return {
        type: ActionTypes.TEST_INFO,
        payload
    }
}
