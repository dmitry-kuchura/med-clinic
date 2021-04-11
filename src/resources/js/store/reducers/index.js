import {combineReducers} from 'redux'
import Auth from './auth-reducer'
import Patients from './patients-reducer'
import Doctors from './doctors-reducer';
import persistStore from './persist-store'
import Tests from './tests-reducer';
import Logs from './logs-reducer';
import PatientsTests from './patients-tests-reducer';
import PatientsMessages from './patients-messages-reducer';
import PatientAppointments from './patients-appointments-reducer';
import MessagesTemplates from './messages-templates-reducer';

const RootReducer = combineReducers({
    Auth,
    Patients,
    Doctors,
    PatientsTests,
    PatientAppointments,
    PatientsMessages,
    Tests,
    Logs,
    MessagesTemplates,
    persistStore
});

export default RootReducer;
