import React from 'react';
import {connect} from 'react-redux';
import Modal from '../../utils/modal';
import PatientTestsList from './common/patient-tests-list';
import {getParamFromUrl} from '../../helpers/url-params';
import {validate} from '../../helpers/validation';
import {getAllTestsList} from '../../services/tests-service';
import {createPatient, getPatientById, updatePatient} from '../../services/patients-service';
import {addPatientTest, getPatientsTests} from '../../services/patients-tests-service';
import swal from 'sweetalert';

const rules = {
    'first_name': ['required'],
    'last_name': ['required'],
    'middle_name': ['string', 'nullable'],
    'email': ['email', 'nullable'],
    'phone': ['required'],
};

class PatientsEdit extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            patient: {
                id: null,
                first_name: null,
                last_name: null,
                email: null,
                middle_name: null,
                gender: 'male',
            },
            test: {
                test_id: null,
                file: null,
                result: null,
                reference_values: null,
            },
            showSendEmailModal: false,
            showSendSmsModal: false,
            showAddTestModal: false,
            patientTests: []
        };

        if (getParamFromUrl(props, 'id')) {
            props.dispatch(getPatientById(getParamFromUrl(props, 'id')))
                .then(success => {
                    props.dispatch(getPatientsTests(getParamFromUrl(props, 'id')));
                })

            props.dispatch(getAllTestsList());
        }

        this.handleChangeInput = this.handleChangeInput.bind(this);
        this.handleSubmitForm = this.handleSubmitForm.bind(this);
        this.handleShowModal = this.handleShowModal.bind(this);
        this.handleHide = this.handleHide.bind(this);
        this.handleSubmitPatientTest = this.handleSubmitPatientTest.bind(this);
        this.clearTestState = this.clearTestState.bind(this);
    }

    componentDidUpdate(prevProps) {
        if (prevProps !== this.props) {
            this.setState({
                patient: this.props.patient,
                tests: this.props.tests.list,
                patientTests: this.props.patientTests
            })
        }
    }

    handleChangeInput(event) {
        event.preventDefault();

        let type = event.target.type;
        let input = event.target.name;
        let value = event.target.value;
        let state = Object.assign({}, this.state);

        let check = input.split('.');

        if (type === 'file') {
            value = event.target.files[0]
        }

        if (check.length === 1) {
            state.patient[input] = value;
        } else {
            state[check[0]][check[1]] = value;
        }

        this.setState(state);
    }

    handleSubmitForm(event) {
        event.preventDefault();

        const self = this;

        if (!this.valid()) {
            swal('Неправильно введені данні', 'Перевірте вказанні данні!', 'error');
            return;
        }

        if (this.state.patient.id) {
            this.props.dispatch(updatePatient(this.state.patient))
                .then(success => {
                    swal('Добре!', 'Профіль було оновлено!', 'success');
                    self.props.history.push('/patients');
                })
                .catch(error => {
                    console.log(error);
                    swal('Погано!', 'Щось пішло не за планом!', 'error');
                })
        } else {
            this.props.dispatch(createPatient(this.state.patient))
                .then(success => {
                    swal('Добре!', 'Профіль було створено!', 'success');
                    self.props.history.push('/patients');
                })
                .catch(error => {
                    console.log(error);
                    swal('Погано!', 'Щось пішло не за планом!', 'error');
                })
        }
    }

    handleSubmitPatientTest(event) {
        event.preventDefault();

        let data = {
            patient_id: this.state.patient.id,
            test_id: this.state.test.test_id,
            file: this.state.test.file,
            result: this.state.test.result,
            reference_values: this.state.test.reference_values,
        }

        this.props.dispatch(addPatientTest(data))
            .then(success => {
                this.handleHide();
                this.clearTestState();

                swal('Добре!', 'Аналіз було додано! Лист надіслано.', 'success');

                this.props.dispatch(getPatientsTests(this.state.patient.id))
            })
            .catch(error => {
                swal('Погано!', 'Щось пішло не за планом!', 'error');
            })
    }

    valid() {
        let patient = this.state.patient;

        for (const [key, value] of Object.entries(patient)) {
            if (rules.hasOwnProperty(key)) {
                let valid = validate(key, value, rules[key]);

                return valid === undefined || valid === null;
            }
        }

        return true;
    }

    clearTestState() {
        this.setState({
            test: {
                test_id: null,
                file: null,
                result: null,
                reference_values: null,
            }
        });
    }

    handleHide() {
        this.setState({
            showSendEmailModal: false,
            showSendSmsModal: false,
            showAddTestModal: false,
        });
    }

    handleShowModal(event) {
        let param = event.target.getAttribute('data-modal');

        this.handleHide();

        switch (param) {
            case 'email':
                this.setState({
                    showSendEmailModal: true
                });
                break;
            case 'sms':
                this.setState({
                    showSendSmsModal: true
                });
                break;
            case 'test':
                this.setState({
                    showAddTestModal: true
                });
                break;
        }
    }

    render() {
        let tests = this.state.tests;
        let patient = this.state.patient;
        let placeholder = patient.gender === 'male' ? '/images/avatars/man.png' : '/images/avatars/woman.png';

        return (
            <main>
                <div className="container-fluid">
                    <h1 className="mt-4">
                        {patient.id ? 'Редагування пацієнта' : 'Додавання нового пацієнта'}
                    </h1>

                    <form>
                        <div className="row gutters-sm">
                            <div className="col-md-4 mb-3">
                                <div className="card">
                                    <div className="card-body">
                                        <div className="d-flex flex-column align-items-center text-center">
                                            <img src={placeholder}
                                                 alt="Avatar" className="rounded-circle" width="150"/>
                                            <div className="mt-3">
                                                <h4>{patient.first_name} {patient.last_name} {patient.middle_name}</h4>
                                                <p className="text-secondary mb-1">{patient.phone}</p>
                                                <p className="text-muted font-size-sm">{patient.email}</p>

                                                {patient.id ?
                                                    <button type="button" className="btn btn-outline-primary m-1"
                                                            data-modal="sms"
                                                            onClick={this.handleShowModal}>Надіслати
                                                        СМС</button>
                                                    : null}

                                                {patient.id ?
                                                    <button type="button" className="btn btn-outline-secondary m-1"
                                                            data-modal="email"
                                                            onClick={this.handleShowModal}>Надіслати
                                                        Email</button>
                                                    : null}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="card mt-3">
                                    <div className="card-body">
                                        <div className="row">
                                            <div className="col-md-12">
                                                <div className="form-group">
                                                    <div className="form-check form-check-inline">
                                                        <input className="form-check-input"
                                                               type="radio"
                                                               value="male"
                                                               name="gender"
                                                               onChange={this.handleChangeInput}
                                                               checked={patient.gender === 'male'}
                                                               id="genderMale"/>
                                                        <label className="form-check-label">Чоловік</label>

                                                        <div className="form-check form-check-inline"/>

                                                        <input className="form-check-input"
                                                               type="radio"
                                                               value="female"
                                                               name="gender"
                                                               onChange={this.handleChangeInput}
                                                               checked={patient.gender === 'female'}
                                                               id="genderFemale"/>
                                                        <label className="form-check-label">Жінка</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="col-md-8">
                                <div className="card mb-3">
                                    <div className="card-body">
                                        <div className="row">
                                            <div className="col-md-12">
                                                <div className="form-group">
                                                    <label htmlFor="formGroupExampleInput">Ім'я</label>
                                                    <input type="text"
                                                           className={validate("first_name", patient.first_name, rules['first_name']) ? "form-control is-invalid" : "form-control"}
                                                           placeholder="Ім'я"
                                                           name="first_name"
                                                           id="first_name"
                                                           onChange={this.handleChangeInput}
                                                           value={patient.first_name ? patient.first_name : ''}/>
                                                    <div
                                                        className="invalid-feedback">{validate('first_name', patient.first_name, rules['first_name'])}</div>
                                                </div>

                                                <div className="form-group">
                                                    <label htmlFor="formGroupExampleInput">Прізвище</label>
                                                    <input type="text"
                                                           className={validate("last_name", patient.last_name, rules['last_name']) ? "form-control is-invalid" : "form-control"}
                                                           placeholder="Прізвище"
                                                           name="last_name"
                                                           id="last_name"
                                                           onChange={this.handleChangeInput}
                                                           value={patient.last_name ? patient.last_name : ''}/>
                                                    <div
                                                        className="invalid-feedback">{validate("last_name", patient.last_name, rules["last_name"])}</div>
                                                </div>

                                                <div className="form-group">
                                                    <label htmlFor="formGroupExampleInput">По батькові</label>
                                                    <input type="text"
                                                           className={validate("middle_name", patient.middle_name, rules['middle_name']) ? "form-control is-invalid" : "form-control"}
                                                           placeholder="По батькові"
                                                           name="middle_name"
                                                           id="middle_name"
                                                           onChange={this.handleChangeInput}
                                                           value={patient.middle_name ? patient.middle_name : ''}/>
                                                    <div
                                                        className="invalid-feedback">{validate("middle_name", patient.middle_name, rules["middle_name"])}</div>
                                                </div>

                                                <hr/>

                                                <div className="form-group">
                                                    <label htmlFor="formGroupExampleInput">Телефон</label>
                                                    <input type="text"
                                                           className={validate("phone", patient.phone, rules['phone']) ? "form-control is-invalid" : "form-control"}
                                                           placeholder="Телефон. Наприклад: +380631234567 або 0631234567"
                                                           name="phone"
                                                           id="phone"
                                                           onChange={this.handleChangeInput}
                                                           value={patient.phone ? patient.phone : ''}/>
                                                    <div
                                                        className="invalid-feedback">{validate("middle_name", patient.middle_name, rules["middle_name"])}</div>
                                                </div>

                                                <div className="form-group">
                                                    <label htmlFor="formGroupExampleInput">Email</label>
                                                    <input type="text"
                                                           className={validate("email", patient.email, rules['email']) ? "form-control is-invalid" : "form-control"}
                                                           placeholder="Email"
                                                           name="email"
                                                           id="email"
                                                           onChange={this.handleChangeInput}
                                                           value={patient.email ? patient.email : ''}/>
                                                    <div
                                                        className="invalid-feedback">{validate("email", patient.email, rules["email"])}</div>
                                                </div>

                                                <div className="pull-right">
                                                    <button className="btn btn-primary" type="submit"
                                                            onClick={this.handleSubmitForm}>{patient.id ? 'Оновити' : 'Додати'}
                                                    </button>
                                                    <div className="form-check form-check-inline"/>
                                                    <button className="btn btn-secondary">Назад</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="row gutters-sm">
                            <div className="col-md-12">
                                <div className="card mb-3">
                                    <div className="card-body">
                                        <div className="row">
                                            <div className="col-md-12">
                                                <div className="row float-right m-2">
                                                    <button type="button" className="btn btn-primary"
                                                            data-modal="test"
                                                            onClick={this.handleShowModal}>
                                                        Додати результат
                                                    </button>
                                                </div>

                                                <PatientTestsList data={this.state.patientTests}/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <Modal show={this.state.showSendSmsModal} handleHide={this.handleHide} title="Віправка SMS">
                    <form>
                        <div className="form-group">
                            <label htmlFor="phone">Телефон</label>
                            <input type="text" className="form-control" id="phone" placeholder="+380931106215"/>
                        </div>
                        <div className="form-group">
                            <label htmlFor="message">Текст повідомлення</label>
                            <textarea className="form-control" id="message" rows="3"></textarea>
                        </div>
                    </form>
                </Modal>

                <Modal show={this.state.showSendEmailModal} handleHide={this.handleHide} title="Відправка Email">
                    <form>
                        <div className="form-group">
                            <label htmlFor="email">Email адреса</label>
                            <input type="email" className="form-control" id="email" placeholder="name@example.com"/>
                        </div>
                        <div className="form-group">
                            <label htmlFor="message">Текст повідомлення</label>
                            <textarea className="form-control" id="message" rows="3"></textarea>
                        </div>
                    </form>
                </Modal>

                <Modal show={this.state.showAddTestModal} handleHide={this.handleHide} title="Додаваня аналізу">
                    <form>
                        <div className="form-group">
                            <label htmlFor="test.test_id">Аналіз</label>
                            <select name="test.test_id" className="form-control" id="test.test_id"
                                    onChange={this.handleChangeInput} defaultValue={this.state.test.test_id}>
                                <option>Не обрано</option>
                                {tests && tests.length && tests.map(test => {
                                        return <option key={test.id}
                                                       value={test.id}>{test.name}</option>
                                    }
                                )};
                            </select>
                        </div>

                        <div className="form-group">
                            <label htmlFor="message">Результат</label>
                            <textarea className="form-control" id="test.result" name="test.result" rows="2"
                                      onChange={this.handleChangeInput}
                                      value={this.state.test.result ? this.state.test.result : ''}></textarea>
                        </div>

                        <div className="form-group">
                            <label htmlFor="message">Референтні значення</label>
                            <textarea className="form-control" id="test.reference_values" name="test.reference_values"
                                      rows="2" onChange={this.handleChangeInput}
                                      value={this.state.test.reference_values ? this.state.test.reference_values : ''}></textarea>
                        </div>

                        <div className="form-group">
                            <label htmlFor="test.file">Файл</label>
                            <input type="file" name="test.file" className="form-control" id="test.file"
                                   onChange={this.handleChangeInput}/>
                        </div>

                        <div className="form-group">
                            <div className="float-right">
                                <button type="button" className="btn btn-success"
                                        onClick={this.handleSubmitPatientTest}>
                                    Додати
                                </button>
                            </div>
                        </div>
                    </form>
                </Modal>
            </main>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        patient: state.Patients.item,
        tests: state.Tests,
        patientTests: state.PatientsTests.list,
    }
};

export default connect(mapStateToProps)(PatientsEdit);
