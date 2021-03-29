import React from 'react';
import {connect} from 'react-redux';
import Pagination from '../../helpers/pagination';
import {Link} from 'react-router-dom';
import {formatDate} from '../../utils/date-format';
import {getDoctorsList} from '../../services/doctors-service';

class DoctorsList extends React.Component {
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

        props.dispatch(getDoctorsList(this.state.currentPage));

        this.handleChangePage = this.handleChangePage.bind(this);
    }

    componentDidUpdate(prevProps) {
        if (prevProps.doctors !== this.props.doctors) {
            this.setState({
                from: this.props.doctors.from,
                to: this.props.doctors.to,
                perPage: this.props.doctors.perPage,
                currentPage: this.props.doctors.currentPage,
                lastPage: this.props.doctors.lastPage,
                total: this.props.doctors.total,
                list: this.props.doctors.list
            })
        }
    }

    handleChangePage(event) {
        event.preventDefault();
        this.props.dispatch(getDoctorsList(parseInt(event.target.id)));
    }

    render() {
        return (
            <main>
                <div className="container-fluid">
                    <h1 className="mt-4">Список лікарів</h1>
                    <div className="card mb-4">
                        <div className="card-body">
                            <div className="table-responsive">
                                <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ім'я</th>
                                        <th>Контактні дані</th>
                                        <th>Дата редагування</th>
                                        <th>Дії</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Ім'я</th>
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
                    <td>
                        <span>{item.address ? 'Адреса: ' + item.address : ''}</span>
                        <span>{item.phone ? 'Телефон: ' + item.phone : ''}</span>
                    </td>
                    <td><p>{formatDate(item.updated_at)}</p></td>
                    <td>
                        <Link to={'/doctors/' + item.id} className="btn btn-success btn-sm">
                            <i className="fas fa-edit"/>
                        </Link>

                        <span> </span>

                        <Link to={'/doctors/view/' + item.id} className="btn btn-warning btn-sm">
                            <i className="fas fa-eye"/>
                        </Link>

                        <span> </span>

                        <Link to={'/doctors/delete/' + item.id} className="btn btn-danger btn-sm">
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
            <td colSpan="5">Нечего отображать!</td>
        </tr>
    )
};

const mapStateToProps = (state) => {
    return {
        authUser: state.Auth.user,
        doctors: state.Doctors
    }
};

export default connect(mapStateToProps)(DoctorsList);
