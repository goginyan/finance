import Axios from 'axios';

const actions = {

    // /////////////////////////////////////////////
    // COMPONENTS
    // /////////////////////////////////////////////

    // Vertical NavMenu
    updateVerticalNavMenuWidth({ commit }, width) {
      commit('UPDATE_VERTICAL_NAV_MENU_WIDTH', width)
    },

    // VxAutoSuggest
    updateStarredPage({ commit }, payload) {
      commit('UPDATE_STARRED_PAGE', payload)
    },

    // The Navbar
    arrangeStarredPagesLimited({ commit }, list) {
      commit('ARRANGE_STARRED_PAGES_LIMITED', list)
    },
    arrangeStarredPagesMore({ commit }, list) {
      commit('ARRANGE_STARRED_PAGES_MORE', list)
    },

    // /////////////////////////////////////////////
    // UI
    // /////////////////////////////////////////////

    toggleContentOverlay({ commit }) {
      commit('TOGGLE_CONTENT_OVERLAY')
    },
    updateTheme({ commit }, val) {
      commit('UPDATE_THEME', val)
    },

    // /////////////////////////////////////////////
    // User/Account
    // /////////////////////////////////////////////

    updateUserInfo({ commit }, payload) {
      commit('UPDATE_USER_INFO', payload)
    },

    setUser({commit}, payload) {
        return new Promise((resolve, reject) => {
            Axios.get('/api/user')
                .then( r => {
                    let user =  r.data.data.user
                    commit('UPDATE_USER', user)
                    let role = user.is_admin === true ? 'admin' : 'operator'
                    payload.aclChangeRole(role)
                    resolve()
                })
                .catch(e => {
                    reject()
                    window.location.href = '/logout'
                })
        })
    },

    getUsers({commit}) {
        return new Promise((resolve, reject) => {
            Axios.get('/api/admin/users')
            .then( r => {
                commit('UPDATE_USERS', r.data.data.users)
                resolve()
            })
            .catch(e => {
                reject()
                // window.location.href = '/logout'
            })
        })
    },

    getTransactions({commit}) {
        Axios.get('/api/admin/transactions')
            .then((r) => {
                commit('UPDATE_TRANSACTIONS', r.data.data.transactions)
            })
            .catch(e => console.log(e))
    },

    updateTransactionUser({commit},userId){
        Axios.get(`/api/user/${userId}/transactions`)
            .then(r => {
                commit('UPDATE_TRANSACTIONS_USER', r.data.data.transactions)
            })
            .catch(e => console.log(e))
    },
    createTransaction({commit}, transaction) {
        return new Promise((resolve, reject) => {
            Axios.post('/api/admin/transactions/', {
                transaction   : transaction,
            })
                .then(r => resolve(r.data.data.transaction))
                .catch(e => reject())
        })
    },

    updateTransaction({commit}, transaction) {
        return new Promise((resolve, reject) => {
            Axios.patch('/api/admin/transactions/' + transaction.id, {
                transaction   : transaction,
            })
                .then(r => resolve(r))
                .catch(e => reject(e))
        })
    },

    getAccounts({commit}) {
        Axios.get('/api/admin/accounts')
            .then((r) => {
                commit('UPDATE_ACCOUNTS', r.data.data.accounts)
            })
            .catch(e => console.log(e))
    },

    createAccount({commit}, account) {
        return new Promise((resolve, reject) => {
            Axios.post('/api/admin/accounts', {
                account: account
            })
                .then(r => resolve(r))
                .catch(e => reject(e))
        })
    },

    saveUser({commit}, user) {
        return new Promise((resolve, reject) => {
            Axios.patch('/api/admin/users/' + user.id, {
                user   : user,
            })
                .then(r => resolve(r))
                .catch(e => reject(e))
        })
    },

    createUser({commit}, user) {
        return new Promise((resolve, reject) => {
            Axios.post('/api/admin/users/', {
                user   : user,
            })
                .then(r => resolve(r))
                .catch(e => reject(e))
        })
    },

    fetchCommissions({commit}) {
        return new Promise((resolve,reject) => {
            Axios.get('/api/admin/commissions')
                .then(r => {
                    // commit('UPDATE_COMMISSIONS', r.data.data.commissions)
                    resolve(r)
                })
                .catch(e => reject(e))
        })
    },

    saveCommissions({commit}, commissions) {
        return new Promise((resolve, reject) => {
            Axios.patch('/api/admin/commissions', {
                commissions: commissions
            })
                .then(r => {
                    // commit('UPDATE_COMMISSIONS', r.data.data.commissions)
                    resolve(r)
                })
                .catch(e => {
                    reject(e)
                })
        })
    }
}

export default actions
