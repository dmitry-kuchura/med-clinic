import React from 'react';

class Editor extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            content: '',
        }
    }

    componentDidUpdate(prevProps) {
        if (prevProps !== this.props) {
            this.setState({
                content: this.props.content,
            })
        }
    }

    updateContent(value) {
        this.setState({content: value})
    }

    render() {
        if (this.state.content === null) {
            return null;
        }

        return null
    }
}

export default Editor;
