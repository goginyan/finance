<template>
  <div class="layout--main" :class="[layoutTypeClass, navbarClasses, footerClasses, {'app-page': isAppPage}]">
    <v-nav-menu
      :navMenuItems = "navMenuItems"
      :logo         = "navMenuLogo"
      title         = "Vuexy"
      parent        = ".layout--main" />

    <div id="content-area" :class="[contentAreaClass, {'show-overlay': bodyOverlay}]">
      <div id="content-overlay" />

    <!-- Navbar -->
    <template v-if="mainLayoutType === 'horizontal' && windowWidth >= 1200">
      <the-navbar-horizontal
        :navbarType= "navbarType"
        :logo= "navMenuLogo"
        :class="[
          {'text-white' : isNavbarDark  && !isThemeDark},
          {'text-base'  : !isNavbarDark && isThemeDark}
        ]" />

      <div style="height: 62px" v-if="navbarType === 'static'"></div>

      <h-nav-menu
        :class="[
          {'text-white' : isNavbarDark  && !isThemeDark},
          {'text-base'  : !isNavbarDark && isThemeDark}
        ]"
        :navMenuItems="navMenuItems" />
    </template>

    <template v-else>
      <the-navbar-vertical
        :navbarColor="navbarColor"
        :class="[
          {'text-white' : isNavbarDark  && !isThemeDark},
          {'text-base'  : !isNavbarDark && isThemeDark}
        ]" />
    </template>
    <!-- /Navbar -->

      <div class="content-wrapper">

        <div class="router-view">
          <div class="router-content">

            <transition :name="routerTransition">

              <div v-if="$route.meta.breadcrumb || $route.meta.pageTitle" class="router-header flex flex-wrap items-center mb-6">
                <div
                  class="content-area__heading"
                  :class="{'pr-4 border-0 md:border-r border-solid border-grey-light' : $route.meta.breadcrumb}">
                  <h2 class="mb-1">{{ routeTitle }}</h2>
                </div>

                <!-- BREADCRUMB -->
                <vx-breadcrumb class="ml-4 md:block hidden" v-if="$route.meta.breadcrumb" :route="$route" />

                <!-- DROPDOWN -->
                <vs-dropdown vs-trigger-click class="ml-auto md:block hidden cursor-pointer">
                  <vs-button radius icon="icon-settings" icon-pack="feather" />

                  <vs-dropdown-menu class="w-32">
                    <vs-dropdown-item>
                      <div @click="$router.push('/pages/profile').catch(() => {})" class="flex items-center">
                        <feather-icon icon="UserIcon" class="inline-block mr-2" svgClasses="w-4 h-4" />
                        <span>Profile</span>
                      </div>
                    </vs-dropdown-item>
                    <vs-dropdown-item>
                      <div @click="$router.push('/apps/todo').catch(() => {})" class="flex items-center">
                        <feather-icon icon="CheckSquareIcon" class="inline-block mr-2" svgClasses="w-4 h-4" />
                        <span>Tasks</span>
                      </div>
                    </vs-dropdown-item>
                    <vs-dropdown-item>
                      <div @click="$router.push('/apps/email').catch(() => {})" class="flex items-center">
                        <feather-icon icon="MailIcon" class="inline-block mr-2" svgClasses="w-4 h-4" />
                        <span>Inbox</span>
                      </div>
                    </vs-dropdown-item>
                  </vs-dropdown-menu>

                </vs-dropdown>

              </div>
            </transition>

            <div class="content-area__content">

              <back-to-top bottom="5%" visibleoffset="500" v-if="!hideScrollToTop">
                <vs-button icon-pack="feather" icon="icon-arrow-up" class="shadow-lg btn-back-to-top" />
              </back-to-top>

              <transition :name="routerTransition" mode="out-in">
                <router-view @changeRouteTitle="changeRouteTitle"></router-view>
              </transition>
            </div>
          </div>
        </div>
      </div>
      <the-footer />
    </div>
  </div>
</template>


<script>
import BackToTop           from 'vue-backtotop'
import navMenuItems        from "@admin/navMenuItems.js"
import TheNavbarVertical   from '@js/common/layouts/components/navbar/TheNavbarVertical.vue'
import TheFooter           from '@js/common/layouts/components/TheFooter.vue'
import themeConfig         from '@/../themeConfig.js'
import VNavMenu            from '@js/common/layouts/components/vertical-nav-menu/VerticalNavMenu.vue'

