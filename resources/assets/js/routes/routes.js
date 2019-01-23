import Register from '../views/Auth/Register';
import Dashboard from '../views/Dashboard';
import Students from '../views/Students';
import Jury from '../views/Jury';
import Events from '../views/Events';
import Event from '../views/Event';
import EventDashboard from '../views/Event/Dashboard';
import EventJury from '../views/Event/Jury';
import EventProjects from '../views/Event/Projects';
import EventStudents from '../views/Event/Students';
import EventSettings from '../views/Event/Settings';
import StudentConfig from '../views/Event/StudentConfig';
import StudentList from '../views/Event/StudentList';
import StudentProjects from '../views/Event/StudentProjects';

export default [
    {
        path: '/register-admin',
        name: 'registerAdmin',
        component: Register
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            title: 'Bienvenue sur Jiri !'
        }
    },
    {
        path: '/jury',
        name: 'jury',
        component: Jury,
        meta: {
            title: 'Membres du jury en base de données'
        }
    },
    {
        path: '/students',
        name: 'students',
        component: Students,
        meta: {
            title: 'Étudiants en base de données'
        }
    },
    {
        path: '/events',
        name: 'events',
        component: Events,
        meta: {
            title: 'Liste des événements'
        }
    },
    {
        path: '/events/:id',
        component: Event,
        name: 'Event',
        meta: {
            title: 'Design Web',
            page: 'event',
        },
        children: [
            {
                path: 'dashboard',
                'component': EventDashboard,
                name: 'EventDashboard',
                meta: {
                    page: 'event'
                },
                props: { default: true }
            },
            {
                path: 'jury',
                component: EventJury,
                name: 'EventJury',
                meta: {
                    page: 'event'
                }
            },
            {
                path: 'students',
                component: EventStudents,
                name: 'EventStudents',
                meta: {
                    page: 'event'
                },
            },
            {
                path: 'students/:student/config',
                name: 'ConfigEventStudent',
                component: StudentConfig,
                meta: {
                    page: 'event'
                }
            },
            {
              path: 'students/list',
              name: 'StudentList',
              component: StudentList
            },
            {
              path: 'students/:student',
              name: 'StudentProjects',
              component: StudentProjects
            },
            {
                path: 'projects',
                component: EventProjects,
                name: 'EventProjects',
                meta: {
                    page: 'event'
                }
            },
            {
                path: 'settings',
                component: EventSettings,
                name: 'EventSettings',
                meta: {
                    page: 'event'
                }
            }
        ]
    },
]
