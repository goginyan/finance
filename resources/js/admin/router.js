import Vue from 'vue'
import Router from 'vue-router'
import { AclRule } from 'vue-acl'
import Main from '@js/common/layouts/main-admin/Main.vue'
import Transactions from '@js/common/views/transactions/Transactions.vue'
import TransactionCreateEdit from '@js/common/views/transactions/TransactionCreateEdit'
import BankAccounts from '@js/common/views/accounts/BankAccounts.vue'
import AccountCreateEdit from '@js/common/views/accounts/AccountCreateEdit'
import FullPage from '@js/common/layouts/full-page/FullPage.vue'
import Error404 from '@js/common/pages/Error404.vue'
import UserIndex from '@js/common/views/users/UserIndex'
import UserCreateEdit from '@js/common/views/users/UserCreateEdit'
import Settings from '@js/admin/views/settings/Settings'

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
            path: '/admin/',
            component: Main,
            children: [
                // =============================================================================
                // Theme Routes
                // =============================================================================
                {
                    path: '',
                    name: 'users',
                    component: UserIndex,
                    meta: {
                        rule: new AclRule('operator').or('admin').generate()
                    },
                },
                {
                    path: 'transactions',
                    name: 'transactions',
                    component: Transactions,
                    meta: {
                        rule: new AclRule('operator').or('admin').generate()
                    },
                },
                {
                    path: 'transactions/user/:userId',
                    name: 'transactions-user',
                    component: Transactions,
                    meta: {
                        rule: new AclRule('operator').or('admin').generate()
                    },
                },
                {
                    path: 'user/:userId/transaction/create',
                    name: 'transaction-create',
                    component: TransactionCreateEdit,
                    meta: {
                        rule: new AclRule('operator').or('admin').generate()
                    },
                },
                {
                    path: 'transaction/:id',
                    name: 'transaction-edit',
                    component: TransactionCreateEdit,
                    meta: {
                        rule: new AclRule('operator').or('admin').generate()
                    },
                },
                {
                    path: 'accounts',
                    name: 'accounts',
                    component: BankAccounts,
                    meta: {
                        rule: new AclRule('operator').or('admin').generate()
                    },
                },
                {
                    path: 'user/:userId/accounts',
                    name: 'user-accounts',
                    component: BankAccounts,
                    props: true,
                    meta: {
                        rule: new AclRule('operator').or('admin').generate()
                    },
                },
                {
                    path: 'user/:userId/account/create',
                    name: 'account-create',
                    component: AccountCreateEdit,
                    meta: {
                        rule: new AclRule('operator').or('admin').generate()
                    },
                },
                {
                    path: 'user/:userId/account/edit/:id',
                    name: 'account-edit',
                    component: AccountCreateEdit,
                    props: {
                        edit: true
                    },
                    meta: {
                        rule: new AclRule('operator').or('admin').generate()
                    },
                },
                {
                    path: 'user/create',
                    name: 'user-create',
                    component: UserCreateEdit,
                    meta: {
                        rule: new AclRule('operator').or('admin').generate()
                    },
                },
                {
                    path: 'user/:id',
                    name: 'user-edit',
                    component: UserCreateEdit,
                    meta: {
                        rule: new AclRule('operator').or('admin').generate()
                    },
                },
                {
                    path: 'settings',
                    name: 'settings',
                    component: Settings,
                    meta: {
                        rule: new AclRule('admin').generate()
                    },
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
                    meta: {
                        rule: new AclRule('operator').or('admin').generate()
                    },
                },
            ]
        },
        // Redirect to 404 page, if no match found
        {
            path: '*',
            redirect: '/404',
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
