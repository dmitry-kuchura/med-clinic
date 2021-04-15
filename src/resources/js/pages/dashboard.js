import React from 'react';
import {Link} from 'react-router-dom';
import {getPatientsTodayList} from '../services/patients-service';
import {connect} from 'react-redux';
import {formatDate} from '../utils/date-format';

class Dashboard extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            list: []
        };
    }

    componentDidMount() {
        const self = this;

        this.props.dispatch(getPatientsTodayList())
            .then(success => {
                self.setState({
                    list: success
                })
            })
            .catch(error => {
                console.log(error)
            })
    }

    render() {
        return (
            <main>
                <div className="container-fluid">
                    <h1 className="mt-4">Панель администрирования</h1>
                    <div className="row">
                        <div className="col-md-6">
                            <div className="card bg-primary text-white mb-4">
                                <div className="card-body">Список пацієнтів</div>
                                <div className="card-footer d-flex align-items-center justify-content-between">
                                    <Link to={'/patients'} className="small text-white stretched-link">
                                        Перегляд
                                    </Link>
                                    <div className="small text-white"><i className="fas fa-angle-right"/></div>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-6">
                            <div className="card bg-success text-white mb-4">
                                <div className="card-body">Список лікарів</div>
                                <div className="card-footer d-flex align-items-center justify-content-between">
                                    <Link to={'/doctors'} className="small text-white stretched-link">
                                        Перегляд
                                    </Link>
                                    <div className="small text-white"><i className="fas fa-angle-right"/></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="card mb-4">
                        <div className="card-header"><i className="fas fa-table mr-1"></i>Найближчим часом прийдуть на
                            прийом
                        </div>
                        <div className="card-body">
                            <div className="table-responsive">
                                <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                    <thead>
                                    <tr>
                                        <th>Паціент</th>
                                        <th>Час візиту</th>
                                        <th>Причина візиту</th>
                                        <th>Лікар</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Паціент</th>
                                        <th>Час візиту</th>
                                        <th>Причина візиту</th>
                                        <th>Лікар</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>

                                    {this.state.list.map((item, index) => {
                                        if (index === 1) {
                                            console.log(item)
                                        }
                                        return (
                                            <tr key={index}>
                                                <td>{item.patient.last_name + ' ' + item.patient.first_name + ' ' + item.patient.middle_name}</td>
                                                <td>{formatDate(item.appointment_at)}</td>
                                                <td><code>{item.comment}</code></td>
                                                <td>{item.doctor_name}</td>
                                            </tr>
                                        );
                                    })}

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        );
    }
}

const mapStateToProps = (state) => {
    return {}
};

export default connect(mapStateToProps)(Dashboard);
