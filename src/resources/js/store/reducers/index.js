import {combineReducers} from 'redux'
import Auth from './auth-reducer'
import Records from './records-reducer'
import persistStore from './persist-store'

const RootReducer = combineReducers({
    Auth,
    Records,
    persistStore
});

export default RootReducer;
