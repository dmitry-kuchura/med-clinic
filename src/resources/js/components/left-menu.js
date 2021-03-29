import React from 'react';
import {Link} from 'react-router-dom';
import {connect} from 'react-redux';

const opened = {display: 'none'};
const closed = {display: 'block'};

class LeftMenu extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            authUser: null,
            dropdownTests: false,
            dropdownPatients: false,
            dropdownDoctors: false
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

        switch (event.target.id) {
            case 'patients':
                this.setState({dropdownPatients: !this.state.dropdownPatients});
                break;
            case 'doctors':
                this.setState({dropdownDoctors: !this.state.dropdownDoctors});
                break;
        }
    }

    render() {
        return (
            <div id="layoutSidenav_nav">
                <nav className="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div className="sb-sidenav-menu">
                        <div className="nav">
                            <div className="sb-sidenav-menu-heading">Основне</div>

                            <Link className="nav-link" to="/">
                                <div className="sb-nav-link-icon">
                                    <i className="fas fa-tachometer-alt"/>
                                </div>
                                Головна
                            </Link>

                            <div className="sb-sidenav-menu-heading">Пацієнти</div>

                            <Link to="#" className="nav-link collapsed" id="patients" onClick={this.handleDropdown}>
                                <div className="sb-nav-link-icon">
                                    <i className="fas fa-users"/>
                                </div>
                                Пацієнти
                                <div className="sb-sidenav-collapse-arrow">
                                    {this.state.dropdownPatients ? <i className="fas fa-angle-right"/> :
                                        <i className="fas fa-angle-down"/>}
                                </div>
                            </Link>
                            <div className="collapse" style={this.state.dropdownPatients ? closed : opened}>
                                <nav className="sb-sidenav-menu-nested nav">
                                    <Link className="nav-link" to="/patients">Список пацієнтів</Link>
                                    <Link className="nav-link" to="/patients/create">Додати пацієнта</Link>
                                    <Link className="nav-link" to="/patients/tests">Аналізи пацієнта</Link>
                                </nav>
                            </div>

                            <div className="sb-sidenav-menu-heading">Лікарі</div>

                            <Link to="#" className="nav-link collapsed" id="doctors" onClick={this.handleDropdown}>
                                <div className="sb-nav-link-icon">
                                    <i className="fas fa-hospital"/>
                                </div>
                                Лікарі
                                <div className="sb-sidenav-collapse-arrow">
                                    {this.state.dropdownDoctors ? <i className="fas fa-angle-right"/> :
                                        <i className="fas fa-angle-down"/>}
                                </div>
                            </Link>
                            <div className="collapse" style={this.state.dropdownDoctors ? closed : opened}>
                                <nav className="sb-sidenav-menu-nested nav">
                                    <Link className="nav-link" to="/doctors">Список лікарів</Link>
                                    <Link className="nav-link" to="/doctors/exclude">Виключити зі сповіщень</Link>
                                </nav>
                            </div>

                            <div className="sb-sidenav-menu-heading">Додатково</div>

                            <Link to="/messages-templates" className="nav-link">
                                <div className="sb-nav-link-icon">
                                    <i className="fas fa-envelope-open"></i>
                                </div>
                                Шаблони
                            </Link>
                            <Link to="/settings" className="nav-link">
                                <div className="sb-nav-link-icon">
                                    <i className="fas fa-cogs"/>
                                </div>
                                Налаштування
                            </Link>
                        </div>
                    </div>

                    {this.state.authUser ? <div className="sb-sidenav-footer">
                        <div className="small">Авторизирован як:</div>
                        {this.state.authUser.name}
                    </div> : null}
                </nav>
            </div>
        )
    };
}

const mapStateToProps = (state) => {
    return {
        authUser: state.Auth.user
    }
};

export default connect(mapStateToProps)(LeftMenu);
