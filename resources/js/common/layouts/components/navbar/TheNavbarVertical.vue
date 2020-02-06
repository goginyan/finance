<template>
    <div class="relative">
        <div class="vx-navbar-wrapper" :class="classObj">
            <vs-navbar class="vx-navbar navbar-custom navbar-skelton" :color="navbarColorLocal">

                <!-- SM - OPEN SIDEBAR BUTTON -->
                <feather-icon class="sm:inline-flex xl:hidden cursor-pointer mr-1" icon="MenuIcon"
                              @click.stop="showSidebar"></feather-icon>

                <template v-if="windowWidth >= 992">
                    <!-- STARRED PAGES - FIRST 10 -->
                    <ul class="vx-navbar__starred-pages">
                        <draggable v-model="starredPagesLimited" :group="{name: 'pinList'}" class="flex cursor-move">
                            <li class="starred-page" v-for="page in starredPagesLimited" :key="page.url">
                                <vx-tooltip :text="page.label" position="bottom" delay=".3s">
                                    <feather-icon svgClasses="h-6 w-6" class="p-2 cursor-pointer" :icon="page.labelIcon"
                                                  @click="$router.push(page.url).catch(() => {})"/>
                                </vx-tooltip>
                            </li>
                        </draggable>
                    </ul>
                </template>


                <vs-spacer/>

                <i18n />

                <!-- NOTIFICATIONS -->
                <vs-dropdown vs-custom-content vs-trigger-click class="cursor-pointer">
                    <feather-icon icon="BellIcon" class="cursor-pointer mt-1 sm:mr-6 mr-2"
                                  :badge="unreadNotifications.length"></feather-icon>
                    <vs-dropdown-menu class="notification-dropdown dropdown-custom vx-navbar-dropdown">

                        <div class="notification-top text-center p-5 bg-primary text-white">
                            <h3 class="text-white">{{ unreadNotifications.length }} новых</h3>
                            <p class="opacity-75">Уведомлениый</p>
                        </div>

                        <VuePerfectScrollbar ref="mainSidebarPs" class="scroll-area--nofications-dropdown p-0 mb-10"
                                             :settings="settings">
                            <ul class="bordered-items">
                                <li v-for="ntf in unreadNotifications" :key="ntf.index"
                                    class="flex justify-between px-4 py-4 notification cursor-pointer">
                                    <div class="flex items-start">
                                        <feather-icon :icon="ntf.icon"
                                                      :svgClasses="[`text-${ntf.category}`, 'stroke-current mr-1 h-6 w-6']"></feather-icon>
                                        <div class="mx-2">
                                            <span class="font-medium block notification-title"
                                                  :class="[`text-${ntf.category}`]">{{ ntf.title }}</span>
                                            <small>{{ ntf.msg }}</small>
                                        </div>
                                    </div>
                                    <small class="mt-1 whitespace-no-wrap">{{ elapsedTime(ntf.time) }}</small>
                                </li>
                            </ul>
                        </VuePerfectScrollbar>
                        <div class="
                        checkout-footer
                        fixed
                        bottom-0
                        rounded-b-lg
                        text-primary
                        w-full
                        p-2
                        font-semibold
                        text-center
                        border
                        border-b-0
                        border-l-0
                        border-r-0
                        border-solid
                        d-theme-border-grey-light
                        cursor-pointer">
                            <span>Все уведомления</span>
                        </div>
                    </vs-dropdown-menu>
                </vs-dropdown>

                <!-- USER META -->
                <div class="the-navbar__user-meta flex items-center" v-if="activeUserInfo">
                    <div class="text-right leading-tight hidden sm:block">
                        <p class="font-semibold">{{ user_displayName }}</p>
                        <span
                            class="flex pt-1 text-sm cursor-pointer"
                            @click="logout">
                    <feather-icon icon="LogOutIcon" svgClasses="w-4 h-4"/>
                    <span class="ml-1">Выйти</span>
                </span>
                    </div>
                </div>

            </vs-navbar>
        </div>
    </div>
</template>

