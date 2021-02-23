import * as ActionTypes from '../action-types';

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
