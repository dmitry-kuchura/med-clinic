import React from 'react';
import {connect} from 'react-redux';
import Pagination from '../../helpers/pagination';
import {Link} from 'react-router-dom';
import {getPatientsList} from '../../services/patients-service';
import {formatDate} from '../../utils/date-format';

class PatientsList extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            from: null,
            to: null,
            perPage: null,
            currentPage: 1,
            lastPage: null,
            total: null,
            list: []
        };

        props.dispatch(getPatientsList(this.state.currentPage));

        this.handleChangePage = this.handleChangePage.bind(this);
    }

    componentDidUpdate(prevProps) {
        if (prevProps.patients !== this.props.patients) {
            this.setState({
                from: this.props.patients.from,
                to: this.props.patients.to,
                perPage: this.props.patients.perPage,
                currentPage: this.props.patients.currentPage,
                lastPage: this.props.patients.lastPage,
                total: this.props.patients.total,
                list: this.props.patients.list
            })
        }
    }

    handleChangePage(event) {
        event.preventDefault();
        this.props.dispatch(getPatientsList(parseInt(event.target.id)));
    }

    render() {
        return (
            <main>
                <div className="container-fluid">
                    <h1 className="mt-4">Список пацієнтиів</h1>
                    <div className="card mb-4">
                        <div className="card-body">
                            <div className="table-responsive">
                                <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ім'я</th>
                                        <th>Стать</th>
                                        <th>Контактні дані</th>
                                        <th>Дата редагування</th>
                                        <th>Дії</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Ім'я</th>
                                        <th>Стать</th>
                                        <th>Контактні дані</th>
                                        <th>Дата редагування</th>
                                        <th>Дії</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>

                                    <List state={this.state.list}/>

                                    </tbody>
                                </table>
                            </div>

                            <Pagination state={this.state} handleChangePage={this.handleChangePage}/>
                        </div>
                    </div>
                </div>
            </main>
        );
    }
}

const List = (props) => {
    let list = props.state;
    let html;

    if (list.length > 0) {
        html = list.map(function (item) {
            return (
                <tr key={item.id}>
                    <td><strong>{item.id}</strong></td>
                    <td><strong>{item.first_name.length ? item.first_name + ' ' + item.last_name : 'N/A'}</strong></td>
                    <td><p>{item.gender === 'male' ? 'Чол.' : 'Жін.'}</p></td>
                    <td>
                        <span>{item.address ? 'Адреса: ' + item.address : ''}</span>
                        <span>{item.phone ? 'Телефон: ' + item.phone : ''}</span>
                    </td>
                    <td><p>{formatDate(item.updated_at)}</p></td>
                    <td>
                        <Link to={'/patients/' + item.id} className="btn btn-success btn-sm">
                            <i className="fas fa-edit"/>
                        </Link>

                        <span> </span>

                        <Link to={'/patients/delete/' + item.id} className="btn btn-danger btn-sm">
                            <i className="fas fa-trash"/>
                        </Link>
                    </td>
                </tr>
            )
        });

        return html;
    }

    return (
        <tr>
            <td colSpan="6">Нечего отображать!</td>
        </tr>
    )
};

const mapStateToProps = (state) => {
    return {
        authUser: state.Auth.user,
        patients: state.Patients
    }
};

export default connect(mapStateToProps)(PatientsList);
