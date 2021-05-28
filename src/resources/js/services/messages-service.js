import * as action from '../store/actions/messages-action'
import Http from '../http';

function preparePaginateLink(page, phone, status) {
    let url = '';
    let link = '/api/v1/messages';
    let array = [];

    if (page > 1) {
        array.push({name: 'page', value: page});
    }

    if (phone) {
        array.push({name: 'phone', value: phone});
    }

    if (status) {
        array.push({name: 'status', value: status});
    }

    array.forEach(function (e, key) {
        url += e.name + '=' + e.value + '&';
    });

    return link + '?' + url;
}

export function getMessagesList(page, phone, status) {
    let link = preparePaginateLink(page, phone, status);

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getMessages(response.data.result));
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

export function getMessagesBalance() {
    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get('/api/v1/messages/balance')
                .then(response => {
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
