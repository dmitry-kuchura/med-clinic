import * as action from '../store/actions/tests-action'
import Http from '../http'

function preparePaginateLink(page) {
    let link = '/api/v1/tests';

    if (page > 1) {
        link = '/api/v1/tests?page=' + page;
    }

    return link;
}

export function getTestsList(page) {
    let link = preparePaginateLink(page);

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getTests(response.data.result));
                    return resolve();
                })
                .catch(err => {
                    const statusCode = err.response.status;
                    const data = {
                        error: null,
                        statusCode,
                    };
                    return reject(data);
                })
        })
    );
}

export function updateTest(data) {
    let link = '/api/v1/tests/';

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.put(link, data)
                .then(response => {
                    return resolve();
                })
                .catch(err => {
                    const statusCode = err.response.status;
                    const data = {
                        error: null,
                        statusCode,
                    };
                    return reject(data);
                })
        })
    );
}

export function createTest(data) {
    let link = '/api/v1/tests/create';

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.post(link, data)
                .then(response => {
                    return resolve();
                })
                .catch(err => {
                    const statusCode = err.response.status;
                    const data = {
                        error: null,
                        statusCode,
                    };
                    return reject(data);
                })
        })
    );
}

export function getTestById(param) {
    let link = '/api/v1/tests/' + param;

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getOneTest(response.data.result));
                    return resolve();
                })
                .catch(err => {
                    const statusCode = err.response.status;
                    const data = {
                        error: null,
                        statusCode,
                    };
                    return reject(data);
                })
        })
    );
}