export default {
    components: {
        BackToTop,
        TheFooter,
        // TheNavbarHorizontal,
        TheNavbarVertical,
        VNavMenu
    },
    data() {
        return {
            disableCustomizer: themeConfig.disableCustomizer,
            disableThemeTour : themeConfig.disableThemeTour,
            footerType       : themeConfig.footerType || 'static',
            hideScrollToTop  : themeConfig.hideScrollToTop,
            isNavbarDark     : false,
            navbarColor      : themeConfig.navbarColor || '#fff',
            navbarType       : themeConfig.navbarType || 'floating',
            navMenuItems     : navMenuItems,
            navMenuLogo      : require('@assets/images/logo/zbk_logo.png'),
            routerTransition : themeConfig.routerTransition || 'none',
            routeTitle       : this.$route.meta.pageTitle,
        }
    },
    watch     : {
        "$route"() {
            this.routeTitle = this.$route.meta.pageTitle
        },
        isThemeDark(val) {
            const color = this.navbarColor == "#fff" && val ? "#10163a" : "#fff"
            this.updateNavbarColor(color)
        },
        "$store.state.mainLayoutType"(val) {
            this.setNavMenuVisibility(val)
            this.disableThemeTour = true
        },
        windowWidth(val) {
            if (val < 1200) this.disableThemeTour = true
        },
        verticalNavMenuWidth() {
            this.disableThemeTour = true
        }
    },
    computed  : {
        bodyOverlay() {
            return this.$store.state.bodyOverlay
        },
        contentAreaClass() {
            if (this.mainLayoutType === "vertical") {
                if (this.verticalNavMenuWidth == "default") return "content-area-reduced"
                else if (this.verticalNavMenuWidth == "reduced") return "content-area-lg"
            }
            // else if(this.mainLayoutType === "boxed") return "content-area-reduced"
            else return "content-area-full"
        },
        footerClasses() {
            return {
                'footer-hidden': this.footerType == 'hidden',
                'footer-sticky': this.footerType == 'sticky',
                'footer-static': this.footerType == 'static',
            }
        },
        isAppPage() {
            return this.$route.path.includes('/apps/') ? true : false
        },
        isThemeDark() {
            return this.$store.state.theme == 'dark'
        },
        layoutTypeClass() {
            return `main-${this.mainLayoutType}`
        },
        mainLayoutType() {
            return this.$store.state.mainLayoutType
        },
        navbarClasses() {
            return {
                'navbar-hidden'  : this.navbarType == 'hidden',
                'navbar-sticky'  : this.navbarType == 'sticky',
                'navbar-static'  : this.navbarType == 'static',
                'navbar-floating': this.navbarType == 'floating',
            }
        },
        verticalNavMenuWidth() {
            return this.$store.state.verticalNavMenuWidth
        },
        windowWidth() {
            return this.$store.state.windowWidth
        }
    },
    methods   : {
        changeRouteTitle(title) {
            this.routeTitle = title
        },
        updateNavbar(val) {
            if (val == "static") this.updateNavbarColor("#fff")
            this.navbarType = val
        },
        updateNavbarColor(val) {
            this.navbarColor = val
            if (val == "#fff") this.isNavbarDark = false
            else this.isNavbarDark = true
        },
        updateFooter(val) {
            this.footerType = val
        },
        updateRouterTransition(val) {
            this.routerTransition = val
        },
        setNavMenuVisibility(layoutType) {
            if ((layoutType === 'horizontal' && this.windowWidth >= 1200) || (layoutType === "vertical" && this.windowWidth < 1200)) {
                this.$store.commit('TOGGLE_IS_VERTICAL_NAV_MENU_ACTIVE', false)
                this.$store.dispatch('updateVerticalNavMenuWidth', 'no-nav-menu')
            } else {
                this.$store.commit('TOGGLE_IS_VERTICAL_NAV_MENU_ACTIVE', true)
            }
        },
        toggleHideScrollToTop(val) {
            this.hideScrollToTop = val
        }
    },
    created() {
        const color = this.navbarColor == "#fff" && this.isThemeDark ? "#10163a" : this.navbarColor
        this.updateNavbarColor(color)
        this.setNavMenuVisibility(this.$store.state.mainLayoutType)
        if (this.$acl.check('admin') == false) {
            _.remove(this.navMenuItems, (item) => item.slug === 'settings')
        }
    }
}

</script>

