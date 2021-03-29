import React from 'react';
import {connect} from 'react-redux';
import swal from 'sweetalert';
import Modal from '../../utils/modal';
import {getParamFromUrl} from '../../helpers/url-params';
import {validate} from '../../helpers/validation';
import {sendPatientMessage} from '../../services/patients-messages-service';
import {getDoctorById, updateDoctor} from '../../services/doctors-service';

const rules = {
    'first_name': ['required'],
    'last_name': ['required'],
    'middle_name': ['string', 'nullable'],
    'email': ['email', 'nullable'],
    'phone': ['required'],
};

class MessagesTemplatesEdit extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            doctor: {
                id: null,
                first_name: null,
                last_name: null,
                email: null,
                phone: null,
                middle_name: null,
                gender: 'male',
            },
            message: {
                phone: '',
                text: '',
            },
            showSendSmsModal: false
        };

        const doctorId = getParamFromUrl(props, 'id');

        if (doctorId) {
            props.dispatch(getDoctorById(doctorId));
        }

        this.handleChangeInput = this.handleChangeInput.bind(this);
        this.handleSubmitForm = this.handleSubmitForm.bind(this);
        this.handleShowModal = this.handleShowModal.bind(this);
        this.handleHide = this.handleHide.bind(this);
        this.handleSubmitSms = this.handleSubmitSms.bind(this);
        this.isTempEmail = this.isTempEmail.bind(this);
    }

    componentDidUpdate(prevProps) {
        if (prevProps.location !== this.props.location) {
            this.props.dispatch(getDoctorById(getParamFromUrl(this.props, 'id')));

        }
        if (prevProps !== this.props) {
            this.setState({
                doctor: this.props.doctor,
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
            state.doctor[input] = value;
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

        this.props.dispatch(updateDoctor(this.state.doctor))
            .then(success => {
                swal('Добре!', 'Профіль було оновлено!', 'success');
            })
            .catch(error => {
                console.log(error);
                swal('Погано!', 'Щось пішло не за планом!', 'error');
            })
    }

    handleSubmitSms(event) {
        event.preventDefault();

        let data = {
            phone: this.state.message.phone,
            text: this.state.message.text,
        }

        this.props.dispatch(sendPatientMessage(this.state.patient.id, data))
            .then(success => {
                this.handleHide();
                this.clearSmsState();
                swal('Добре!', 'СМС повідомлення було надіслано!', 'success');
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

    isTempEmail() {
        let patient = this.state.patient;

        if (patient && patient.email) {
            let email = patient.email.split('@');

            return email[1] === 'temporary.email';
        }

        return false;
    }

    clearSmsState() {
        this.setState({
            message: {
                text: null,
            },
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
            case 'sms':
                this.setState({
                    message: {
                        phone: this.state.patient.phone,
                        text: '',
                    },
                    showSendSmsModal: true
                });
                break;
        }
    }

    render() {
        let doctor = this.state.doctor;
        let placeholder = doctor.gender === 'male' ? '/images/avatars/man.png' : '/images/avatars/woman.png';

        return (
            <main>
                <div className="container-fluid">
                    <h1 className="mt-4">
                        {doctor.id ? 'Редагування доктора' : 'Додавання нового доктора'}
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
                                                <h4>{doctor.first_name} {doctor.last_name} {doctor.middle_name}</h4>
                                                <p className="text-secondary mb-1">{doctor.phone}</p>
                                                <p className="text-muted font-size-sm">{doctor.email}</p>

                                                {doctor.id ?
                                                    <button type="button" className="btn btn-outline-primary m-1"
                                                            data-modal="sms"
                                                            onClick={this.handleShowModal}>Надіслати
                                                        СМС</button>
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
                                                               checked={doctor.gender === 'male'}
                                                               id="genderMale"/>
                                                        <label className="form-check-label">Чоловік</label>

                                                        <div className="form-check form-check-inline"/>

                                                        <input className="form-check-input"
                                                               type="radio"
                                                               value="female"
                                                               name="gender"
                                                               onChange={this.handleChangeInput}
                                                               checked={doctor.gender === 'female'}
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
                                                           className={validate('first_name', doctor.first_name, rules['first_name']) ? 'form-control is-invalid' : 'form-control'}
                                                           placeholder="Ім'я"
                                                           name="first_name"
                                                           id="first_name"
                                                           onChange={this.handleChangeInput}
                                                           value={doctor.first_name ? doctor.first_name : ''}/>
                                                    <div
                                                        className="invalid-feedback">{validate('first_name', doctor.first_name, rules['first_name'])}</div>
                                                </div>

                                                <div className="form-group">
                                                    <label htmlFor="formGroupExampleInput">Прізвище</label>
                                                    <input type="text"
                                                           className={validate('last_name', doctor.last_name, rules['last_name']) ? 'form-control is-invalid' : 'form-control'}
                                                           placeholder="Прізвище"
                                                           name="last_name"
                                                           id="last_name"
                                                           onChange={this.handleChangeInput}
                                                           value={doctor.last_name ? doctor.last_name : ''}/>
                                                    <div
                                                        className="invalid-feedback">{validate('last_name', doctor.last_name, rules['last_name'])}</div>
                                                </div>

                                                <div className="form-group">
                                                    <label htmlFor="formGroupExampleInput">По батькові</label>
                                                    <input type="text"
                                                           className={validate('middle_name', doctor.middle_name, rules['middle_name']) ? 'form-control is-invalid' : 'form-control'}
                                                           placeholder="По батькові"
                                                           name="middle_name"
                                                           id="middle_name"
                                                           onChange={this.handleChangeInput}
                                                           value={doctor.middle_name ? doctor.middle_name : ''}/>
                                                    <div
                                                        className="invalid-feedback">{validate('middle_name', doctor.middle_name, rules['middle_name'])}</div>
                                                </div>

                                                <hr/>

                                                <div className="form-group">
                                                    <label htmlFor="formGroupExampleInput">Телефон</label>
                                                    <input type="text"
                                                           className={validate('phone', doctor.phone, rules['phone']) ? 'form-control is-invalid' : 'form-control'}
                                                           placeholder="Телефон. Наприклад: +380631234567 або 0631234567"
                                                           name="phone"
                                                           id="phone"
                                                           onChange={this.handleChangeInput}
                                                           value={doctor.phone ? doctor.phone : ''}/>
                                                    <div
                                                        className="invalid-feedback">{validate('middle_name', doctor.middle_name, rules['middle_name'])}</div>
                                                </div>

                                                <div className="form-group">
                                                    <label htmlFor="formGroupExampleInput">Email</label>
                                                    <input type="text"
                                                           className={validate('email', doctor.email, rules['email']) ? 'form-control is-invalid' : 'form-control'}
                                                           placeholder="Email"
                                                           name="email"
                                                           id="email"
                                                           onChange={this.handleChangeInput}
                                                           value={doctor.email ? doctor.email : ''}/>
                                                    <div
                                                        className="invalid-feedback">{validate('email', doctor.email, rules['email'])}</div>
                                                </div>

                                                <div className="pull-right">
                                                    <button className="btn btn-primary" type="submit"
                                                            onClick={this.handleSubmitForm}>{doctor.id ? 'Оновити' : 'Додати'}
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
                    </form>
                </div>

                <Modal show={this.state.showSendSmsModal} handleHide={this.handleHide} title="Віправка SMS">
                    <form>
                        <div className="form-group">
                            <label htmlFor="message.phone">Телефон</label>
                            <input type="text" className="form-control"
                                   id="message.phone"
                                   name="message.phone"
                                   defaultValue={this.state.message.phone}
                                   onChange={this.handleChangeInput}
                                   placeholder="+380931106215"/>
                        </div>
                        <div className="form-group">
                            <label htmlFor="message.text">Текст повідомлення</label>
                            <textarea className="form-control"
                                      id="message.text"
                                      name="message.text"
                                      rows="3"
                                      onChange={this.handleChangeInput}></textarea>
                        </div>
                        <div className="form-group">
                            <div className="float-right">
                                <button type="button" className="btn btn-success"
                                        onClick={this.handleSubmitSms}>
                                    Відправити
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
        doctor: state.Doctors.item
    }
};

export default connect(mapStateToProps)(MessagesTemplatesEdit);
