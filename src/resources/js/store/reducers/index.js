import {combineReducers} from 'redux'
import persistStore from './persist-store'
import Auth from './auth-reducer'
import Patients from './patients-reducer'
import Doctors from './doctors-reducer';
import DoctorsApproved from './doctors-approved-reducer';
import Tests from './tests-reducer';
import Logs from './logs-reducer';
import PatientsTests from './patients-tests-reducer';
import PatientsMessages from './patients-messages-reducer';
import PatientAppointments from './patients-appointments-reducer';
import MessagesTemplates from './messages-templates-reducer';
import PatientVisits from './patients-visits-reducer';
import PatientVisitsTemplates from './patients-visits-templates-reducer';

const RootReducer = combineReducers({
    Auth,
    Patients,
    Doctors,
    DoctorsApproved,
    PatientsTests,
    PatientAppointments,
    PatientVisits,
    PatientsMessages,
    Tests,
    Logs,
    MessagesTemplates,
    PatientVisitsTemplates,
    persistStore
});

export default RootReducer;
