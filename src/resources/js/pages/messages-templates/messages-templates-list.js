import React from 'react';
import {connect} from 'react-redux';
import Pagination from '../../helpers/pagination';
import {Link} from 'react-router-dom';
import {formatDate} from '../../utils/date-format';
import {getMessagesTemplatesList} from '../../services/messages-templates-service';

class MessagesTemplatesList extends React.Component {
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

        props.dispatch(getMessagesTemplatesList(this.state.currentPage));

        this.handleChangePage = this.handleChangePage.bind(this);
    }

    componentDidUpdate(prevProps) {
        if (prevProps.messagesTemplates !== this.props.messagesTemplates) {
            this.setState({
                from: this.props.messagesTemplates.from,
                to: this.props.messagesTemplates.to,
                perPage: this.props.messagesTemplates.perPage,
                currentPage: this.props.messagesTemplates.currentPage,
                lastPage: this.props.messagesTemplates.lastPage,
                total: this.props.messagesTemplates.total,
                list: this.props.messagesTemplates.list
            })
        }
    }

    handleChangePage(event) {
        event.preventDefault();
        this.props.dispatch(getMessagesTemplatesList(parseInt(event.target.id)));
    }

    render() {
        return (
            <main>
                <div className="container-fluid">
                    <h1 className="mt-4">Список шаблонів</h1>
                    <div className="card mb-4">
                        <div className="card-body">
                            <div className="table-responsive">
                                <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Назва</th>
                                        <th>Дата редагування</th>
                                        <th>Дії</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Назва</th>
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
                    <td><strong>{item.name}</strong></td>
                    <td><p>{formatDate(item.updated_at)}</p></td>
                    <td>
                        <Link to={'/doctors/' + item.id} className="btn btn-success btn-sm">
                            <i className="fas fa-edit"/>
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
            <td colSpan="4">Нечего отображать!</td>
        </tr>
    )
};

const mapStateToProps = (state) => {
    return {
        authUser: state.Auth.user,
        messagesTemplates: state.MessagesTemplates
    }
};

export default connect(mapStateToProps)(MessagesTemplatesList);
