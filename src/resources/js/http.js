import axios from 'axios'
import store from './store'
import * as actions from './store/actions/auth-action'
import {getCookie} from './utils/cookie';

const token = document.head.querySelector('meta[name="csrf-token"]');
const jwtToken = getCookie('jwt_token');

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

if (jwtToken) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${jwtToken}`;
}

axios.interceptors.response.use(
    response => response,
    (error) => {
        if (error.response.status === 401) {
            store.dispatch(actions.authLogout())
        }
        return Promise.reject(error);
    }
);
export default axios;
