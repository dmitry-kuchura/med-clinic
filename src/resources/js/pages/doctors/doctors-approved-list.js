import React from 'react';
import {connect} from 'react-redux';
import Pagination from '../../helpers/pagination';
import {Link} from 'react-router-dom';
import {formatDate} from '../../utils/date-format';
import {getDoctorsApprovedList, searchDoctorsList} from '../../services/doctors-service';
import Modal from '../../utils/modal';

const show = 'dropdown show';
const hide = 'dropdown';

class DoctorsApprovedList extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            showAddDoctor: false,
            from: null,
            to: null,
            perPage: null,
            currentPage: 1,
            lastPage: null,
            total: null,
            list: [],
            result: []
        };

        props.dispatch(getDoctorsApprovedList(this.state.currentPage));

        this.handleChangePage = this.handleChangePage.bind(this);
        this.handleChangeInput = this.handleChangeInput.bind(this);
        this.handleHide = this.handleHide.bind(this);
        this.handleShowModal = this.handleShowModal.bind(this);
        this.handleSubmitAddDoctor = this.handleSubmitAddDoctor.bind(this);
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
        this.props.dispatch(getDoctorsApprovedList(parseInt(event.target.id)));
    }

    handleChangeInput() {
        event.preventDefault();
        let self = this;
        let query = event.target.value;

        console.log(query)

        if (query.length) {
            self.props.dispatch(searchDoctorsList(query))
                .then(success => {
                    self.setState({
                        result: success
                    })
                })
                .catch(error => {
                    self.setState({
                        result: [],
                    })
                })
        }
    }

    handleHide() {
        this.setState({
            showAddDoctor: false,
        });
    }

    handleShowModal(event) {
        let param = event.target.getAttribute('data-modal');

        this.handleHide();

        this.setState({
            showAddDoctor: true
        });
    }

    handleSubmitAddDoctor() {
        this.setState({
            showAddDoctor: false,
        });
    }

    render() {
        return (
            <main>
                <div className="container-fluid">
                    <h1 className="mt-4">Список лікарів з СМС нагадуванням</h1>
                    <div className="card mb-4">
                        <div className="card-body">

                            <div>
                                <button type="button" className="btn btn-outline-primary m-1"
                                        onClick={this.handleShowModal}>Додати лікаря
                                </button>
                            </div>

                            <div className="table-responsive">
                                <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ім'я</th>
                                        <th>Дата редагування</th>
                                        <th>Дії</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Ім'я</th>
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

                    <Modal show={this.state.showAddDoctor} handleHide={this.handleHide} title="Додаваня лікаря до списку">
                        <form>
                            <div className="form-group">
                                <label htmlFor="doctor">Лікар</label>
                                <input type="text" name="doctor" className="form-control" id="doctor"
                                       onChange={this.handleChangeInput}/>
                            </div>

                            <div className="list-group">
                                <SearchList result={this.state.result}/>
                            </div>

                            <hr/>

                            <div className="form-group">
                                <div className="float-right">
                                    <button type="button" className="btn btn-success"
                                            onClick={this.handleSubmitAddDoctor}>
                                        Додати
                                    </button>
                                </div>
                            </div>
                        </form>
                    </Modal>
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
                    <td>
                        <strong>{item.doctor.first_name.length ? item.doctor.first_name + ' ' + item.doctor.last_name : 'N/A'}</strong>
                    </td>
                    <td><p>{formatDate(item.updated_at)}</p></td>
                    <td>
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

const SearchList = (props) => {
    let list = props.result;
    let html;

    if (list.length > 0) {
        html = list.map(function (item) {
            return (
                <span className="list-group-item d-flex justify-content-between align-items-center" key={item.id}>
                    {item.first_name + ' ' + item.last_name + ' ' + item.middle_name}
                    <span className="badge badge-primary badge-pill" style={{cursor: 'pointer'}}>
                        <i className="fas fa-plus"/>
                    </span>
                </span>
            )
        });

        return html;
    }

    return null;
};

const mapStateToProps = (state) => {
    return {
        authUser: state.Auth.user,
        doctors: state.DoctorsApproved
    }
};

export default connect(mapStateToProps)(DoctorsApprovedList);
