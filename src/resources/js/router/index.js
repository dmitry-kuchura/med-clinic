import React from 'react'
import {
    BrowserRouter as Router,
    Switch
} from 'react-router-dom'

import routes from './routes'
import GuestRoute from './guest-route'
import AuthRoute from './auth-route'

const Routes = () => (
    <Router>
        <Switch>
            {routes.map((route, i) => {
                if (route.auth) {
                    return <AuthRoute key={i} {...route}/>
                } else {
                    return <GuestRoute key={i} {...route}/>
                }
            })}
        </Switch>
    </Router>
);

export default Routes;
