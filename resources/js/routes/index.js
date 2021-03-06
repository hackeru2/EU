import Call from '../components/Call';
import Calls from '../components/Calls';
import CallsTable from '../components/CallsTable';
import Carousel from '../components/Carousel';
import Expretize from '../components/Expretize';
import Keywords from '../components/Keywords';
import KeywordsPortalTree from '../components/KeywordsPortalTree';
import NewProfile from '../components/NewProfile';
import Profile from '../components/Profile';
import Programme from '../components/Programme';
import ProgrammeTags from '../components/ProgrammeTags';
import Subjects from '../components/Subjects';


export default {
    mode: 'history',
    routes: [
        {
            path: '/subjects',
            name: 'Subjects',
            component: Subjects
        },
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
            path: '/new-profile',
            name: 'New Profile',
            component: NewProfile
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
            path: '/calls-table',
            name: 'CallsTable',
            component: CallsTable
        },
        {
            path: '/programme',
            name: 'Programme',
            component: Programme
        },
        {
            path: '/Keywords',
            name: 'Keywords',
            component: Keywords
        },
        {
            path: '/portal',
            name: 'KeywordsPortalTree',
            component: KeywordsPortalTree
        },
        {
            path: '/programme-tags-new',
            name: 'ProgrammeTagsNew',
            component: ProgrammeTags
        },
        {
            path: '/programme-tags',
            name: 'ProgrammeTags',
            component: ProgrammeTags
        },

        // {
        //     // will match everything
        //     name: 'Call',
        //     component: Call,
        //     path: '*'
        // }
    ]
} 
