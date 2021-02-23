import React from 'react';
import {connect} from 'react-redux';
import Pagination from '../../helpers/pagination';
import {Link} from 'react-router-dom';
import {getTestsList} from '../../services/tests-service';
import {formatDate} from '../../utils/date-format';

class TestsList extends React.Component {
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

        props.dispatch(getTestsList(this.state.currentPage));

        this.handleChangePage = this.handleChangePage.bind(this);
    }

    componentDidUpdate(prevProps) {
        if (prevProps.tests !== this.props.tests) {
            this.setState({
                from: this.props.tests.from,
                to: this.props.tests.to,
                perPage: this.props.tests.perPage,
                currentPage: this.props.tests.currentPage,
                lastPage: this.props.tests.lastPage,
                total: this.props.tests.total,
                list: this.props.tests.list
            })
        }
    }

    handleChangePage(event) {
        event.preventDefault();
        this.props.dispatch(getTestsList(parseInt(event.target.id)));
    }

    render() {
        return (
            <main>
                <div className="container-fluid">
                    <h1 className="mt-4">Список аналізів</h1>
                    <div className="card mb-4">
                        <div className="card-body">
                            <div className="table-responsive">
                                <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Назва</th>
                                        <th>Ціна</th>
                                        <th>Дата редагування</th>
                                        <th>Дії</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Назва</th>
                                        <th>Ціна</th>
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
                    <td>{item.name.length ? item.name + ' ' + item.name : 'N/A'}</td>
                    <td><p>{item.cost + ' грн.'}</p></td>
                    <td><p>{formatDate(item.updated_at)}</p></td>
                    <td>
                        <Link to={'/patients/' + item.id} className="btn btn-success btn-sm">
                            <i className="fas fa-edit"/>
                        </Link>

                        <span> </span>

                        <Link to={'/patients/view/' + item.id} className="btn btn-warning btn-sm">
                            <i className="fas fa-eye"/>
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
            <td colSpan="5">Нечего отображать!</td>
        </tr>
    )
};

const mapStateToProps = (state) => {
    return {
        authUser: state.Auth.user,
        tests: state.Tests
    }
};

export default connect(mapStateToProps)(TestsList);
