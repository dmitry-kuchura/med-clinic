import React from 'react';
import {formatDate} from "../../../utils/date-format";
import Pagination from "../../../helpers/pagination";

class PatientTestsList extends React.Component {
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
                    <th scope="col">Аналіз</th>
                    <th scope="col">Результат</th>
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
            return (
                <tr key={item.id}>
                    <td><strong>{item.id}</strong></td>
                    <td><p>{item.test.name}</p></td>
                    <td><p>{item.result}</p></td>
                    <td><p>{formatDate(item.created_at)}</p></td>
                </tr>
            )
        });

        return html;
    }

    return (
        <tr>
            <td colSpan="4">Нечего отображать!</td>
        </tr>
    )
};

export default PatientTestsList;
