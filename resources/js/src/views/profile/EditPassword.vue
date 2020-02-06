<template>
    <div id="password-change">
        <vx-card title="Сменить пароль"
                 class="mb-base vs-con-loading__container"
                 id="user-password-edit-card">

            <div class="vx-row">
                <div class="vx-col w-1/3">
                    <div class="vx-col mt-5">
                        <vs-input label="Текущий пароль" name="old_password"
                                  type="password"
                                  v-validate="{required: true, max: 30}"
                                  v-model="oldPassword" class="w-full"/>
                        <span class="text-danger text-sm" v-show="errors.has('old_password')">
                            {{ errors.first('old_password') }}
                        </span>
                    </div>
                    <div class="vx-col mt-5">
                        <vs-input label="Новый пароль" name="new_password"
                                  type="password"
                                  v-validate="{required: true, max: 30}"
                                  v-model="newPassword" class="w-full"/>
                        <span class="text-danger text-sm" v-show="errors.has('new_password')">
                            {{ errors.first('new_password') }}
                        </span>
                    </div>
                    <div class="vx-col mt-5">
                        <vs-input label="Новый пароль повторно" name="new_password_repeat"
                                  type="password"
                                  v-validate="{required: true, max: 30, is: newPassword}"
                                  v-model="newPasswordRepeat" class="w-full"/>
                        <span class="text-danger text-sm" v-show="errors.has('new_password_repeat')">
                            {{ errors.first('new_password_repeat') }}
                        </span>
                    </div>
                </div>

                <div class="vx-col w-full flex mt-5" id="account-manage-buttons">
                    <vs-button icon-pack="feather"
                               icon="icon-edit"
                               v-on:click="submitForm"
                               class="mr-4">
                        Сохранить
                    </vs-button>
                </div>

            </div>

        </vx-card>
    </div>
</template>

<script>
    import {Validator} from 'vee-validate'

    const dict = {
        custom: {
            old_password: {
                required: 'Поле обязательно для ввода',
            },
            new_password: {
                regex  : 'Поле может содержать только буквы, пробел " ", "-"',
                required: 'Поле обязательно для ввода',
            },
            new_password_repeat: {
                regex  : 'Поле может содержать только буквы, пробел " ", "-"',
                required: 'Поле обязательно для ввода',
                is: 'Пароли не совпадают'
            },
        }
    }

    Validator.localize('en', dict);
    Validator.localize('ru', dict);

    export default {
        name: 'EditPassword',
        data() {
            return {
                oldPassword: '',
                newPassword: '',
                newPasswordRepeat: '',
                validForm: false,
                isLoading: false,
            }
        },
        methods: {
            toggleLoader() {
                this.isLoading ?
                    this.$vs.loading.close('#user-password-edit-card > .con-vs-loading') :
                    this.$vs.loading({container: '#user-password-edit-card'})

                this.isLoading = !this.isLoading
            },
            submitForm() {
                this.$validator
                    .validateAll()
                    .then(result => result ? (this.validForm = true, this.savePassword()) : this.validForm = false)
            },
            savePassword() {
                this.toggleLoader()
                this.$store.dispatch('savePassword', {
                    oldPassword: this.oldPassword,
                    newPassword: this.newPassword
                })
                .then((r) => {
                    console.log(r)
                    this.toggleLoader()
                    this.$vs.notify({
                        title   : 'Сохранено',
                        text    : 'Пароль пользвателя успешно обновлен',
                        color   : 'success',
                        position: 'top-right'
                    })
                })
                .catch((e) => {
                    console.log(e)
                    this.toggleLoader()
                    this.$vs.notify({
                        title   : 'Ошибка',
                        text    : 'Произошла ошибка. Пароль не был обновлен',
                        color   : 'danger',
                        position: 'top-right'
                    })
                })
            }
        }
    }
</script>

<style scoped>

</style>
