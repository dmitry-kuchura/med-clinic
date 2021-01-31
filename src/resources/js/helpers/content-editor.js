import React from 'react';

const block = {display: 'block'};
const none = {display: 'none'};

class ContentEditor extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            content: '',
            showContent: true,
            showHTML: false
        };

        this.handleClick = this.handleClick.bind(this);
    }

    componentDidUpdate(prevProps) {
        if (prevProps !== this.props) {
            this.setState({
                content: this.props.content,
            })
        }
    }

    handleClick(event) {
        let command = event.currentTarget.dataset.action;
        let url = '';

        switch (command) {
            case 'h1':
                document.execCommand('formatBlock', false, command);
                break;
            case 'createlink':
                url = 'http://example.com';
                document.execCommand(command, false, url);
                break;
            case 'insertimage':
                url = 'http://example.com';
                document.execCommand(command, false, url);
                break;
            case 'code':
                this.setState({
                    showHTML: this.state.showHTML !== true,
                    showContent: this.state.showContent !== true,
                });

                break;
            default:
                document.execCommand(command, false, null);
                break;
        }

        let content = this.refs.textarea.innerHTML;

        this.setState({
            content: content
        });
    };

    render() {
        let colors = ['000000', 'FF9966', '6699FF', '99FF66', 'CC0000', '00CC00', '0000CC', '333333', '0066FF', 'FFFFFF'];

        return (
            <div className="editor">
                <div className="toolbar">
                    <div className="line">
                        <div className="box">
                            <span className="btn icon smaller" data-action="bold" title="Bold"
                                  onClick={this.handleClick}>
                              <i className="fa fa-bold"/>
                            </span>
                            <span className="btn icon smaller" data-action="italic" title="Italic"
                                  onClick={this.handleClick}>
                              <i className="fa fa-italic"/>
                            </span>
                            <span className="btn icon smaller" data-action="underline" title="Underline"
                                  onClick={this.handleClick}>
                                <i className="fa fa-underline"/>
                            </span>
                            <span className="btn icon smaller" data-action="strikeThrough" title="Strike through"
                                  onClick={this.handleClick}>
                              <i className="fa fa-strikethrough"/>
                            </span>
                        </div>
                        <div className="box">
                            <span className="btn icon" data-action="justifyLeft" title="Justify left"
                                  onClick={this.handleClick}>
                              <i className="fa fa-align-left"/>
                            </span>
                            <span className="btn icon" data-action="justifyCenter" title="Justify center"
                                  onClick={this.handleClick}>
                              <i className="fa fa-align-center"/>
                            </span>
                            <span className="btn icon" data-action="justifyRight" title="Justify right"
                                  onClick={this.handleClick}>
                              <i className="fa fa-align-right"/>
                            </span>
                            <span className="btn icon" data-action="formatBlock" title="Justify block"
                                  onClick={this.handleClick}>
                              <i className="fa fa-align-justify"/>
                            </span>
                        </div>
                        <div className="box">
                            <span className="btn icon" data-action="insertOrderedList" title="Insert ordered list"
                                  onClick={this.handleClick}>
                              <i className="fa fa-list-ol"/>
                            </span>
                            <span className="btn icon" data-action="insertUnorderedList" title="Insert unordered list"
                                  onClick={this.handleClick}>
                              <i className="fa fa-list-ul"/>
                            </span>
                            <span className="btn icon" data-action="outdent" title="Outdent" onClick={this.handleClick}>
                                <i className="fa fa-outdent"/>
                            </span>
                            <span className="btn icon" data-action="indent" title="Indent" onClick={this.handleClick}>
                                <i className="fa fa-indent"/>
                            </span>
                        </div>
                        <div className="box">
                            <span className="btn icon" data-action="insertHorizontalRule" title="Insert horizontal rule"
                                  onClick={this.handleClick}>
                                <i className="fa fa-ruler-horizontal"/>
                            </span>
                        </div>
                        <div className="box">
                            <span className="btn icon smaller" data-action="undo" title="Undo"
                                  onClick={this.handleClick}>
                              <i className="fa fa-undo"/>
                            </span>
                            <span className="btn icon" data-action="removeFormat" title="Remove format"
                                  onClick={this.handleClick}>
                                <i className="fa fa-remove-format"/>
                            </span>
                        </div>
                        <div className="box">
                            <span className="btn icon smaller" data-action="createlink" title="Create link"
                                  onClick={this.handleClick}>
                              <i className="fa fa-link"/>
                            </span>
                            <span className="btn icon smaller" data-action="unlink" title="Unlink"
                                  onClick={this.handleClick}>
                              <i className="fa fa-unlink"/>
                            </span>
                            <span className="btn icon" data-action="insertimage" title="Insert image"
                                  onClick={this.handleClick}>
                                <i className="fa fa-image"/>
                            </span>
                            <span className="btn icon" data-action="insertHTML" title="Insert HTML"
                                  onClick={this.handleClick}>
                                <i className="fa fa-video"/>
                            </span>
                        </div>
                        <div className="box">
                            <span className="btn icon" data-action="code" title="Show HTML-Code"
                                  onClick={this.handleClick}>
                              <i className="fa fa-code"/>
                            </span>
                        </div>
                    </div>
                </div>
                <div className="content-area">
                    <div className="editor-view" id="content" style={this.state.showContent ? block : none}
                         ref="textarea"
                         onChange={this.props.onEditorStateChange}
                         contentEditable={true}
                         suppressContentEditableWarning={true}>
                        {this.state.content}
                    </div>
                    <textarea className="html-view"
                              style={this.state.showHTML ? block : none}
                              defaultValue={this.state.content}/>
                </div>
            </div>
        );
    }
}

export default ContentEditor;
