import React from 'react';

class Profile extends React.Component {
    constructor(props) {
        super(props);

        this.state = {};
    }

    render() {
        return (
            <main>
                <div className="container p-2 mx-auto flex flex-col">
                    <h1>This is Profile page</h1>
                </div>
            </main>
        );
    }
}

export default Profile;
