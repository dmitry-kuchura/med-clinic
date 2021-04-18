import * as action from '../store/actions/patients-visits-action'
import Http from '../http'

function preparePaginateLink(page, id) {
    let link = '/api/v1/visits/' + id + '/list';

    if (page > 1) {
        link = '/api/v1/visits/' + id + '/list?page=' + page;
    }

    return link;
}

export function getPatientsVisits(page, id) {
    let link = preparePaginateLink(page, id);

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getPatientVisits(response.data.result));
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
