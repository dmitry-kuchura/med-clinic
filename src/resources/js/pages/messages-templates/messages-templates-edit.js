import React from 'react';
import {connect} from 'react-redux';
import swal from 'sweetalert';
import {getParamFromUrl} from '../../helpers/url-params';
import {validate} from '../../helpers/validation';
import {getMessageTemplateById, updateMessageTemplate} from '../../services/messages-templates-service';

const rules = {
    'name': ['required'],
    'language': ['string', 'nullable'],
    'alias': ['string', 'nullable'],
    'content': ['required'],
};

class MessagesTemplatesEdit extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            messageTemplate: {
                id: null,
                language: null,
                name: null,
                alias: null,
                content: null,
                created_at: null,
                updated_at: null
            },
        };

        const messageTemplateId = getParamFromUrl(props, 'id');

        if (messageTemplateId) {
            props.dispatch(getMessageTemplateById(messageTemplateId));
        }

        this.handleChangeInput = this.handleChangeInput.bind(this);
        this.handleSubmitForm = this.handleSubmitForm.bind(this);
    }

    componentDidUpdate(prevProps) {
        if (prevProps !== this.props) {
            this.setState({
                messageTemplate: this.props.messageTemplate,
            })
        }
    }

    handleChangeInput(event) {
        event.preventDefault();

        let input = event.target.name;
        let value = event.target.value;
        let state = Object.assign({}, this.state);

        state.messageTemplate[input] = value;

        this.setState(state);
    }

    handleSubmitForm(event) {
        event.preventDefault();

        if (!this.valid()) {
            swal('Неправильно введені данні', 'Перевірте вказанні данні!', 'error');
            return;
        }

        this.props.dispatch(updateMessageTemplate(this.state.messageTemplate))
            .then(success => {
                swal('Добре!', 'Шаблон було оновлено!', 'success');
            })
            .catch(error => {
                swal('Погано!', 'Щось пішло не за планом!', 'error');
            })
    }

    valid() {
        let messageTemplate = this.state.messageTemplate;

        for (const [key, value] of Object.entries(messageTemplate)) {
            if (rules.hasOwnProperty(key)) {
                let valid = validate(key, value, rules[key]);

                return valid === undefined || valid === null;
            }
        }

        return true;
    }

    render() {
        let messageTemplate = this.state.messageTemplate;

        return (
            <main>
                <div className="container-fluid">
                    <h1 className="mt-4">
                        {messageTemplate.id ? 'Редагування шаблону' : 'Додавання нового шаблона'}
                    </h1>
                    <form>
                        <div className="row gutters-sm">
                            <div className="col-md-12">
                                <div className="card mb-3">
                                    <div className="card-body">
                                        <div className="row">
                                            <div className="col-md-12">
                                                <div className="form-group">
                                                    <label htmlFor="formGroupExampleInput">Назва</label>
                                                    <input type="text"
                                                           className={validate('name', messageTemplate.name, rules['name']) ? 'form-control is-invalid' : 'form-control'}
                                                           placeholder="Назва"
                                                           name="name"
                                                           id="name"
                                                           onChange={this.handleChangeInput}
                                                           value={messageTemplate.name ? messageTemplate.name : ''}/>
                                                    <div
                                                        className="invalid-feedback">{validate('name', messageTemplate.name, rules['name'])}</div>
                                                </div>

                                                <div className="form-group">
                                                    <label htmlFor="formGroupExampleInput">Текст повідомлення</label>
                                                    <textarea rows="5"
                                                              className={validate('content', messageTemplate.content, rules['content']) ? 'form-control is-invalid' : 'form-control'}
                                                              placeholder="Назва"
                                                              name="content"
                                                              id="content"
                                                              onChange={this.handleChangeInput}
                                                              value={messageTemplate.content ? messageTemplate.content : ''}/>
                                                    <div
                                                        className="invalid-feedback">{validate('content', messageTemplate.content, rules['content'])}</div>
                                                </div>

                                                <div className="pull-right">
                                                    <button className="btn btn-primary" type="submit"
                                                            onClick={this.handleSubmitForm}>{messageTemplate.id ? 'Оновити' : 'Додати'}
                                                    </button>
                                                    <div className="form-check form-check-inline"/>
                                                    <button className="btn btn-secondary">Назад</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        messageTemplate: state.MessagesTemplates.item
    }
};

export default connect(mapStateToProps)(MessagesTemplatesEdit);
