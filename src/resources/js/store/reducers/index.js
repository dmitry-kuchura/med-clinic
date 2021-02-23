import {combineReducers} from 'redux'
import Auth from './auth-reducer'
import Patients from './patients-reducer'
import persistStore from './persist-store'
import Tests from './tests-reducer';

const RootReducer = combineReducers({
    Auth,
    Patients,
    Tests,
    persistStore
});

export default RootReducer;
