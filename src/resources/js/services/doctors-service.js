import * as action from '../store/actions/doctors-action'
import Http from '../http'

function preparePaginateLink(page) {
    let link = '/api/v1/doctors';

    if (page > 1) {
        link = '/api/v1/doctors?page=' + page;
    }

    return link;
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


export function updatePatient(data) {
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

export function getPatientById(param) {
    let link = '/api/v1/doctors/' + param;

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
