import * as action from '../store/actions/messages-templates-action'
import Http from '../http';

function preparePaginateLink(page) {
    let link = '/api/v1/messages-templates';

    if (page > 1) {
        link = '/api/v1/messages-templates?page=' + page;
    }

    return link;
}

export function getMessagesTemplatesList(page) {
    let link = preparePaginateLink(page);

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getMessagesTemplates(response.data.result));
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

export function getMessageTemplateById(id) {
    let link = '/api/v1/messages-templates/' + id;

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getMessageTemplate(response.data.result));
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

export function updateMessageTemplate(data) {
    let link = '/api/v1/messages-templates/';

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.put(link, data)
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
