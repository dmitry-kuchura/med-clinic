import React from 'react';

class Loader extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="spinner-grow" role="status">
                <span className="sr-only">Loading...</span>
            </div>
        )
    }
}

export default Loader;
