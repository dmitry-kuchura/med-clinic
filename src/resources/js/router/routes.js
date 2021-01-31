import Login from '../pages/auth/login'
import Logout from '../pages/logout';
import Register from '../pages/auth/register'
// import ForgotPassword from '../pages/auth/forgot-password'
// import ResetPassword from '../pages/auth/reset-password'
import Dashboard from '../pages/dashboard'
import NoMatch from '../pages/404'

const routes = [
    {
        path: '/',
        exact: true,
        auth: true,
        component: Dashboard
    }, {
        path: '/login',
        exact: true,
        auth: false,
        component: Login
    }, {
        path: '/logout',
        exact: true,
        auth: true,
        component: Logout
    }, {
        path: '/register',
        exact: true,
        auth: false,
        component: Register
    },
    // {
    //     path: '/admin/forgot-password',
    //     exact: true,
    //     auth: false,
    //     component: ForgotPassword
    // }, {
    //     path: '/admin/password/reset/:token',
    //     exact: true,
    //     auth: false,
    //     component: ResetPassword
    // },
    {
        path: '',
        exact: true,
        auth: false,
        component: NoMatch
    }
];

export default routes;
