import React from 'react';

class Loader extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="text-center m-5">
                <div className="spinner-grow" style={{width: '3rem', height: '3rem'}} role="status">
                    <span className="sr-only">Loading...</span>
                </div>
            </div>
        )
    }
}

export default Loader;
