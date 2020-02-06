import Axios from "axios";

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

    updateToken({ commit }, token) {
        commit('UPDATE_TOKEN', token)
    },

    setUser({commit}) {
        Axios.get('/api/user')
            .then(r => commit('UPDATE_USER', r.data.data.user))
            .catch(e => console.log(e))
    },

    setTransactions({commit}) {
        Axios.get('/api/user/transactions')
            .then(r => commit('UPDATE_TRANSACTIONS', r.data.data.transactions))
            .catch(e => console.log(e))
    },

    setAccounts({commit}) {
        Axios.get('/api/user/accounts')
            .then(r => commit('UPDATE_ACCOUNTS', r.data.data.accounts))
            .catch(e => console.log(e))
    },

    savePassword({commit}, payload) {
        return new Promise((resolve, reject) => {
            Axios.patch('/api/user/password', {
                oldPassword: payload.oldPassword,
                newPassword: payload.newPassword,
            })
                .then(r => resolve(r))
                .catch(e => reject(e))
        })
    },

    updateDetails: function ({commit, getters}, user) {
        return new Promise((resolve, reject) => {
            Axios.post('/api/user', {
                user: user
            })
                .then((r) => {
                    commit('UPDATE_USER', r.data.data.user)
                    resolve()
                })
                .catch((e) => {
                    console.log(e)
                    reject()
                })
        })

    },

    sendTransaction({commit, getters, dispatch}, transaction) {
        transaction.api_token = getters.apiToken
        Axios.post('/api/transaction', transaction)
            .then((r) => {
                dispatch('setTransactions')
                dispatch('setAccounts')
            })
            .catch(e => console.log(e))
    },

}

export default actions
