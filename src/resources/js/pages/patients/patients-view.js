import React from 'react';
import {connect} from 'react-redux';

class PatientsView extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            record: null
        };

        // props.dispatch(getRecordsList())
    }

    componentDidUpdate(prevProps) {
        // if (prevProps.recordsList !== this.props.recordsList) {
        //     this.setState({list: this.props.recordsList})
        // }
    }

    render() {
        return (
            <main>
                <div className="container-fluid">
                    <h1 className="mt-4">Просмотр статьи</h1>
                    <div className="card mb-4">
                        <div className="card-body">
                            <div>Here is view</div>
                        </div>
                    </div>
                </div>
            </main>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        recordsList: state.Records.item
    }
};

export default connect(mapStateToProps)(PatientsView);
