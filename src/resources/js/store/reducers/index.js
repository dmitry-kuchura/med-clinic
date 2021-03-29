import {combineReducers} from 'redux'
import Auth from './auth-reducer'
import Patients from './patients-reducer'
import Doctors from './doctors-reducer';
import persistStore from './persist-store'
import Tests from './tests-reducer';
import PatientsTests from './patients-tests-reducer';
import PatientsMessages from './patients-messages-reducer';
import PatientAppointments from './patients-appointments-reducer';

const RootReducer = combineReducers({
    Auth,
    Patients,
    Doctors,
    PatientsTests,
    PatientAppointments,
    PatientsMessages,
    Tests,
    persistStore
});

export default RootReducer;
