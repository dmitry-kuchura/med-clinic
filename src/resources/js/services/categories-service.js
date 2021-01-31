import * as action from '../store/actions/categories-action'
import Http from '../http'

function preparePaginateLink(page) {
    let link = '/api/v2/categories';

    if (page > 1) {
        link = '/api/v2/categories?page=' + page;
    }

    return link;
}

export function getCategoriesShortList() {
    let link = '/api/v2/categories/short';

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getCategoriesShort(response.data.result));
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

export function getCategoriesList(page) {
    let link = preparePaginateLink(page);

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getCategories(response.data.result));
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

export function getCategoryById(param) {
    let link = '/api/v2/categories/' + param;

    return dispatch => (
        new Promise((resolve, reject) => {
            Http.get(link)
                .then(response => {
                    dispatch(action.getOneCategory(response.data.result));
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
