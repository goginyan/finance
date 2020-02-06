<template>
    <div>
        <vx-card :title="(isEdit ? 'Редактировать счет' : 'Создать счет')" id="account-create-edit-card" class="mb-base vs-con-loading__container">
            <div v-if="!isEdit" class="vx-row mt-4">
                <div class="vx-col w-full">
                    <vs-select v-model="account.currency" class="w-full select-large max-w-sm" label="Валюта">
                        <vs-select-item :key="index" :value="item.code" :text="item.sign + ' ' +item.name"
                                        v-for="(item,index) in currencies" class="w-full"/>
                    </vs-select>
                </div>
            </div>
            <div v-if="!isEdit" class="vx-row mt-4">
                <div class="vx-col w-full">
                    <vs-input class="max-w-sm w-full" label="Название счета" v-model="account.name"/>
                </div>
            </div>
            <div v-if="!isEdit" class="vx-row mt-4">
                <div class="vx-col w-full">
                    <vs-input class="max-w-sm w-full"
                              label="IBAN"
                              name="iban"
                              v-validate="{required: true, max: 80, regex: /^([A-я0-9])+$/}"
                              v-model="account.iban"/>
                    <span class="text-danger text-sm" v-show="errors.has('iban')">{{ errors.first('iban') }}</span>
                </div>
            </div>
            <div v-if="isEdit" class="vx-row mt-4">
                <div class="vx-col w-full">
                    <h6>Текущий баланс</h6>
                    <div>{{ currencySing(account) }}{{ account.balance }}</div>
                </div>
            </div>
            <div v-if="isEdit" class="vx-row mt-4">
                <div class="vx-col w-full">
                    <vs-input class="w-full max-w-sm"
                              v-validate="{required:true, regex: /^([.0-9-])+$/}"
                              label="Сумма"
                              v-model="account.amount"
                              name="amount"/>
                </div>
            </div>
            <div v-if="isEdit" class="vx-row mt-4">
                <div class="vx-col w-full">
                    <vs-input class="w-full max-w-sm"
                              v-validate="{required:true, regex: /^([A-я \/.\-0-9])+$/}"
                              label="Пояснение"
                              v-model="transaction.reference"
                              name="transaction_reference"/>
                    <span class="text-danger text-sm"
                          v-show="errors.has('transaction_reference')">{{ errors.first('transaction_reference') }}</span>
                </div>
            </div>
            <div v-if="isEdit" class="vx-row mt-4">
                <div class="vx-col w-full">
                    <vs-input class="w-full max-w-sm"
                              v-validate="{regex: /^([A-я0-9])+$/}"
                              label="IBAN"
                              v-model="transaction.iban"
                              name="transaction_iban"/>
                    <span class="text-danger text-sm" v-show="errors.has('transaction_iban')">{{ errors.first('transaction_iban') }}</span>
                </div>
            </div>
            <div class="vx-row mt-4">
                <div class="vx-col w-full">
                    <vs-button v-on:click="submitForm">
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
        </vx-card>
    </div>
</template>

<script>
    import {Validator} from 'vee-validate'
    import Axios from "axios";

    const dict = {
        custom: {
            iban: {
                regex   : 'Поле может содержать только буквы и цифры',
                required: 'Поле обязательно для ввода',
                max     : 'Длина не может привышать 80 символов'
            }
        }
    }

    Validator.localize('en', dict);
    Validator.localize('ru', dict);

    export default {
        name: 'AccountCreateEdit',
        props:{
            edit:{
                required:false
            }
        },
        data() {
            return {
                isEdit: false,
                validForm: false,
                isLoading: false,
                amount: 0,
                transaction: {},
                account: {
                    user_id: '',
                    name: '',
                    iban: '',
                    currency: 'EUR',
                },
                currencies: [
                    {code: 'EUR', name: 'Евро', sign: '€'},
                    {code: 'USD', name: 'Доллары', sign: '$'},
                    {code: 'GBP', name: 'Фунты', sign: '£'},
                ],
            }
        },
        methods: {
            currencySing(account) {
                return account.currency === 'EUR' ? '€' : account.currency === 'USD' ? '$' : '£'
            },
            toggleLoader() {
                this.isLoading ?
                    this.$vs.loading.close('#account-create-edit-card > .con-vs-loading') :
                    this.$vs.loading({container: '#account-create-edit-card'})

                this.isLoading = !this.isLoading
            },
            createAccount() {
                this.toggleLoader()
                this.$store.dispatch('createAccount', this.account)
                    .then((r) => {
                        this.account = r.data.data.account
                        this.toggleLoader()
                        this.$vs.notify({
                            title   : 'Сохранено',
                            text    : 'Счет успешно создан',
                            color   : 'success',
                            position: 'top-right'
                        })
                        this.$store.dispatch('getAccounts')
                        this.$router.push({name: 'user-edit', params: {id: this.account.user_id}})
                    })
                    .catch((e) => {
                        console.log(e)
                        this.toggleLoader()
                        this.$vs.notify({
                            title   : 'Ошибка',
                            text    : 'Произошла ошибка! Данные не были сохранены',
                            color   : 'danger',
                            position: 'top-right'
                        })
                    })

            },
            updateAccount() {
                Axios.patch('/api/admin/accounts', {
                    account    : this.account,
                    transaction: this.transaction
                }).then(r => {
                    this.$router.push({name: 'accounts'})
                }).catch(e => console.log(e))
            },
            accountCreateUpdate() {
                if (this.$route.name == 'account-create') {
                    this.createAccount()
                } else if (this.$route.name == 'account-edit') {
                    this.updateAccount()
                }
            },
            submitForm() {
                this.$validator
                    .validateAll()
                    .then(result => result ? (this.validForm = true,
                        this.accountCreateUpdate()) : this.validForm = false)
            },
            openAlert() {
                this.$vs.dialog({
                    color        : 'primary',
                    title        : `Удалить счет ${this.account.iban}`,
                    text         : 'Данный счет будет удален.',
                    "accept-text": 'Подтвердить',
                    accept       : this.acceptAlert
                })
            },
            acceptAlert() {
                Axios.delete('/api/admin/accounts/' + this.account.id)
                    .then(r => {
                        this.$vs.notify({
                            title   : 'Сохранено',
                            text    : 'Счет удалён',
                            color   : 'success',
                            position: 'top-right'
                        })
                        this.$router.push({name: 'accounts'})
                    })
                    .catch(e => {
                        console.log(e)
                        this.$vs.notify({
                            title   : 'Ошибка',
                            text    : 'Произошла ошибка! Данные не были сохранены',
                            color   : 'danger',
                            position: 'top-right'
                        })
                    })
            },
        },
        created() {
            if (this.edit) {
                this.isEdit = this.edit;
                Axios.get('/api/admin/account/' + this.$route.params.id)
                    .then(r => {
                        this.account = r.data.data.account
                    })

            }
            this.account.user_id = this.$route.params.userId;
        }
    }
</script>
