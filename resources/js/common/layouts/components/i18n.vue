<template>
    <vs-dropdown vs-custom-content vs-trigger-click class="cursor-pointer px-2">
    <span class="cursor-pointer flex items-center i18n-locale">
      <span class="sm:block ml-2">{{ getCurrentLocaleData.lang }}</span>
        <vs-icon class="" icon="expand_more"></vs-icon>
    </span>
        <vs-dropdown-menu class="w-48 i18n-dropdown vx-navbar-dropdown">
            <vs-dropdown-item v-for="locale in locales" :key="locale" v-on:click="updateLocale(locale)">{{ locale.toUpperCase() }}</vs-dropdown-item>
        </vs-dropdown-menu>
    </vs-dropdown>
</template>

<script>
    export default {
        data() {
            return {
                locales: [
                    'ru',
                    'lv',
                    'en'
                ]
            }
        },
        computed: {
            getCurrentLocaleData() {
                const locale = this.$i18n.locale;
                if (locale == "en")      return { flag: "us", lang: 'EN'    }
                else if (locale == "ru") return { flag: "de", lang: 'RU'     }
                else if (locale == "lv") return { flag: "de", lang: 'LV'     }
            },
        },
        methods: {
            updateLocale(locale) {
                this.$i18n.locale = locale;
            },
        }
    }
</script>
