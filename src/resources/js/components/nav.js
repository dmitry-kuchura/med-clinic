import React from 'react';
import {Link} from 'react-router-dom';
import {connect} from 'react-redux';
import {searchPatientsList} from '../services/patients-service';

const show = 'dropdown show';
const hide = 'dropdown';

const opened = {display: 'none'};
const closed = {display: 'block'};

class Navigation extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            authUser: null,
            dropdownMenu: false,
            showResult: false,
            isLoading: false,
            result: []
        };

        this.handleClose = this.handleClose.bind(this);
        this.handleDropdown = this.handleDropdown.bind(this);
        this.filterPatients = this.filterPatients.bind(this);
    }

    componentDidUpdate(prevProps) {
        if (prevProps.authUser !== this.props.authUser) {
            this.setState({authUser: this.props.authUser})
        }
    }

    componentDidMount() {
        this.setState({
            showResult: false,
            isLoading: false,
            result: []
        })
    }

    componentWillUnmount() {
        this.setState({
            showResult: false,
            isLoading: false,
            result: []
        })
    }

    handleDropdown(event) {
        event.preventDefault();

        this.setState({dropdownMenu: !this.state.dropdownMenu})
    }

    handleClose() {
        this.setState({
            showResult: false,
            isLoading: false,
            result: []
        })
    }

    filterPatients(event) {
        event.preventDefault();
        let self = this;
        let query = event.target.value;

        self.setState({
            isLoading: query.length > 0
        })

        if (query.length) {
            self.props.dispatch(searchPatientsList(query))
                .then(success => {
                    self.setState({
                        isLoading: false,
                        result: success
                    })
                })
                .catch(error => {
                    self.setState({
                        showResult: false,
                        result: [],
                        query: ''
                    })
                })
        }
    }

    render() {
        return (
            <>
                <nav className="sb-topnav navbar navbar-expand navbar-dark bg-dark">
                    <Link to="/admin" className="navbar-brand">МедСервіс</Link>
                    <button className="btn btn-link btn-sm order-1 order-lg-0">
                        <i className="fas fa-bars"/>
                    </button>

                    <div className="d-none d-md-inline-block ml-auto mr-0 mr-md-3 my-2 my-md-0 dropdown">
                        <form className="form-inline">
                            <div className="input-group">
                                <input className="form-control"
                                       type="text"
                                       onBlur={this.filterPatients}
                                       placeholder="Пошук.."/>
                                <div className="input-group-append">
                                    {!this.state.isLoading &&
                                    <button className="btn btn-primary" type="button"><i className="fas fa-search"/>
                                    </button>
                                    }

                                    {this.state.isLoading &&
                                    <button className="btn btn-primary" type="button" disabled>
                                            <span className="spinner-border spinner-border-sm" role="status"
                                                  aria-hidden="true"></span>
                                        <span className="sr-only">Loading...</span>
                                    </button>
                                    }
                                </div>
                            </div>
                        </form>
                        <div className={this.state.showResult ? show : hide}
                             style={{position: 'absolute', left: '0px'}}>
                            <div>
                                <List list={this.state.result} handleClose={this.handleClose}/>
                            </div>
                        </div>
                    </div>

                    <ul className="navbar-nav ml-auto ml-md-0">
                        <li className="nav-item dropdown">
                            <Link className="nav-link dropdown-toggle" to="#" onClick={this.handleDropdown}>
                                <i className="fas fa-user fa-fw"/>
                            </Link>

                            <div className="dropdown-menu dropdown-menu-right"
                                 style={this.state.dropdownMenu ? closed : opened}>
                                <Link className="dropdown-item" to="/settings">Налаштування</Link>
                                <Link className="dropdown-item" to="/logs">Логі</Link>
                                <div className="dropdown-divider"/>
                                <Link className="dropdown-item" to="/logout">Вихід</Link>
                            </div>
                        </li>
                    </ul>
                </nav>
            </>
        );
    }
}

const List = (props) => {
    let list = props.list;
    let man = '/images/avatars/man.png';
    let woman = '/images/avatars/woman.png';
    let html;

    if (list.length > 0) {
        html = list.map(function (patient) {
            return (
                <div className="card" style={{width: '17rem'}} key={patient.id}>
                    <div className="card-body">
                        <img src={patient.gender === 'male' ? man : woman} alt="Avatar" className="rounded-circle"
                             width="40"/>

                        <Link to={'/patients/' + patient.id} onClick={props.handleClose}>
                            {patient.first_name + ' ' + patient.last_name + ' ' + patient.middle_name}
                        </Link>

                        <p className="card-text">{patient.phone}</p>
                    </div>
                </div>
            )
        });

        return html;
    }

    return null;
};

const mapStateToProps = (state) => {
    return {
        authUser: state.Auth.user
    }
};

export default connect(mapStateToProps)(Navigation);
