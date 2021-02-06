import React from 'react';
import {Link, Redirect} from 'react-router-dom'
import {connect} from 'react-redux'
import {login, updatePassword} from '../../services/auth-service';
import {getParamFromUrl} from "../../helpers/url-params";

class ResetPassword extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            data: {
                token: null,
                password: null,
                password_confirmation: null,
            },
            error: null
        };

        if (getParamFromUrl(props, 'token')) {
            this.state.data.token = getParamFromUrl(props, 'token');
        }

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleChange(event) {
        const name = event.target.name;
        const value = event.target.value;
        const {data} = this.state;
        data[name] = value;
    }

    handleSubmit(event) {
        event.preventDefault();
        const {data} = this.state;

        this.props.dispatch(updatePassword(data))
            .catch(({error, statusCode}) => {
                const responseError = {
                    isError: true,
                    code: statusCode,
                    text: error
                };
                this.setState({responseError});
                this.setState({
                    isLoading: false
                });
            })
    }

    render() {
        const {from} = this.props.location.state || {from: {pathname: '/'}};
        const {isAuthenticated} = this.props;

        if (isAuthenticated) {
            return (
                <Redirect to={from}/>
            )
        }

        return (
            <div id="layoutAuthentication">
                <div id="layoutAuthentication_content">
                    <main>
                        <div className="container">
                            <div className="row justify-content-center">
                                <div className="col-lg-5">
                                    <div className="card shadow-lg border-0 rounded-lg mt-5">
                                        <div className="card-header">
                                            <h3 className="text-center font-weight-light my-4">МедСервіс | Відновлення
                                                паролю</h3>
                                        </div>
                                        <div className="card-body">
                                            <form onSubmit={this.handleSubmit} method="POST">
                                                <div className="form-group">
                                                    <label className="small mb-1"
                                                           htmlFor="password">Введіть новий пароль</label>
                                                    <input
                                                        className="form-control py-4"
                                                        id="password"
                                                        type="password"
                                                        name="password"
                                                        onChange={this.handleChange}
                                                        placeholder="Введіть новий пароль"/>

                                                    {this.state.error &&
                                                    <p style={{color: 'red'}}>{this.state.error}</p>}
                                                </div>

                                                <div className="form-group">
                                                    <label className="small mb-1"
                                                           htmlFor="password_confirm">Підтвердіть паролю</label>
                                                    <input
                                                        className="form-control py-4"
                                                        id="password_confirmation"
                                                        type="password"
                                                        name="password_confirmation"
                                                        onChange={this.handleChange}
                                                        placeholder="Підтвердіть новий пароль"/>

                                                    {this.state.error &&
                                                    <p style={{color: 'red'}}>{this.state.error}</p>}
                                                </div>

                                                <div
                                                    className="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                                    <button type="submit" className="btn btn-primary">Встановити пароль</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div className="card-footer text-center">
                                            <div className="small">
                                                <Link to="/login">Авторизація</Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        isAuthenticated: state.Auth.isAuthenticated,
    }
};

export default connect(mapStateToProps)(ResetPassword)
