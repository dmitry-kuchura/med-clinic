import React from 'react';
import {connect} from 'react-redux';
import Pagination from '../../helpers/pagination';
import {Link} from 'react-router-dom';
import {getPatientsList} from '../../services/patients-service';
import {formatDate} from "../../utils/date-format";

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
        if (prevProps.records !== this.props.records) {
            this.setState({
                from: this.props.records.from,
                to: this.props.records.to,
                perPage: this.props.records.perPage,
                currentPage: this.props.records.currentPage,
                lastPage: this.props.records.lastPage,
                total: this.props.records.total,
                list: this.props.records.list
            })
        }
    }

    handleChangePage(event) {
        event.preventDefault();
        this.props.dispatch(getRecordsList(parseInt(event.target.id)));
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
                                        <th>Ім'я</th>
                                        <th>Статус</th>
                                        <th>Дата редагування</th>
                                        <th>Відвідувань</th>
                                        <th>Дії</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Ім'я</th>
                                        <th>Статус</th>
                                        <th>Дата редагування</th>
                                        <th>Відвідувань</th>
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
                    <td>{item.translation ? item.translation.name : 'N/A'}</td>
                    <td>{item.views}</td>
                    <td>{item.status}</td>
                    <td>{formatDate(item.created_at)}</td>
                    <td>
                        <Link to={'/admin/patients/' + item.id} className="btn btn-success btn-sm">
                            <i className="fas fa-edit"/>
                        </Link>

                        <span> </span>

                        <Link to={'/admin/patients/view/' + item.id} className="btn btn-warning btn-sm">
                            <i className="fas fa-eye"/>
                        </Link>

                        <span> </span>

                        <Link to={'/admin/patients/delete/' + item.id} className="btn btn-danger btn-sm">
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
        records: state.Records
    }
};

export default connect(mapStateToProps)(PatientsList);
