import {combineReducers} from 'redux'
import Auth from './auth-reducer'
import Patients from './patients-reducer'
import persistStore from './persist-store'
import Tests from './tests-reducer';
import PatientsTests from './patients-tests-reducer';
import PatientsMessages from './patients-messages-reducer';

const RootReducer = combineReducers({
    Auth,
    Patients,
    PatientsTests,
    PatientsMessages,
    Tests,
    persistStore
});

export default RootReducer;
