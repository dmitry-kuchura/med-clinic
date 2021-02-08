import {combineReducers} from 'redux'
import Auth from './auth-reducer'
import Patients from './patients-reducer'
import persistStore from './persist-store'

const RootReducer = combineReducers({
    Auth,
    Patients,
    persistStore
});

export default RootReducer;
