import React from 'react';
import Pagination from '../helpers/pagination';
import {formatDate} from '../utils/date-format';
import {connect} from 'react-redux';
import {getLogsList} from '../services/logs-service';

class Logs extends React.Component {
    constructor(props) {
        super(props);

        this.state = {};

        this.state = {
            from: null,
            to: null,
            perPage: null,
            currentPage: 1,
            lastPage: null,
            total: null,
            list: []
        };

        props.dispatch(getLogsList(this.state.currentPage));

        this.handleChangePage = this.handleChangePage.bind(this);
        this.getAppointmentType = this.getAppointmentType.bind(this);
    }

    componentDidUpdate(prevProps) {
        if (prevProps.logs !== this.props.logs) {
            this.setState({
                from: this.props.logs.from,
                to: this.props.logs.to,
                perPage: this.props.logs.perPage,
                currentPage: this.props.logs.currentPage,
                lastPage: this.props.logs.lastPage,
                total: this.props.logs.total,
                list: this.props.logs.list
            })
        }
    }

    handleChangePage(event) {
        event.preventDefault();
        this.props.dispatch(getLogsList(parseInt(event.target.id)));
    }

    getAppointmentType(type) {
        switch (type) {
            case 'info':
                return 'badge badge-primary';
            case 'alert':
                return 'badge badge-success';
            case 'debug':
                return 'badge badge-info';
            case 'exception':
            case 'error':
                return 'badge badge-warning';
            default:
                return 'badge badge-light';
        }
    }

    render() {
        return (
            <main>
                <div className="container-fluid">
                    <h1 className="mt-4">Логі</h1>
                    <div className="card mb-4">
                        <div className="card-body">
                            <div className="table-responsive">
                                <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                    <thead>
                                    <tr>
                                        <th width={'5%'}>#</th>
                                        <th>Сообщение</th>
                                        <th width={'5%'}>Тип</th>
                                        <th width={'10%'}>Адрес</th>
                                        <th width={'20%'}>User-Agent</th>
                                        <th width={'10%'}>Дата</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th width={'5%'}>#</th>
                                        <th>Сообщение</th>
                                        <th width={'5%'}>Тип</th>
                                        <th width={'10%'}>Адрес</th>
                                        <th width={'20%'}>User-Agent</th>
                                        <th width={'10%'}>Дата</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>

                                    <List state={this.state.list} getType={this.getAppointmentType}/>

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
                    <td>{item.message}</td>
                    <td>
                        <span className={props.getType(item.level)}>{item.level}</span>
                    </td>
                    <td><p>{item.remote_addr}</p></td>
                    <td><p>{item.user_agent}</p></td>
                    <td><p>{formatDate(item.updated_at)}</p></td>
                </tr>
            )
        });

        return html;
    }

    return (
        <tr>
            <td colSpan="5">Нічого відображати!</td>
        </tr>
    )
};

const mapStateToProps = (state) => {
    return {
        authUser: state.Auth.user,
        logs: state.Logs
    }
};

export default connect(mapStateToProps)(Logs);
