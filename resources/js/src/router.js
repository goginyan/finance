import Vue from 'vue'
import Router from 'vue-router'
import store from './store/store.js'
import Main from '@js/common/layouts/main/Main.vue'
import Overview from './views/overview/Overview.vue'
import Transactions from './views/transactions/Transactions.vue'
import BankAccounts from './views/accounts/BankAccounts.vue'
import Messages from './views/messages/Messages.vue'
import AddTransaction from './views/transactions/AddTransaction.vue'
// import AddAccount from './views/accounts/AddAccount.vue'
import ProfileView from "./views/profile/ProfileView"
import FullPage from '@js/common/layouts/full-page/FullPage.vue'
import Error404 from '@js/common/pages/Error404.vue'
import Statement from './views/Statement'
import EditPassword from './views/profile/EditPassword'

Vue.use(Router)

const router = new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    scrollBehavior() {
        return {x: 0, y: 0}
    },
    routes: [
        {
            // =============================================================================
            // MAIN LAYOUT ROUTES
            // =============================================================================
            path: '/',
            component: Main,
            children: [
                // =============================================================================
                // Theme Routes
                // =============================================================================
                {
                    path: '',
                    name: 'overview',
                    component: Overview,
                },
                {
                    path: 'transactions',
                    name: 'transactions',
                    component: Transactions,
                },
                {
                    path: 'accounts',
                    name: 'accounts',
                    component: BankAccounts,
                },
                {
                    path: 'messages',
                    name: 'messages',
                    component: Messages,
                },
                {
                    path: '/transactions/new',
                    name: 'new-transaction',
                    component: AddTransaction,
                },
                {
                    path: 'profile',
                    name: 'profile',
                    component: ProfileView,
                },
                {
                    path: 'profile/password',
                    name: 'password',
                    component: EditPassword
                },
            ],
        },
        // =============================================================================
        // FULL PAGE LAYOUTS
        // =============================================================================
        {
            path: '',
            component: FullPage,
            children: [
                // =============================================================================
                // PAGES
                // =============================================================================
                {
                    path: '404',
                    name: 'page-error-404',
                    component: Error404,
                },
                {
                    path     : 'statement',
                    name     : 'statement',
                    component: Statement,
                    props    : { transaction: {} }
                }
            ]
        },
        // Redirect to 404 page, if no match found
        {
            path: '*',
            redirect: '/404'
        }
    ],
})

router.afterEach(() => {
    // Remove initial loading
    const appLoading = document.getElementById('loading-bg')
    if (appLoading) {
        appLoading.style.display = "none";
    }
})

export default router
