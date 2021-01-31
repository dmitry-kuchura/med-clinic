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
            dropdownRecords: false
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
            case "records":
                this.setState({dropdownRecords: !this.state.dropdownRecords});
                break;
        }
    }

    render() {
        return (
            <div id="layoutSidenav_nav">
                <nav className="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div className="sb-sidenav-menu">
                        <div className="nav">
                            <div className="sb-sidenav-menu-heading">Основное</div>

                            <Link className="nav-link" to="/admin">
                                <div className="sb-nav-link-icon">
                                    <i className="fas fa-tachometer-alt"/>
                                </div>
                                Главная
                            </Link>

                            <div className="sb-sidenav-menu-heading">Контент</div>

                            <Link to="#" className="nav-link collapsed" id="records" onClick={this.handleDropdown}>
                                <div className="sb-nav-link-icon">
                                    <i className="fas fa-book-open"/>
                                </div>
                                Статьи
                                <div className="sb-sidenav-collapse-arrow">
                                    {this.state.dropdownRecords ? <i className="fas fa-angle-right"/> : <i className="fas fa-angle-down"/>}
                                </div>
                            </Link>
                            <div className="collapse" style={this.state.dropdownRecords ? closed : opened}>
                                <nav className="sb-sidenav-menu-nested nav">
                                    <Link className="nav-link" to="/admin/records">Список статей</Link>
                                    <Link className="nav-link" to="/admin/records/create">Добавить статью</Link>
                                </nav>
                            </div>

                            <div className="sb-sidenav-menu-heading">Дополнительно</div>

                            <Link to="/admin/charts" className="nav-link">
                                <div className="sb-nav-link-icon">
                                    <i className="fas fa-chart-area"/>
                                </div>
                                Статистика</Link>
                            <a className="nav-link" href="tables.html">
                                <div className="sb-nav-link-icon">
                                    <i className="fas fa-cogs"/>
                                </div>
                                Настройки</a>
                        </div>
                    </div>

                    {this.state.authUser ? <div className="sb-sidenav-footer">
                        <div className="small">Авторизирован как:</div>
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
