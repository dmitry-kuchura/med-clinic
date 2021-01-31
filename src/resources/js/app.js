import React from 'react';
import ReactDOM from 'react-dom';
import App from './router';
import {Provider} from 'react-redux';
import * as action from './store/actions/auth-action'
import store from './store';

store.dispatch(action.authCheck());

if (document.getElementById('app')) {
    ReactDOM.render(
        <Provider store={store}>
            <App/>
        </Provider>, document.getElementById('app')
    );
}
