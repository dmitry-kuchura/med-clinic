import * as action from '../store/actions/doctors-action'
import Http from '../http'

function preparePaginateLink(page) {
    let link = '/api/v1/doctors';

    if (page > 1) {
        link = '/api/v1/doctors?page=' + page;
    }

    return link;
}

function prepareApprovedPaginateLink(page) {
    let link = '/api/v1/doctors/approved';

    if (page > 1) {
        link = '/api/v1/doctors/approved?page=' + page;
    }

    return link;
}

export function getDoctorsApprovedList(page) {
    let link = prepareApprovedPaginateLink(page);

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getDoctorsApproved(response.data.result));
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

export function getDoctorsList(page) {
    let link = preparePaginateLink(page);

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getDoctors(response.data.result));
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

export function updateDoctor(data) {
    let link = '/api/v1/doctors/';

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

export function getDoctorById(param) {
    let link = '/api/v1/doctors/' + param;

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getOneDoctor(response.data.result));
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

export function searchDoctorsList(query) {
    return dispatch => (
        new Promise((resolve, reject) => {
            Http.post('/api/v1/doctors/search', {
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
