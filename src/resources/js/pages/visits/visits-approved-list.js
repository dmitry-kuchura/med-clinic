import React from 'react';
import {connect} from 'react-redux';
import {
    approveTemplate,
    deleteTemplate,
    getApprovedPatientsVisitsTemplateList
} from '../../services/patients-visits-service';
import swal from 'sweetalert';

class VisitsApprovedList extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            list: [],
            approved: []
        };

        props.dispatch(getApprovedPatientsVisitsTemplateList());

        this.handleApprove = this.handleApprove.bind(this);
        this.handleRemove = this.handleRemove.bind(this);
    }

    componentDidUpdate(prevProps) {
        if (prevProps.visitsTemplates !== this.props.visitsTemplates) {
            this.setState({
                list: this.props.visitsTemplates.list,
                approved: this.props.visitsTemplates.approved,
            })
        }
    }

    handleApprove(event) {
        let self = this;
        let template = event.target.id;

        this.props.dispatch(approveTemplate(template))
            .then(success => {
                self.props.dispatch(getApprovedPatientsVisitsTemplateList());
                swal('Добре!', 'Шаблон було додано!', 'success');
            })
            .catch(error => {
                console.log(error)
            })
    }

    handleRemove(event) {
        let self = this;
        let template = event.target.id;

        this.props.dispatch(deleteTemplate(template))
            .then(success => {
                self.props.dispatch(getApprovedPatientsVisitsTemplateList());
                swal('Добре!', 'Шаблон було видалено!', 'success');
            })
            .catch(error => {
                console.log(error)
            })
    }

    render() {
        return (
            <main>
                <div className="container-fluid">
                    <h1 className="mt-4">Список аналізів з СМС нагадуванням</h1>
                    <div className="card mb-4">
                        <div className="card-body">
                            <div className="table-responsive">
                                <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Назва</th>
                                        <th>Дозволено / Не доволено</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Назва</th>
                                        <th>Дозволено / Не доволено</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>

                                    <List list={this.state.list} approved={this.state.approved}
                                          handleApprove={this.handleApprove}
                                          handleRemove={this.handleRemove}/>

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

const List = (props) => {
    let list = props.list;
    let approved = props.approved;
    let html;

    if (list.length > 0) {
        html = list.map(function (item, key) {
            return (
                <tr key={key}>
                    <td><strong>{key}</strong></td>
                    <td>
                        <strong>{item}</strong>
                    </td>
                    <td>

                        {approved.includes(item) ?
                            <span className="btn btn-success btn-sm" id={item} onClick={props.handleRemove}>
                                Дозволено
                            </span>
                            :
                            <span className="btn btn-danger btn-sm" id={item} onClick={props.handleApprove}>
                                Не доволено
                            </span>
                        }

                    </td>
                </tr>
            )
        });

        return html;
    }

    return (
        <tr>
            <td colSpan="3">Нічого відображати!</td>
        </tr>
    )
};

const mapStateToProps = (state) => {
    return {
        authUser: state.Auth.user,
        visitsTemplates: state.PatientVisitsTemplates
    }
};

export default connect(mapStateToProps)(VisitsApprovedList);
