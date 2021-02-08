import * as ActionTypes from '../action-types';
import Http from '../../http'
import {deleteCookie, getCookie, setCookie} from '../../utils/cookie';

const user = {
    id: null,
    name: null,
    email: null,
};

const initialState = {
    isAuthenticated: false,
    isAdmin: false,
    user
};

const Auth = (state = initialState, {type, payload = null}) => {
    switch (type) {
        case ActionTypes.AUTH_LOGIN:
            return authLogin(state, payload);
        case ActionTypes.AUTH_CHECK:
            return checkAuth(state);
        case ActionTypes.AUTH_LOGOUT:
            return logout(state);
        default:
            return state;
    }
};

const authLogin = (state, payload) => {
    const jwtToken = payload.access_token;
    const user = payload.user;
    const isAdmin = payload.is_admin;

    setCookie('is_admin', isAdmin);
    setCookie('jwt_token', jwtToken)

    Http.defaults.headers.common['Authorization'] = `Bearer ${jwtToken}`;

    state = Object.assign({}, state, {
        isAuthenticated: true,
        isAdmin: getCookie('is_admin') === 'true',
        user
    });

    return state;
};

const checkAuth = (state) => {
    state = Object.assign({}, state, {
        isAuthenticated: getCookie('jwt_token'),
        isAdmin: getCookie('is_admin')
    });

    if (state.isAuthenticated) {
        Http.defaults.headers.common['Authorization'] = `Bearer ${getCookie('jwt_token')}`;
    }

    return state;
};

const logout = (state) => {
    deleteCookie('jwt_token');

    state = Object.assign({}, state, {
        isAuthenticated: false,
        isAdmin: false,
        user
    });

    return state;
};

export default Auth;
