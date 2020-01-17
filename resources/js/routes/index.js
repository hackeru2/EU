import Call from '../components/Call';
import Calls from '../components/Calls';
import Profile from '../components/Profile';

export default {
    mode: 'history',
    routes: [
        {
            path: '/profile',
            name: 'Profile',
            component: Profile
        },
        {
            path: '/calls/:id',
            name: 'Call',
            component: Call
        },
        {
            path: '/calls',
            name: 'Calls',
            component: Calls
        },
        // {
        //     // will match everything
        //     name: 'Call',
        //     component: Call,
        //     path: '*'
        // }
    ]
} 
