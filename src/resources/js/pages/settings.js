import React from 'react';

class Settings extends React.Component {
    constructor(props) {
        super(props);

        this.state = {};
    }

    render() {
        let callout = {
            padding: '1.25rem',
            marginTop: '1.25rem',
            marginBottom: '1.25rem',
            border: '1px solid #eee',
            borderLeftWidth: '.25rem',
            borderRadius: '.25rem',
            borderLeftColor: '#f0ad4e'
        }

        return (
            <main>
                <div className="container-fluid">
                    <h1 className="mt-4">Налаштування</h1>
                    <form>
                        <div style={callout}>
                            <h5 id="jquery-incompatibility">Нагадування день у день</h5>
                            <p className="text-muted">
                                З котрої години розсилати повідомлення про запис до лікаря та о котрій не турбувати.
                                <br/>
                                <strong>Скоріш за все якшо пацієнтів очікується приблизно до 100 вини будуть розіслані
                                    за 2-3 години</strong>
                            </p>
                        </div>

                        <div className="form-row">
                            <div className="col-md-6 mb-3">
                                <label htmlFor="time-start">З котрої години</label>
                                <div className="input-group">
                                    <div className="input-group-prepend">
                                        <span className="input-group-text" id="time-start">
                                            <i className="fas fa-history"></i>
                                        </span>
                                    </div>
                                    <input type="text" className="form-control" value="07:00" required/>
                                </div>
                            </div>
                            <div className="col-md-6 mb-3">
                                <label htmlFor="time-end">До котрої години</label>
                                <div className="input-group">
                                    <div className="input-group-prepend">
                                        <span className="input-group-text" id="time-end">
                                            <i className="fas fa-history"></i>
                                        </span>
                                    </div>
                                    <input type="text" className="form-control" value="18:00" required/>
                                </div>
                            </div>
                        </div>

                        <hr/>

                        <div style={callout}>
                            <h5 id="jquery-incompatibility">Нагадування за добу</h5>
                            <p className="text-muted">
                                З котрої години розсилати повідомлення про запис до лікаря
                                <br/>
                                <strong>Аналогічно до попереднього налаштування, якшо пацієнтів очікується приблизно до
                                    100 вини будуть розіслані за 2-3 години</strong>
                            </p>
                        </div>

                        <div className="form-row">
                            <div className="col-md-6 mb-3">
                                <label htmlFor="per-day">З котрої години</label>
                                <div className="input-group">
                                    <div className="input-group-prepend">
                                        <span className="input-group-text" id="per-day">
                                            <i className="fas fa-history"></i>
                                        </span>
                                    </div>
                                    <input type="text" className="form-control" value="14:00" required/>
                                </div>
                            </div>
                        </div>

                        <hr/>

                        <div style={callout}>
                            <h5 id="jquery-incompatibility">Налаштування сервісу TurboSMS</h5>
                            <p className="text-muted">
                                З котрої години розсилати повідомлення про запис до лікаря
                                <br/>
                                <strong>Аналогічно до попереднього налаштування, якшо пацієнтів очікується приблизно до
                                    100 вини будуть розіслані за 2-3 години</strong>
                            </p>
                        </div>

                        <div className="form-row">
                            <div className="col-md-6 mb-3">
                                <label htmlFor="sender-key">Унікальний ключ</label>
                                <div className="input-group">
                                    <div className="input-group-prepend">
                                        <span className="input-group-text" id="sender-key">
                                            <i className="fas fa-key"></i>
                                        </span>
                                    </div>
                                    <input type="text" className="form-control" value="ae9753fa09ba530a25cb29e68b9f59ab39997986" required/>
                                </div>
                            </div>
                            <div className="col-md-6 mb-3">
                                <label htmlFor="sender-name">Ім'я відправника</label>
                                <div className="input-group">
                                    <div className="input-group-prepend">
                                        <span className="input-group-text" id="sender-name">
                                            <i className="fas fa-user"></i>
                                        </span>
                                    </div>
                                    <input type="text" className="form-control" value="IT Alarm" required/>
                                </div>
                            </div>
                        </div>

                        <button className="btn btn-primary" type="submit">Оновити налаштування</button>
                    </form>
                </div>
            </main>
        );
    }
}

export default Settings;
