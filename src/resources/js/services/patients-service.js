import * as action from '../store/actions/patients-action'
import Http from '../http'

function preparePaginateLink(page) {
    let link = '/api/v1/patients';

    if (page > 1) {
        link = '/api/v1/patients?page=' + page;
    }

    return link;
}

export function getPatientsList(page) {
    let link = preparePaginateLink(page);

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getPatients(response.data.result));
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

export function searchPatientsList(query) {
    return dispatch => (
        new Promise((resolve, reject) => {
            Http.post('/api/v1/patients/search', {
                query: query
            })
                .then(response => {
                    dispatch(action.search());
                    return resolve(response.data.result);
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

export function updatePatient(data) {
    let link = '/api/v1/patients/';

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

export function createPatient(data) {
    let link = '/api/v1/patients/create';

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

export function getPatientById(param) {
    let link = '/api/v1/patients/' + param;

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getOnePatient(response.data.result));
                    return resolve();
                })
                .catch(err => {
                    console.log(err)
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
