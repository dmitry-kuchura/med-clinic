import * as ActionTypes from '../action-types';

const messageTemplate = {
    id: null,
    language: null,
    name: null,
    alias: null,
    content: null,
    created_at: null,
    updated_at: null
};

const initialState = {
    from: null,
    to: null,
    perPage: null,
    currentPage: null,
    lastPage: null,
    total: null,
    list: [],
    item: messageTemplate
};

const MessagesTemplates = (state = initialState, {type, payload = null}) => {
    switch (type) {
        case ActionTypes.MESSAGES_TEMPLATES_LIST:
            return applyMessagesTemplates(state, payload);
        case ActionTypes.MESSAGES_TEMPLATES_INFO:
            return applyMessagesTemplate(state, payload);
        default:
            return state;
    }
};

const applyMessagesTemplates = (state, payload) => {
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

const applyMessagesTemplate = (state, payload) => {
    state = Object.assign({}, state, {
        item: {
            id: payload.id,
            language: payload.language,
            name: payload.name,
            alias: payload.alias,
            content: payload.content,
            created_at: payload.created_at,
            updated_at: payload.updated_at
        }
    });

    return state;
};

export default MessagesTemplates;
