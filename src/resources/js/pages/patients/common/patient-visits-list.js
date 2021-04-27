import React from 'react';
import {formatDate} from '../../../utils/date-format';

class PatientVisitsList extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            data: {
                from: null,
                to: null,
                perPage: null,
                currentPage: null,
                lastPage: null,
                total: null,
                list: [],
            }
        };
    }

    componentDidUpdate(prevProps) {
        if (prevProps !== this.props) {
            this.setState({
                data: this.props.data,
            })
        }
    }

    render() {
        return (
            <table className="table table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Категорія</th>
                    <th scope="col">Шаблон</th>
                    <th scope="col">Додано</th>
                </tr>
                </thead>
                <tbody>

                <List data={this.state.data.list}/>

                </tbody>
            </table>
        );
    }
}

const List = (props) => {
    let list = props.data;
    let array = [];
    let html;

    if (list.length > 0) {
        list.forEach(function (item) {
            item.data.forEach(function (data) {
                array.push({
                    id: item.id,
                    category: data.category,
                    template: data.template,
                    created_at: data.created_at,
                })
            });
        });

        html = array.map(function (data) {
            return (
                <tr key={data.id}>
                    <td><strong>{data.id}</strong></td>
                    <td><p>{data.category}</p></td>
                    <td><p>{data.template}</p></td>
                    <td><p>{formatDate(data.created_at)}</p></td>
                </tr>
            )
        })

        return html;
    }

    return (
        <tr>
            <td colSpan="4">Нічого відображати!</td>
        </tr>
    )
};

export default PatientVisitsList;
