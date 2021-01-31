import * as action from '../store/actions/records-action'
import Http from '../http'

function preparePaginateLink(page) {
    let link = '/api/v2/blog';

    if (page > 1) {
        link = '/api/v2/blog?page=' + page;
    }

    return link;
}

export function getRecordsList(page) {
    let link = preparePaginateLink(page);

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getRecords(response.data.result));
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

export function updateRecord(id, data) {
    let link = '/api/v2/blog/' + id;

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

export function getRecordById(param) {
    let link = '/api/v2/blog/' + param;

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getOneRecord(response.data.result));
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
