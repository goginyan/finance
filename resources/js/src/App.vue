<template>
    <div id="app">
        <router-view v-if="this.isLoading === false"></router-view>
    </div>
</template>

<script>
    import themeConfig from '../themeConfig.js'

    export default {
        watch: {
            '$store.state.theme'(val) {
                this.toggleClassInBody(val)
            }
        },
        data() {
            return {
                isLoading: false
            }
        },
        methods: {
            toggleClassInBody(className) {
                if (className == 'dark') {
                    if (document.body.className.match('theme-semi-dark')) document.body.classList.remove('theme-semi-dark')
                    document.body.classList.add('theme-dark')
                } else if (className == 'semi-dark') {
                    if (document.body.className.match('theme-dark')) document.body.classList.remove('theme-dark')
                    document.body.classList.add('theme-semi-dark')
                } else {
                    if (document.body.className.match('theme-dark')) document.body.classList.remove('theme-dark')
                    if (document.body.className.match('theme-semi-dark')) document.body.classList.remove('theme-semi-dark')
                }
            },
            handleWindowResize() {
                this.$store.commit('UPDATE_WINDOW_WIDTH', window.innerWidth)
            },
            handleScroll() {
                this.$store.commit('UPDATE_WINDOW_SCROLL_Y', window.scrollY)
            },
        },
        beforeCreate() {
            // this.$vs.loading({background:'#fff'})
            //             this.$vs.loading.close()
            this.$store.dispatch('setUser')
            this.$store.dispatch('setTransactions')
            this.$store.dispatch('setAccounts')
        },
        mounted() {
            this.toggleClassInBody(themeConfig.theme)
            this.$store.commit('UPDATE_WINDOW_WIDTH', window.innerWidth)
        },
        created() {
            window.addEventListener('resize', this.handleWindowResize)
            window.addEventListener('scroll', this.handleScroll)
        },
        destroyed() {
            window.removeEventListener('resize', this.handleWindowResize)
            window.removeEventListener('scroll', this.handleScroll)
        },
    }

</script>
