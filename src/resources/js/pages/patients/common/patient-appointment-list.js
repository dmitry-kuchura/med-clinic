import React from 'react';
import {formatDate} from "../../../utils/date-format";

class PatientAppointmentList extends React.Component {
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
                    <th scope="col">Дата прийому</th>
                    <th scope="col">Коментар</th>
                    <th scope="col">Лікар / Аналіз</th>
                    <th scope="col">Тип</th>
                    <th scope="col">Створено</th>
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
                    <td><p>{formatDate(item.appointment_at)}</p></td>
                    <td><p>{item.comment}</p></td>
                    <td><p>{item.doctor_name}</p></td>
                    <td><p>{item.type}</p></td>
                    <td><p>{formatDate(item.updated_at)}</p></td>
                </tr>
            )
        });

        return html;
    }

    return (
        <tr>
            <td colSpan="4">Нічого відображати!</td>
        </tr>
    )
};

export default PatientAppointmentList;
