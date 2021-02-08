import React from 'react';
import {connect} from 'react-redux';
import {getParamFromUrl} from '../../helpers/url-params';
import {validate} from '../../helpers/validation';
import Editor from "../../helpers/editor";

const rules = {
    "name": ["required"],
    "alias": ["url", "nullable"],
    "content": ["content", "required"],
    "title": ["string", "nullable"],
    "description": ["string", "nullable"],
    "keywords": ["string", "nullable"],
    "status": ["string", "nullable"],
    "category_id": ["integer"],
};

class PatientsEdit extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            record: {
                id: null,
                alias: null,
                views: null,
                status: 0,
                category_id: 0,
                created_at: null,
                updated_at: null,
            },
            categories: []
        };

        if (getParamFromUrl(props, 'id')) {
            props.dispatch(getRecordById(getParamFromUrl(props, 'id')));
        }

        this.handleChangeInput = this.handleChangeInput.bind(this);
        this.handleSubmitForm = this.handleSubmitForm.bind(this);
        this.handleUpdateContent = this.handleUpdateContent.bind(this);
    }

    componentDidUpdate(prevProps) {
        if (prevProps.record !== this.props.record) {
            this.setState({
                record: this.props.record,
                categories: this.props.categories
            })
        }
    }

    handleChangeInput(event) {
        event.preventDefault();

        let input = event.target.id;
        let value = event.target.value;
        let state = Object.assign({}, this.state);

        switch (input) {
            case 'category_id':
                state.record.category_id = parseInt(value);
                break;
            case 'status':
                state.record.status = this.state.record.status === 0 ? 1 : 0;
                break;
            default:
                state.record[input] = value;
                break;
        }

        this.setState(state);
    }

    handleUpdateContent(content) {
        let state = Object.assign({}, this.state);

        state.record['content'] = content;

        this.setState(state);
    }

    handleSubmitForm(event) {
        event.preventDefault();

        const self = this;

        this.props.dispatch(updateRecord(this.state.record.id, this.state.record))
            .then(success => {
                self.props.history.push('/admin/records');
            });
    }

    render() {
        let record = this.state.record;
        let categories = this.state.categories;

        return (
            <main>
                <div className="container-fluid">
                    <h1 className="mt-4">
                        {this.state.record.id ? 'Редактирование статьи' : 'Создание статьи'}
                    </h1>

                    <div className="card mb-4">
                        <div className="card-body">
                            <form>
                                <div className="row">
                                    <div className="col-md-8">
                                        <div className="form-group">
                                            <label htmlFor="formGroupExampleInput">Название</label>
                                            <input type="text"
                                                   className={validate("name", record.name, rules["name"]) ? "form-control is-invalid" : "form-control"}
                                                   placeholder="Название статьи"
                                                   id="name"
                                                   onChange={this.handleChangeInput}
                                                   value={record.name ? record.name : ''}/>
                                            <div
                                                className="invalid-feedback">{validate("name", record.name, rules["name"])}</div>
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="formGroupExampleInput2">Контент</label>
                                            <Editor content={record.name}
                                                    updateContent={this.handleUpdateContent}/>
                                        </div>

                                        <hr/>

                                        <div className="form-group">
                                            <label htmlFor="formGroupExampleInput2">Title</label>
                                            <input type="text"
                                                   className={validate("title", record.title, rules["title"]) ? "form-control is-invalid" : "form-control"}
                                                   placeholder="Title"
                                                   id="title"
                                                   onChange={this.handleChangeInput}
                                                   value={record.title ? record.title : ''}/>
                                            <div
                                                className="invalid-feedback">{validate("title", record.title, rules["title"])}</div>
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="formGroupExampleInput2">Description</label>
                                            <textarea rows="3"
                                                      className={validate("description", record.description, rules["description"]) ? "form-control is-invalid" : "form-control"}
                                                      placeholder="Description"
                                                      id="description"
                                                      onChange={this.handleChangeInput}
                                                      value={record.description ? record.description : ''}/>
                                            <div
                                                className="invalid-feedback">{validate("description", record.description, rules["description"])}</div>
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="formGroupExampleInput2">Keywords</label>
                                            <textarea rows="3"
                                                      className={validate("keywords", record.keywords, rules["keywords"]) ? "form-control is-invalid" : "form-control"}
                                                      placeholder="Keywords"
                                                      id="keywords"
                                                      onChange={this.handleChangeInput}
                                                      value={record.keywords ? record.keywords : ''}/>
                                            <div
                                                className="invalid-feedback">{validate("keywords", record.keywords, rules["keywords"])}</div>
                                        </div>

                                        <hr/>

                                        <div className="pull-right">
                                            <button className="btn btn-primary" type="submit"
                                                    onClick={this.handleSubmitForm}>Сохранить
                                            </button>
                                            <div className="form-check form-check-inline"/>
                                            <button className="btn btn-secondary">Назад</button>
                                        </div>
                                    </div>

                                    <div className="col-md-4">
                                        <div className="form-group">
                                            <label htmlFor="formGroupExampleInput2">Ссылка</label>
                                            <input type="text"
                                                   className={validate("alias", record.alias, rules["alias"]) ? "form-control is-invalid" : "form-control"}
                                                   placeholder="Ссылка"
                                                   id="alias"
                                                   onChange={this.handleChangeInput}
                                                   value={record.alias ? record.alias : ''}/>
                                            <div
                                                className="invalid-feedback">{validate("alias", record.alias, rules["alias"])}</div>
                                        </div>

                                        <hr/>

                                        <div className="form-group">
                                            <label htmlFor="category_id">Категория</label>
                                            <select size="3"
                                                    className={validate("category_id", record.category_id, rules["category_id"]) ? "form-control is-invalid" : "form-control"}
                                                    id="category_id"
                                                    value={record.category_id}
                                                    onChange={this.handleChangeInput}>

                                                <option value={0}>Open this select menu</option>
                                                {categories.map(category => {
                                                        return <option key={category.id}
                                                                       value={category.id}>{category.name}</option>
                                                    }
                                                )};
                                            </select>
                                            <div
                                                className="invalid-feedback">{validate("category_id", record.category_id, rules["category_id"])}</div>
                                        </div>
                                        <div className="form-group">
                                            <div className="form-check form-check-inline">
                                                <input className="form-check-input"
                                                       type="radio"
                                                       onChange={this.handleChangeInput}
                                                       checked={record.status === 1}
                                                       id="status"/>
                                                <label className="form-check-label"
                                                       htmlFor="statusEnabled">Опубликовано</label>

                                                <div className="form-check form-check-inline"/>

                                                <input className="form-check-input"
                                                       type="radio"
                                                       onChange={this.handleChangeInput}
                                                       checked={record.status === 0}
                                                       id="status"/>
                                                <label className="form-check-label" htmlFor="statusDisabled">Не
                                                    опубликовано</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        record: state.Records.item,
        categories: state.Categories.list
    }
};

export default connect(mapStateToProps)(PatientsEdit);
