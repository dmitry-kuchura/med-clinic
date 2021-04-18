import * as action from '../store/actions/patients-messages-action'
import Http from '../http';

function preparePaginateLink(page, patientId) {
    let link = '/api/v1/messages/' + patientId + '/list';

    if (page > 1) {
        link = '/api/v1/messages/' + patientId + '/list?page=' + page;
    }

    return link;
}

export function sendPatientMessage(patientId, data) {
    let link = '/api/v1/messages/send';

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.post(link, data)
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

export function getPatientMessagesList(page, id) {
    let link = preparePaginateLink(page, id);

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getPatientMessages(response.data.result));
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
