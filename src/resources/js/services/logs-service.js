import * as action from '../store/actions/logs-action'
import Http from '../http'

function preparePaginateLink(page) {
    let link = '/api/v1/logs';

    if (page > 1) {
        link = '/api/v1/logs?page=' + page;
    }

    return link;
}

export function getLogsList(page) {
    let link = preparePaginateLink(page);

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getLogs(response.data.result));
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
