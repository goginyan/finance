<template>
    <div>
        <div class="flex" v-if="isEdit">
            <vs-button type="border"
                       icon-pack="feather"
                       icon="icon-plus"
                       class="my-2 mr-2"
                       v-on:click="addTransaction">
                Платеж
            </vs-button>
            <vs-button type="border"
                       icon-pack="feather"
                       icon="icon-plus"
                       class="my-2 mr-2"
                       v-on:click="addAccount">
                Счет
            </vs-button>

            <vs-button type="border"
                       icon-pack="feather"
                       icon="icon-list"
                       class="my-2 "
                       v-on:click="getTransaction">
                Платежи
            </vs-button>
        </div>
        <vx-card :title="isEdit ? 'Редактировать пользователя' : 'Создать пользователя'"
                 id="user-create-edit-card"
                 class="mb-base vs-con-loading__container">
            <div class="vx-row">
                <div class="vx-col w-full">
                    <h5 class="mb-4">Тип пользователя</h5>
                    <vs-radio v-model="user.user_type" vs-value="1" class="mr-2">
                        Физическое лицо
                    </vs-radio>
                    <vs-radio v-model="user.user_type" vs-value="2">
                        Юридическое лицо
                    </vs-radio>
                </div>
                <div class="vx-col md:w-1/2 w-full">

                    <vs-input class="w-full mt-4" v-if="!isPrivateUser" label="Название компании" v-model="user.details.company_name" name="company_name" v-validate="{required: true, max: 30, regex: /^([A-я \-0-9])+$/}"/>
                    <span class="text-danger text-sm" v-show="errors.has('company_name')">{{ errors.first('company_name') }}</span>

                    <vs-input class="w-full mt-4" v-if="!isPrivateUser" label="Регистрационный номер компании" v-model="user.details.company_reg_number" name="company_reg_number" v-validate="{required: true, max: 30, regex: /^([A-я \-0-9])+$/}"/>
                    <span class="text-danger text-sm" v-show="errors.has('company_reg_number')">{{ errors.first('company_reg_number') }}</span>

                    <vs-input class="w-full mt-4" v-if="!isPrivateUser" label="Представитель компании" v-model="user.details.company_representative" name="company_representative" v-validate="{required: true, max: 30, regex: /^([A-я \-0-9])+$/}"/>
                    <span class="text-danger text-sm" v-show="errors.has('company_representative')">{{ errors.first('company_representative') }}</span>

                    <vs-input class="w-full mt-4" label="Имя" v-model="user.name" name="name" v-validate="{required: true, max: 30, regex: /^([A-я \-0-9])+$/}"/>
                    <span class="text-danger text-sm" v-show="errors.has('name')">{{ errors.first('name') }}</span>

                    <vs-input class="w-full mt-4" label="Фамилия" v-model="user.last_name" name="last_name" v-validate="{required: true, max: 30, regex: /^([A-я \-0-9])+$/}"/>
                    <span class="text-danger text-sm" v-show="errors.has('last_name')">{{ errors.first('last_name') }}</span>

                    <vs-input class="w-full mt-4" label="Почта" v-model="user.email" type="email" name="email" v-validate="'required|email'"/>
                    <span class="text-danger text-sm" v-show="errors.has('email')">{{ errors.first('email') }}</span>

                    <vs-input class="w-full mt-4"
                              v-if="!isEdit"
                              label="Пароль"
                              v-model="user.password"
                              type="password"
                              v-validate="'required'"
                              name="password"/>
                    <span class="text-danger text-sm" v-show="errors.has('password')">{{ errors.first('password') }}</span>

                    <vs-input class="w-full mt-4"
                              label="PIN"
                              v-model="user.pin"
                              type="text"
                              v-validate="'required|digits:4'"
                              name="pin"/>
                    <span class="text-danger text-sm" v-show="errors.has('pin')">{{ errors.first('pin') }}</span>

                    <vs-input class="w-full mt-4"
                              label="Дата рождения"
                              v-model="user.details.birth_date"
                              v-validate="'required'"
                              name="birth_date"/>
                    <span class="text-danger text-sm" v-show="errors.has('birth_date')">{{ errors.first('birth_date') }}</span>

                    <vs-input class="w-full mt-4"
                              label="Номер ID документа"
                              v-model="user.details.document_id"
                              v-validate="'required'"
                              name="document_id"/>
                    <span class="text-danger text-sm" v-show="errors.has('document_id')">{{ errors.first('document_id') }}</span>

                </div>

                <div class="vx-col md:w-1/2 w-full">
                    <vs-input class="w-full mt-4"
                              label="Номер телефона"
                              v-model="user.details.phone_number"
                              v-validate="'required'"
                              name="phone_number"/>
                    <span class="text-danger text-sm" v-show="errors.has('phone_number')">{{ errors.first('phone_number') }}</span>

                    <vs-input class="w-full mt-4"
                              label="Адрес"
                              v-model="user.details.address"
                              v-validate="'required'"
                              name="address"/>
                    <span class="text-danger text-sm" v-show="errors.has('address')">{{ errors.first('address') }}</span>

                    <vs-input class="w-full mt-4"
                              label="Страна"
                              v-model="user.details.country"
                              v-validate="'required'"
                              name="country"/>
                    <span class="text-danger text-sm" v-show="errors.has('country')">{{ errors.first('country') }}</span>

                    <vs-input class="w-full mt-4"
                              label="Почтовый индекс"
                              v-model="user.details.zip_code"
                              v-validate="'required'"
                              name="zip_code"/>
                    <span class="text-danger text-sm" v-show="errors.has('zip_code')">{{ errors.first('zip_code') }}</span>

                    <div class="my-4">
                        <span>Получать информацию о актульных предложениях</span>
                        <vs-switch class="mt-2" v-model="user.details.marketing" />
                    </div>

                    <div class="my-4">
                        <span>Получать уведомления</span>
                        <vs-switch class="mt-2" v-model="user.details.notifications" />
                    </div>

                    <div class="my-4">
                        <span>Язык общения с оператором</span>
                        <vs-select class="mt-2" v-model="user.details.language">
                            <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="item,index in languages" />
                        </vs-select>
                    </div>

                </div>
            </div>
            <!-- Content Row -->

            <!-- Save & Reset Button -->
            <div class="vx-row">
                <div class="vx-col w-full">
                    <div class="mt-8 flex flex-wrap items-center">
                        <vs-button class="mt-2"
                                   v-on:click="submitForm">
                            {{ isEdit ? 'Сохранить' : 'Создать' }}
                        </vs-button>
                        <vs-button v-if="isEdit"
                                   class="ml-5 mt-2"
                                   type="border"
                                   color="danger"
                                   v-on:click="openAlert">
                           Удалить
                        </vs-button>
                    </div>
                </div>
            </div>
        </vx-card>
        <div class="vx-row mt-8">
            <vs-button v-on:click="showUserAccounts">Счета</vs-button>
        </div>
    </div>
