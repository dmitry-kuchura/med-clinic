import * as ActionTypes from '../action-types';

const log = {
    id: null,
    message: null,
    context: null,
    level: null,
    request_headers: null,
    request: null,
    response_headers: null,
    response: null,
    remote_addr: null,
    user_agent: null,
    createdAt: null,
    updatedAt: null,
};

const initialState = {
    from: null,
    to: null,
    perPage: null,
    currentPage: null,
    lastPage: null,
    total: null,
    list: [],
    item: log
};

const Logs = (state = initialState, {type, payload = null}) => {
    switch (type) {
        case ActionTypes.LOGS_LIST:
            return applyLogs(state, payload);
        default:
            return state;
    }
};

const applyLogs = (state, payload) => {
    state = Object.assign({}, state, {
        from: payload.from,
        to: payload.to,
        perPage: payload.per_page,
        currentPage: payload.current_page,
        lastPage: payload.last_page,
        total: payload.total,
        list: payload.data
    });

    return state;
};

export default Logs;
