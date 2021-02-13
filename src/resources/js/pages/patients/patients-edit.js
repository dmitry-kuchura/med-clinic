import React from 'react';
import {connect} from 'react-redux';
import {getParamFromUrl} from '../../helpers/url-params';
import {validate} from '../../helpers/validation';
import {getOnePatient} from '../../store/actions/patients-action';
import {updatePatient} from '../../services/patients-service';

const rules = {
    'first_name': ['required'],
    'last_name': ['required'],
    'middle_name': ['string'],
    'email': ['email'],
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
                middle_name: null,
                gender: 'male',
            },
        };

        if (getParamFromUrl(props, 'id')) {
            props.dispatch(getOnePatient(getParamFromUrl(props, 'id')));
        }

        this.handleChangeInput = this.handleChangeInput.bind(this);
        this.handleSubmitForm = this.handleSubmitForm.bind(this);
        this.handleUpdateContent = this.handleUpdateContent.bind(this);
    }

    componentDidUpdate(prevProps) {
        if (prevProps.patient !== this.props.patient) {
            this.setState({
                patient: this.props.patient,
            })
        }
    }

    handleChangeInput(event) {
        event.preventDefault();

        let input = event.target.name;
        let value = event.target.value;
        let state = Object.assign({}, this.state);

        switch (input) {
            default:
                state.patient[input] = value;
                break;
        }

        this.setState(state);
    }

    handleUpdateContent(content) {
        let state = Object.assign({}, this.state);

        state.patient['content'] = content;

        this.setState(state);
    }

    handleSubmitForm(event) {
        event.preventDefault();

        const self = this;

        this.props.dispatch(updatePatient(this.state.patient.id, this.state.patient))
            .then(success => {
                self.props.history.push('/patients');
            });
    }

    render() {
        let patient = this.state.patient;

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
                                            <img src="https://bootdey.com/img/Content/avatar/avatar7.png"
                                                 alt="Admin" className="rounded-circle" width="150"/>
                                                <div className="mt-3">
                                                    <h4>John Doe</h4>
                                                    <p className="text-secondary mb-1">Full Stack Developer</p>
                                                    <p className="text-muted font-size-sm">Bay Area, San Francisco,
                                                        CA</p>
                                                    <button className="btn btn-outline-primary">Message</button>
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
                                                        <label className="form-check-label"
                                                               htmlFor="genderMale">Чоловік</label>

                                                        <div className="form-check form-check-inline"/>

                                                        <input className="form-check-input"
                                                               type="radio"
                                                               value="female"
                                                               name="gender"
                                                               onChange={this.handleChangeInput}
                                                               checked={patient.gender === 'female'}
                                                               id="genderFemale"/>
                                                        <label className="form-check-label" htmlFor="genderFemale">Жінка</label>
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
                                                           value={patient.middle_name ? patient.middle_name : ''}/>
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
                    </form>
                </div>
            </main>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        patient: state.Patients.item,
    }
};

export default connect(mapStateToProps)(PatientsEdit);