</template>

<script>
    import Axios from 'axios'
    import {Validator} from 'vee-validate'

    const dict = {
        custom: {
            company_name: {
                max: 'Максимальная длина не должна привышать 30 символов включая пробел',
                regex  : 'Поле может содержать только буквы, пробел " ", "-"',
                required: 'Поле обязательно для ввода',
            },
            company_reg_number: {
                max: 'Максимальная длина не должна привышать 30 символов включая пробел',
                regex  : 'Поле может содержать только буквы, пробел " ", "-"',
                required: 'Поле обязательно для ввода',
            },
            company_representative: {
                max: 'Максимальная длина не должна привышать 30 символов включая пробел',
                regex  : 'Поле может содержать только буквы, пробел " ", "-"',
                required: 'Поле обязательно для ввода',
            },
            name: {
                max: 'Максимальная длина не должна привышать 30 символов включая пробел',
                regex  : 'Поле может содержать только буквы, пробел " ", "-"',
                required: 'Поле обязательно для ввода',
            },
            last_name: {
                max: 'Максимальная длина не должна привышать 30 символов включая пробел',
                regex  : 'Поле может содержать только буквы, пробел " ", "-"',
                required: 'Поле обязательно для ввода',
            },
            email: {
                email: 'Введён некорректный email адрес',
                required: 'Поле обязательно для ввода',
            },
            password: {
                required: 'Пароль обязателен для ввода'
            },
            pin: {
                required: 'PIN обязателен для ввода',
                digits  : 'PIN должен состоять из четырёх цифр',
            },
            birth_date: {
                required: 'Поле обязательно для ввода',
            },
            document_id: {
                required: 'Поле обязательно для ввода',
            },
            phone_number: {
                required: 'Поле обязательно для ввода',
            },
            address: {
                required: 'Поле обязательно для ввода',
            },
            country: {
                required: 'Поле обязательно для ввода',
            },
            zip_code: {
                required: 'Поле обязательно для ввода',
            },
        }
    }

    Validator.localize('en', dict);
    Validator.localize('ru', dict);

    const USER_TYPE_PRIVATE = 1;
    const USER_TYPE_BUSINESS = 2;

    export default {
        name: 'UserCreateEdit',
        data() {
            return {
                isLoading: false,
                isEdit   : true,
                user     : {
                    name     : '',
                    last_name: '',
                    email    : '',
                    password : '',
                    pin      : '',
                    user_type: USER_TYPE_PRIVATE,
                    details  : {
                        company_name          : '',
                        company_reg_number    : '',
                        company_representative: '',
                        birth_date            : '',
                        document_id           : '',
                        phone_number          : '',
                        address               : '',
                        country               : '',
                        zip_code              : '',
                        marketing             : false,
                        notifications         : false,
                        language              : 'ru'
                    },
                },
                languages: [
                    {text: 'Русский', value: 'ru'},
                    {text: 'Latvian', value: 'lv'},
                    {text: 'English', value: 'en'},
                ],
                validForm: false,
            }
        },
        computed: {
            isPrivateUser() {
                return this.user.user_type == USER_TYPE_PRIVATE
            }
        },
        methods: {
            toggleLoader() {
                this.isLoading ?
                    this.$vs.loading.close('#user-create-edit-card > .con-vs-loading') :
                    this.$vs.loading({container: '#user-create-edit-card'})

                this.isLoading = !this.isLoading
            },
            saveUser() {
                this.toggleLoader()
                this.$store.dispatch('saveUser', this.user)
                    .then((r) => {
                        this.user = r.data.data.user
                        this.toggleLoader()
                        this.$vs.notify({
                            title   : 'Сохранено',
                            text    : 'Данные пользвателя успешно сохранены',
                            color   : 'success',
                            position: 'top-right'
                        })
                        this.$store.dispatch('getUsers')
                    })
                    .catch(() => {
                        this.toggleLoader()
                        this.$vs.notify({
                            title   : 'Ошибка',
                            text    : 'Произошла ошибка! Данные пользователя не были сохранены',
                            color   : 'danger',
                            position: 'top-right'
                        })
                    })
            },
            createUser() {
                this.toggleLoader()
                this.$store.dispatch('createUser', this.user)
                    .then((r) => {
                        this.toggleLoader()
                        this.user = r.data.data.user
                        this.isEdit = true
                        this.$vs.notify({title:'Сохранено',text:'Пользователь успешно добавлен',color:'success',position: 'top-right'})
                        this.$router.push({name: 'user-edit', params: {id: this.user.id}})
                        this.$store.dispatch('getUsers')
                    })
                    .catch((e) => {
                        this.toggleLoader()
                        this.$vs.notify({
                            title   : 'Ошибка',
                            text    : 'Произошла ошибка! Данные пользователя не были сохранены',
                            color   : 'danger',
                            position: 'top-right'
                        })
                    })
            },
            fetchUser() {
                Axios.get('/api/admin/user/' + this.$route.params.id)
                    .then(r => {
                        this.user = r.data.data.user
                    })
                    .catch(e => console.log(e))
            },
            userCreateUpdate() {
                if (this.$route.name == 'user-create') {
                    this.createUser()
                } else if (this.$route.name == 'user-edit') {
                    this.saveUser()
                }
            },
            submitForm() {
                this.$validator
                    .validateAll()
                    .then(result => result ? (this.validForm = true, this.userCreateUpdate()) : this.validForm = false)
            },
            addTransaction() {
                this.$router.push({name: 'transaction-create', params: {userId: this.user.id}})
            },
            addAccount() {
                this.$router.push({name: 'account-create', params: {userId: this.user.id}})
            },
            getTransaction() {
                this.$router.push({name: 'transactions-user', params: {userId: this.user.id}})
            },
            showUserAccounts() {
                this.$router.push({
                    name: 'user-accounts',
                    params: {
                        userId: this.user.id,
                        userAccounts: this.user.accounts,
                        user: this.user
                    },
                })
            },
            openAlert() {
                this.$vs.dialog({
                    color        : 'primary',
                    title        : `Удалить пользователя ${this.user.email}`,
                    text         : 'Данные пользователя будут удалены.',
                    "accept-text" : 'Подтвердить',
                    accept       : this.acceptAlert
                })
            },
            acceptAlert() {
                Axios.delete('/api/admin/users/' + this.$route.params.id)
                    .then(r => {
                        this.$vs.notify({
                            title: 'Сохранено',
                            text: 'Пользователь удалён',
                            color: 'success',
                            position: 'top-right'
                        })
                        this.$store.dispatch('getUsers')
                        this.$router.push({name: 'users'})
                    })
                    .catch(e => {
                        console.log(e)
                        this.$vs.notify({
                            title   : 'Ошибка',
                            text    : 'Произошла ошибка! Данные пользователя не были сохранены',
                            color   : 'danger',
                            position: 'top-right'
                        })
                    })
            },
        },
        created() {
            if (this.$route.name == 'user-create') {
                this.isEdit = false
            } else if (this.$route.name == 'user-edit') {
                this.isEdit = true
                this.fetchUser()
            }
        },
        beforeCreate() {
            // if (this.$route.name == 'user-edit') {
            //     this.fetchUser()
            // }
            // console.log(_.find(this.$store.state.allUsers, {id: 1}))
            // console.log(this.$store.state.allUsers)
            // console.log(_.isEmpty(this.$store.state.allUsers))
        }
    }
</script>
