import Call from '../components/Call';
import Calls from '../components/Calls';
import Carousel from '../components/Carousel';
import Expretize from '../components/Expretize';
import Keywords from '../components/Keywords';
import Profile from '../components/Profile';

export default {
    mode: 'history',
    routes: [
        {
            path: '/expretize',
            name: 'Expretize',
            component: Expretize
        },
        {
            path: '/carousel',
            name: 'Carousel',
            component: Carousel
        },
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
        {
            path: '/Keywords',
            name: 'Keywords',
            component: Keywords
        },
        // {
        //     // will match everything
        //     name: 'Call',
        //     component: Call,
        //     path: '*'
        // }
    ]
} 
