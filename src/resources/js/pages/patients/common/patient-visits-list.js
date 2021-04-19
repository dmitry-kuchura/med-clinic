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
    let html;

    if (list.length > 0) {
        html = list.map(function (item) {
            if (item.data) {
                return (
                    <tr key={item.id}>
                        <td><strong>{item.id}</strong></td>
                        <td><p>{item.data.category}</p></td>
                        <td><p>{item.data.template}</p></td>
                        <td><p>{formatDate(item.data.created_at)}</p></td>
                    </tr>
                )
            }
        });

        return html;
    }

    return (
        <tr>
            <td colSpan="4">Нічого відображати!</td>
        </tr>
    )
};

export default PatientVisitsList;
