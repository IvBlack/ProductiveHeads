import Vue from 'vue'
import Router from 'vue-router'
import VueJwtDecode from 'vue-jwt-decode'

// Containers
const TheContainer = () => import('@/containers/TheContainer')

// Views
const Dashboard = () => import('@/views/Dashboard')

// Views - Pages
const Page404 = () => import('@/views/pages/Page404')
const Page500 = () => import('@/views/pages/Page500')
const AdminLogin = () => import('@/views/pages/AdminLogin')
const Login = () => import('@/views/pages/Login')

// Admin Users
const Users = () => import('@/views/admin/users/Users')
const User = () => import('@/views/admin/users/User')
const EditUser = () => import('@/views/admin/users/EditUser')

// Admin Tests
const AdminTests = () => import('@/views/admin/tests/Tests')
const AdminTest = () => import('@/views/admin/tests/Test')
const EditTest = () => import('@/views/admin/tests/EditTest')
const CreateTest = () => import('@/views/admin/tests/Create')

// Admin Test subjects
const TestSubjects = () => import('@/views/admin/test-subjects/TestSubjects')
const TestSubject = () => import('@/views/admin/test-subjects/TestSubject')
const EditTestSubject = () => import('@/views/admin/test-subjects/EditTestSubject')
const CreateTestSubject = () => import('@/views/admin/test-subjects/CreateTestSubject')
const AdminTestResult = () => import('@/views/admin/test-subjects/TestResult')

// Test Subject Tests
const Tests = () => import('@/views/test-subject/tests/Tests')
const Test = () => import('@/views/test-subject/tests/Test')
const TestResult = () => import('@/views/test-subject/tests/TestResult')


Vue.use(Router)

let router = new Router({
    mode: 'hash', // https://router.vuejs.org/api/#mode
    linkActiveClass: 'active',
    scrollBehavior: () => ({y: 0}),
    routes: configRoutes()
})


router.beforeEach((to, from, next) => {
    let apiToken = localStorage.getItem('api_token')
    if (apiToken) {
        let token = VueJwtDecode.decode(apiToken)
        if (!token || Math.floor(Date.now() / 1000) > token.exp) {
            localStorage.removeItem('api_token')
            localStorage.removeItem('roles')
        }
    }

    let roles = localStorage.getItem("roles");
    if (roles != null) {
        roles = roles.split(',')
    }

    if (to.matched.some(record => record.meta.requiresAdmin)) {
        if (roles != null && roles.indexOf('admin') >= 0) {
            next()
        } else {
            next({
                path: '/admin/login',
                params: {nextUrl: to.fullPath}
            })
        }
    } else if (to.matched.some(record => record.meta.requiresUser)) {
        if (roles != null && roles.indexOf('user') >= 0) {
            next()
        } else {
            next({
                path: '/admin/login',
                params: {nextUrl: to.fullPath}
            })
        }
    } else if (to.matched.some(record => record.meta.requiresTestSubject)) {
        if (roles != null && roles.indexOf('test-subject') >= 0) {
            next()
        } else {
            next({
                path: '/login',
                params: {nextUrl: to.fullPath}
            })
        }
    } else {
        next()
    }
})

export default router

function configRoutes() {
    return [
        {
            path: '/',
            redirect: '/tests',
            name: 'Тесты',
            component: TheContainer,
            meta: {
                requiresTestSubject: true
            },
            children: [
                {
                    path: 'tests',
                    meta: {label: 'Тесты'},
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: '',
                            component: Tests,
                            meta: {
                                requiresTestSubject: true,
                            }
                        },
                        {
                            path: ':id',
                            name: 'Тест',
                            component: Test,
                            meta: {
                                requiresTestSubject: true,
                                label: 'Пройти тест'
                            }
                        },
                        {
                            path: ':id/result',
                            name: 'Тест',
                            component: TestResult,
                            meta: {
                                requiresTestSubject: true,
                                label: 'Результаты'
                            }
                        },
                    ]
                }
            ]
        },
        {
            path: '/admin',
            name: 'Админ',
            component: TheContainer,
            meta: {
                requiresUser: true
            },
            children: [
                {
                    path: '/',
                    name: 'Панель',
                    component: Dashboard
                },
                {
                    path: 'users',
                    meta: {label: 'Users'},
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: '',
                            component: Users,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':id',
                            name: 'Пользователь',
                            component: User,
                            meta: {
                                requiresAdmin: true,
                                label: 'User Details'
                            }
                        },
                        {
                            path: ':id/edit',
                            name: 'Редактировать пользователя',
                            component: EditUser,
                            meta: {
                                requiresAdmin: true,
                                label: 'Редактировать пользователя'
                            }
                        },
                    ]
                },
                {
                    path: 'tests',
                    meta: {label: 'Тесты'},
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: '',
                            component: AdminTests,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: 'create',
                            component: CreateTest,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':id',
                            name: 'Тест',
                            component: AdminTest,
                            meta: {
                                requiresAdmin: true,
                                label: 'Детали Теста'
                            }
                        },
                        {
                            path: ':id/edit',
                            name: 'Тест',
                            component: EditTest,
                            meta: {
                                requiresAdmin: true,
                                label: 'Редактировать тест'
                            }
                        },
                    ]
                },
                {
                    path: 'test-subjects',
                    meta: {label: 'Тестируемые'},
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: '',
                            component: TestSubjects,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: 'create',
                            component: CreateTestSubject,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':id',
                            name: 'Тестируемый',
                            component: TestSubject,
                            meta: {
                                requiresAdmin: true,
                                label: 'Тестируемый'
                            }
                        },
                        {
                            path: ':id/edit',
                            name: 'Тестируемый',
                            component: EditTestSubject,
                            meta: {
                                requiresAdmin: true,
                                label: 'Редактировать информацию о тесируемом'
                            }
                        },
                        {
                            path: ':testSubjectId/results/:userTestId',
                            name: 'Результаты',
                            component: AdminTestResult,
                            meta: {
                                requiresAdmin: true,
                                label: 'Посмотреть результаты тестирования'
                            }
                        },
                    ]
                },
            ]
        },
        {
            path: '/pages',
            redirect: '/pages/404',
            name: 'Страницы',
            component: {
                render(c) {
                    return c('router-view')
                }
            },
            children: [
                {
                    path: '404',
                    name: '404',
                    component: Page404
                },
                {
                    path: '500',
                    name: '500',
                    component: Page500
                },
            ]
        },
        {
            path: '/',
            redirect: '/login',
            name: 'Авторизация',
            component: {
                render(c) {
                    return c('router-view')
                }
            },
            children: [
                {
                    path: 'login',
                    name: 'Авторизация',
                    component: Login
                },
                {
                    path: 'admin/login',
                    name: 'Вход в админ панель',
                    component: AdminLogin
                },
            ]
        },
        {
            path: '*',
            name: '404',
            component: Page404
        }
    ]
}