<script>
    import VxAutoSuggest from '../../../../common/components/vx-auto-suggest/VxAutoSuggest.vue';
    import VuePerfectScrollbar from 'vue-perfect-scrollbar'
    import draggable from 'vuedraggable'
    import i18n from "../i18n";

    export default {
        name: "the-navbar",
        components: {
            VxAutoSuggest,
            VuePerfectScrollbar,
            draggable,
            i18n
        },
        props: {
            navbarColor: {
                type: String,
                default: "#fff",
            },
        },
        data() {
            return {
                unreadNotifications: [
                    // { index: 0, title: 'New Message', msg: 'Are your going to meet me tonight?', icon: 'MessageSquareIcon', time: this.randomDate({sec: 10}), category: 'primary' },
                    // { index: 1, title: 'New Order Recieved', msg: 'You got new order of goods.', icon: 'PackageIcon', time: this.randomDate({sec: 40}), category: 'success' },
                    // { index: 2, title: 'Server Limit Reached!', msg: 'Server have 99% CPU usage.', icon: 'AlertOctagonIcon', time: this.randomDate({min: 1}), category: 'danger' },
                    // { index: 3, title: 'New Mail From Peter', msg: 'Cake sesame snaps cupcake', icon: 'MailIcon', time: this.randomDate({min: 6}), category: 'primary' },
                    // { index: 4, title: 'Bruce\'s Party', msg: 'Chocolate cake oat cake tiramisu', icon: 'CalendarIcon', time: this.randomDate({hr: 2}), category: 'warning' },
                    // { index: 5, title: 'Bruce\'s Party', msg: 'Chocolate cake oat cake tiramisu', icon: 'CalendarIcon', time: this.randomDate({hr: 2}), category: 'warning' },
                    // { index: 6, title: 'Bruce\'s Party', msg: 'Chocolate cake oat cake tiramisu', icon: 'CalendarIcon', time: this.randomDate({hr: 2}), category: 'warning' },
                    // { index: 7, title: 'Bruce\'s Party', msg: 'Chocolate cake oat cake tiramisu', icon: 'CalendarIcon', time: this.randomDate({hr: 2}), category: 'warning' },
                    // { index: 8, title: 'Bruce\'s Party', msg: 'Chocolate cake oat cake tiramisu', icon: 'CalendarIcon', time: this.randomDate({hr: 2}), category: 'warning' },
                ],
                settings: {
                    maxScrollbarLength: 60,
                    wheelSpeed: .60,
                },
            }
        },
        watch: {
            '$route'() {
                if (this.showBookmarkPagesDropdown) this.showBookmarkPagesDropdown = false
            }
        },
        computed: {
            // navbarSearchAndPinList() {
            //     return {
            //         data: this.$store.state.transactions
            //     }
            // },
            navbarColorLocal() {
                return this.$store.state.theme === "dark" ? "#10163a" : this.navbarColor
            },
            // HELPER
            verticalNavMenuWidth() {
                return this.$store.state.verticalNavMenuWidth
            },
            windowWidth() {
                return this.$store.state.windowWidth
            },

            // NAVBAR STYLE
            classObj() {
                if (this.verticalNavMenuWidth == "default") return "navbar-default"
                else if (this.verticalNavMenuWidth == "reduced") return "navbar-reduced"
                else if (this.verticalNavMenuWidth) return "navbar-full"
            },

            // BOOKMARK & SEARCH
            data() {
                return this.$store.state.navbarSearchAndPinList;
            },
            starredPages() {
                return this.$store.state.starredPages;
            },
            starredPagesLimited: {
                get() {
                    return this.starredPages.slice(0, 10);
                },
                set(list) {
                    this.$store.dispatch('arrangeStarredPagesLimited', list);
                }
            },
            starredPagesMore: {
                get() {
                    return this.starredPages.slice(10);
                },
                set(list) {
                    this.$store.dispatch('arrangeStarredPagesMore', list);
                }
            },

            // PROFILE
            activeUserInfo() {
                return this.$store.state.user
            },
            user_displayName() {
                if (this.activeUserInfo.user_type === 1) {
                    return `${this.activeUserInfo.name} ${this.activeUserInfo.last_name}`
                } else {
                    return this.details.company_name
                }
            },
        },
        methods: {
            showSidebar() {
                this.$store.commit('TOGGLE_IS_VERTICAL_NAV_MENU_ACTIVE', true);
            },
            // selected(item) {
            //     this.$router.push(item.url).catch(() => {
            //     })
            //     // this.showFullSearch = false;
            // },
            actionClicked(item) {
                // e.stopPropogation();
                this.$store.dispatch('updateStarredPage', {index: item.index, val: !item.highlightAction});
            },
            // showNavbarSearch() {
            //     // this.showFullSearch = true;
            // },
            // showSearchbar() {
            //     // this.showFullSearch = true;
            // },
            elapsedTime(startTime) {
                let x = new Date(startTime);
                let now = new Date();
                var timeDiff = now - x;
                timeDiff /= 1000;

                var seconds = Math.round(timeDiff);
                timeDiff = Math.floor(timeDiff / 60);

                var minutes = Math.round(timeDiff % 60);
                timeDiff = Math.floor(timeDiff / 60);

                var hours = Math.round(timeDiff % 24);
                timeDiff = Math.floor(timeDiff / 24);

                var days = Math.round(timeDiff % 365);
                timeDiff = Math.floor(timeDiff / 365);

                var years = timeDiff;

                if (years > 0) {
                    return years + (years > 1 ? ' Years ' : ' Year ') + 'ago';
                } else if (days > 0) {
                    return days + (days > 1 ? ' Days ' : ' Day ') + 'ago';
                } else if (hours > 0) {
                    return hours + (hours > 1 ? ' Hrs ' : ' Hour ') + 'ago';
                } else if (minutes > 0) {
                    return minutes + (minutes > 1 ? ' Mins ' : ' Min ') + 'ago';
                } else if (seconds > 0) {
                    return seconds + (seconds > 1 ? ' sec ago' : 'just now');
                }

                return 'Just Now'
            },
            logout() {
                window.location.href = '/logout'
            },
            outside: function () {
                this.showBookmarkPagesDropdown = false
            },

            // Method for creating dummy notification time
            randomDate({hr, min, sec}) {
                let date = new Date()

                if (hr) date.setHours(date.getHours() - hr)
                if (min) date.setMinutes(date.getMinutes() - min)
                if (sec) date.setSeconds(date.getSeconds() - sec)

                return date
            }
        },
        directives: {
            'click-outside': {
                bind: function (el, binding) {
                    const bubble = binding.modifiers.bubble
                    const handler = (e) => {
                        if (bubble || (!el.contains(e.target) && el !== e.target)) {
                            binding.value(e)
                        }
                    }
                    el.__vueClickOutside__ = handler
                    document.addEventListener('click', handler)
                },

                unbind: function (el) {
                    document.removeEventListener('click', el.__vueClickOutside__)
                    el.__vueClickOutside__ = null

                }
            }
        },
    }
</script>
