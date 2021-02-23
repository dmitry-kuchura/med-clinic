import React from 'react';

class Modal extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            show: false,
            title: '',
            content: '',
        };
    }

    componentDidUpdate(prevProps) {
        if (prevProps !== this.props) {
            this.setState({
                show: this.props.show,
                title: this.props.title,
                content: this.props.children
            })
        }
    }

    render() {
        const showHideClassName = this.state.show ? 'modal display-block' : 'modal display-none';

        return (
            <div className={showHideClassName} tabIndex="-1">
                <div className="modal-dialog">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title">{this.state.title}</h5>
                            <button type="button" className="close" data-dismiss="modal" aria-label="Close"
                                    onClick={this.props.handleHide}>
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div className="modal-body">
                            {this.state.content}
                        </div>
                        <div className="modal-footer">
                            <button type="button" className="btn btn-secondary" data-dismiss="modal"
                                    onClick={this.props.handleHide}>Закрити
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default Modal;
