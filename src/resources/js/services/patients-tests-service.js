import * as action from '../store/actions/patients-tests-action'
import Http from '../http'

function preparePaginateLink(page) {
    let link = '/api/v1/patients';

    if (page > 1) {
        link = '/api/v1/patients?page=' + page;
    }

    return link;
}

export function addPatientTest(data) {
    let link = '/api/v1/patients/add-test';

    const formData = new FormData();

    for (const [key, value] of Object.entries(data)) {
        formData.append(key, value)
    }

    const config = {
        headers: {
            'content-type': 'multipart/form-data'
        }
    }

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.post(link, formData, config)
                .then(response => {
                    return resolve(response);
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

export function getPatientsTests(id) {
    let link = '/api/v1/patients/' + id + '/tests';

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getPatientTests(response.data.result));
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
