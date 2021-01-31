import React from 'react';

class Pagination extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            from: 0,
            lastPage: 0,
            currentPage: 0,
            perPage: 0,
            to: 0,
            total: 0
        };
    }

    componentDidUpdate(prevProps) {
        if (prevProps !== this.props) {
            this.setState({
                from: this.props.state.from,
                to: this.props.state.to,
                perPage: this.props.state.perPage,
                currentPage: this.props.state.currentPage,
                lastPage: this.props.state.lastPage,
                total: this.props.state.total,
                list: this.props.state.list
            })
        }
    }

    render() {
        return (
            <div className="row">
                <div className="col-md-5">
                    <div className="dataTables_info" id="dataTable_info" role="status"
                         aria-live="polite">Показано
                        от {this.state.from} до {this.state.to} из {this.state.total} записей
                    </div>
                </div>
                <div className="col-md-7">
                    <div style={{float: 'right'}}>
                        <ul className="pagination">

                            <li className={this.state.currentPage === 1 ? "page-item previous disabled" : "page-item previous"}>
                                <a href="#" className="page-link">«</a>
                            </li>

                            <Pages state={this.state} handleChangePage={this.props.handleChangePage}/>

                            <li className={this.state.lastPage === 1 ? "page-item next disabled" : "page-item next"}>
                                <a href="#" className="page-link">»</a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        );
    }
}

const Pages = (props) => {
    let {state} = props;
    let pages = [];

    for (let i = 1; i <= state.lastPage; i++) {
        pages.push(i)
    }

    if (pages.length) {
        return pages.map(function (page) {
            return (
                <li className={state.currentPage === page ? "page-item active" : "page-item"} key={page}>
                    <a href="#" className="page-link" onClick={props.handleChangePage} id={page}>{page}</a>
                </li>
            )
        });
    }

    return null;
};

export default Pagination;
