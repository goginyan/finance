<template>
    <div id="app" class="layout--full-page">
        <div class="h-screen flex w-full bg-img vx-row no-gutter items-center justify-center" id="page-login" v-on:keyup.enter="login">
            <div class="vx-col sm:w-1/2 md:w-1/2 lg:w-3/4 xl:w-3/5 sm:m-0 m-4">
                <vx-card class="max-w-md mx-auto">
                    <div slot="no-body" class="">

                        <div class="vx-row no-gutter justify-center items-center py-4">
                            <img src="../../assets/images/logo/zbk_logo.png" alt="">
                        </div>
                        <div class="vx-row no-gutter justify-center items-center">
                            <div class="vx-card__title mb-4">
                                <h1 class="m-4">Добро пожаловать в ZBK Bank</h1>
                            </div>
                            <div class="vx-col d-theme-dark-bg">
                                <div class="p-8 login-tabs-container">
                                    <form id="login-form" method="post" action="/login">
                                        <vs-input
                                            name="email"
                                            icon-no-border
                                            icon="icon icon-user"
                                            icon-pack="feather"
                                            label-placeholder="Email"
                                            v-model="email"
                                            class="w-full"/>

                                        <vs-input
                                            type="password"
                                            name="password"
                                            icon-no-border
                                            icon="icon icon-lock"
                                            icon-pack="feather"
                                            label-placeholder="Пароль"
                                            v-model="password"
                                            class="w-full mt-6"/>

                                        <input type="hidden" name="_token" :value="csrfToken">

                                        <!--      <div class="flex flex-wrap justify-between my-5">-->
                                        <!--          <router-link to=''>Forgot Password?</router-link>-->
                                        <!--      </div>-->
                                        <vs-button class="float-right my-5" v-on:click="login">Login</vs-button>

                                    </form>
                                </div>
                                <div class="pt-4"></div>
                                <div class="flex flex-col p-8">
                                    <a href="mailto:bank@zbkbank.com"><span class="flex items-center"><MailIcon /> <span class="ml-2">bank@zbkbank.com</span></span></a>
                                    <a href="mailto:aml@zbkbank.com"><span class="flex items-cnter"><MailIcon /> <span class="ml-2">aml@zbkbank.com</span></span></a>
                                </div>
                            </div>

                        </div>


                    </div>
                </vx-card>
            </div>
        </div>
    </div>
</template>
<script>
    import { MailIcon } from 'vue-feather-icons'

    export default {
        data() {
            return {
                email: '',
                password: '',
                loginForm: '',
                csrfToken: '',
            }
        },
        components:{MailIcon},
        methods: {
            login() {
                this.loginForm.submit()
            },
        },
        mounted() {
            this.loginForm = document.getElementById('login-form')
            this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }
</script>

<style>
    img {
        max-width: 200px;
    }
</style>
