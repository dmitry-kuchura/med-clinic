import React from 'react';
import {Link, Redirect} from 'react-router-dom'
import {connect} from 'react-redux'
import {login} from '../../services/auth-service';

class ResetPassword extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            credentials: {
                email: null,
                password: null,
            },
            error: null
        };

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleChange(event) {
        const name = event.target.name;
        const value = event.target.value;
        const {credentials} = this.state;
        credentials[name] = value;
    }

    handleSubmit(event) {
        event.preventDefault();
        const {credentials} = this.state;

        this.props.dispatch(login(credentials))
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
                                                        id="password_confirm"
                                                        type="password"
                                                        name="password_confirm"
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
