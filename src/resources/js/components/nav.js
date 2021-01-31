import React from 'react';
import {Link} from 'react-router-dom';
import {connect} from 'react-redux';

const opened = {display: 'none'};
const closed = {display: 'block'};

class Navigation extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            authUser: null,
            dropdownMenu: false
        };

        this.handleDropdown = this.handleDropdown.bind(this);
    }

    componentDidUpdate(prevProps) {
        if (prevProps.authUser !== this.props.authUser) {
            this.setState({authUser: this.props.authUser})
        }
    }


    handleDropdown(event) {
        event.preventDefault();

        this.setState({dropdownMenu: !this.state.dropdownMenu})
    }

    render() {
        return (
            <nav className="sb-topnav navbar navbar-expand navbar-dark bg-dark">
                <Link to="/admin" className="navbar-brand">Dronepilot | Admin</Link>
                <button className="btn btn-link btn-sm order-1 order-lg-0">
                    <i className="fas fa-bars"/>
                </button>

                <form className="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                    <div className="input-group">
                        <input className="form-control" type="text" placeholder="Поиск.."/>
                        <div className="input-group-append">
                            <button className="btn btn-primary" type="button"><i className="fas fa-search"/></button>
                        </div>
                    </div>
                </form>

                <ul className="navbar-nav ml-auto ml-md-0">
                    <li className="nav-item dropdown">
                        <Link className="nav-link dropdown-toggle" to="#" onClick={this.handleDropdown}>
                            <i className="fas fa-user fa-fw"/>
                        </Link>

                        <div className="dropdown-menu dropdown-menu-right" style={this.state.dropdownMenu ? closed : opened }>
                            <Link className="dropdown-item" to="/admin/settings">Настройки</Link>
                            <Link className="dropdown-item" to="/admin/logs">Логи</Link>
                            <div className="dropdown-divider"/>
                            <Link className="dropdown-item" to="/admin/logout">Выход</Link>
                        </div>
                    </li>
                </ul>
            </nav>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        authUser: state.Auth.user
    }
};

export default connect(mapStateToProps)(Navigation);
