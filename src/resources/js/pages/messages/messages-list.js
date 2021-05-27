import React from 'react';
import {connect} from 'react-redux';
import {getMessagesList} from '../../services/messages-service';
import Pagination from '../../helpers/pagination';
import {formatDate} from '../../utils/date-format';

class MessagesList extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            from: null,
            to: null,
            perPage: null,
            currentPage: 1,
            lastPage: null,
            total: null,
            list: [],
            filter: {
                phone: null,
                status: null,
            }
        };

        props.dispatch(getMessagesList());

        this.handleChangePage = this.handleChangePage.bind(this);
        this.handleChangeFilter = this.handleChangeFilter.bind(this);
        this.handleChangeGetWithFilter = this.handleChangeGetWithFilter.bind(this);
    }

    componentDidUpdate(prevProps) {
        if (prevProps.messages !== this.props.messages) {
            this.setState({
                from: this.props.messages.from,
                to: this.props.messages.to,
                perPage: this.props.messages.perPage,
                currentPage: this.props.messages.currentPage,
                lastPage: this.props.messages.lastPage,
                total: this.props.messages.total,
                list: this.props.messages.list
            })
        }
    }

    handleChangePage(event) {
        event.preventDefault();
        this.props.dispatch(getMessagesList(parseInt(event.target.id)));
    }

    handleChangeFilter(event) {
        let input = event.target.name;
        let value = event.target.value;
        let state = Object.assign({}, this.state);

        state.filter[input] = value;

        this.setState(state);
    }

    handleChangeGetWithFilter(event) {
        event.preventDefault();

        let phone = this.state.filter.phone ?? null;
        let status = this.state.filter.status ?? null;

        this.props.dispatch(getMessagesList(1, phone, status));
    }

    render() {
        return (
            <main>
                <div className="container-fluid">
                    <h1 className="mt-4">Надіслані СМС повідомлення</h1>
                    <div className="card mb-4">
                        <div className="card-header">
                            <div className="row">
                                <div className="col-md-6"></div>
                                <div className="col-md-6">
                                    <div className="row">
                                        <div className="col-md-4 mr-1">
                                            <input type="email" className="form-control" name="phone"
                                                   placeholder="Телефон, 0931234567" onChange={this.handleChangeFilter}/>
                                        </div>
                                        <div className="col-md-4 ml-1">
                                            <select className="form-control" name="status" onChange={this.handleChangeFilter}>
                                                <option value="">Оберіть статус</option>
                                                <option value="Queued">В черзі</option>
                                                <option value="Accepted">Прийнято</option>
                                                <option value="Sent">Відправлено</option>
                                                <option value="Delivered">Надіслано</option>
                                                <option value="Undelivered">Не надіслано</option>
                                                <option value="Rejected">Отколонено</option>
                                                <option value="Cancelled">Отменено</option>
                                            </select>
                                        </div>
                                        <div className="col-md-3">
                                            <button type="submit" className="btn btn-primary" onClick={this.handleChangeGetWithFilter}>Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="card-body">
                            <div className="table-responsive">
                                <table className="table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Телефон</th>
                                        <th scope="col">Текст</th>
                                        <th scope="col">Статус</th>
                                        <th scope="col">Отправлено</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <List list={this.state.list}/>

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
    let list = props.list;
    let html;

    if (list.length > 0) {
        html = list.map(function (item) {
            return (
                <tr key={item.id}>
                    <td><strong>{item.id}</strong></td>
                    <td><strong>{item.recipient}</strong></td>
                    <td>
                        <span>{item.text}</span>
                    </td>
                    <td>{item.status}</td>
                    <td><p>{formatDate(item.created_at)}</p></td>
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
        messages: state.Messages
    }
};

export default connect(mapStateToProps)(MessagesList);
