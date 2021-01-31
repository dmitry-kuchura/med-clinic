import React from 'react'
import {connect} from 'react-redux'
import Navigation from './components/nav'
import Footer from './components/footer'
import LeftMenu from "./components/left-menu";

class Main extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div>
                <Navigation/>
                <div id="layoutSidenav">
                    <LeftMenu/>
                    <div id="layoutSidenav_content">
                        {this.props.children}
                        <Footer/>
                    </div>
                </div>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        isAuthenticated: state.Auth.isAuthenticated
    }
};

export default connect(mapStateToProps)(Main);
