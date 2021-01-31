import {combineReducers} from 'redux'
import Auth from './auth-reducer'
import Records from './records-reducer'
import Categories from './categories-reducer'
import persistStore from './persist-store'

const RootReducer = combineReducers({
    Auth,
    Records,
    Categories,
    persistStore
});

export default RootReducer;
