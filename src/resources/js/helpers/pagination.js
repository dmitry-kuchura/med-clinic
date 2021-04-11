import React from 'react';

const LEFT_PAGE = 'LEFT';
const RIGHT_PAGE = 'RIGHT';

/**
 * Helper method for creating a range of numbers
 * range(1, 5) => [1, 2, 3, 4, 5]
 */
const range = (from, to, step = 1) => {
    let i = from;
    const range = [];

    while (i <= to) {
        range.push(i);
        i += step;
    }

    return range;
}

class Pagination extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            from: 0,
            lastPage: 0,
            currentPage: 0,
            perPage: 0,
            to: 0,
            total: 0,
            list: []
        };

        this.fetchPageNumbers = this.fetchPageNumbers.bind(this);
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

    fetchPageNumbers() {
        const totalPages = this.state.lastPage;
        const currentPage = this.state.currentPage;
        const pageNeighbours = 2;

        /**
         * totalNumbers: the total page numbers to show on the control
         * totalBlocks: totalNumbers + 2 to cover for the left(<) and right(>) controls
         */
        const totalNumbers = 3;
        const totalBlocks = totalNumbers + 2;

        if (totalPages > totalBlocks) {
            const startPage = Math.max(2, currentPage - pageNeighbours);
            const endPage = Math.min(totalPages - 1, currentPage + pageNeighbours);

            let pages = range(startPage, endPage);

            pages = [LEFT_PAGE, ...pages, RIGHT_PAGE];

            return [1, ...pages, totalPages];
        }

        return range(1, totalPages);
    }

    render() {
        if (this.state.total === null || this.state.total === 0 || this.state.lastPage === 1) {
            return null;
        }

        const pages = this.fetchPageNumbers();

        return (
            <div className="row">
                <div className="col-md-5">
                    <div className="dataTables_info" id="dataTable_info" role="status"
                         aria-live="polite">Відображено
                        від {this.state.from} до {this.state.to} з {this.state.total} записів
                    </div>
                </div>
                <div className="col-md-7">
                    <div style={{float: 'right'}}>
                        <ul className="pagination">
                            {pages.map((page, index) => {
                                if (page === LEFT_PAGE) return (
                                    <li key={index} className="page-item">
                                        <a className="page-link" href="#" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                            <span className="sr-only">Previous</span>
                                        </a>
                                    </li>
                                );

                                if (page === RIGHT_PAGE) return (
                                    <li key={index} className="page-item">
                                        <a className="page-link" href="#" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                            <span className="sr-only">Next</span>
                                        </a>
                                    </li>
                                );

                                return (
                                    <li key={index}
                                        className={this.state.currentPage === page ? 'page-item active' : 'page-item'}>
                                        <a className="page-link" href="#" onClick={this.props.handleChangePage}
                                           id={page}>{page}</a>
                                    </li>
                                );
                            })}
                        </ul>
                    </div>
                </div>
            </div>
        );
    }
}

export default Pagination;
