<template>
    <div>
        <vx-card title="Платежные данные"
                 id="transaction-create-edit-card"
                 class="mb-base vs-con-loading__container">
            <div class="vx-row mt-4">
                <div class="vx-col w-full">
                    <vs-radio :disabled="isEdit" v-model="transaction.is_incoming" vs-value="1" class="mr-2">
                        Входящий
                    </vs-radio>
                    <vs-radio :disabled="isEdit" v-model="transaction.is_incoming" vs-value="0">
                        Исходящий
                    </vs-radio>
                </div>
            </div>
            <div v-if="!isIncoming" class="vx-row mt-4">
                <div class="vx-col w-full">
                    <div class="vx-col">
                        <vs-select :disabled="isEdit" v-model="transaction.type" class="max-w-xs w-full select-large"
                                   label="Тип платежа">
                            <vs-select-item :key="index" :value="item.value" :text="item.text"
                                            v-for="(item,index) in paymentTypeOptions" class="w-full"/>
                        </vs-select>
                    </div>
                </div>
            </div>
            <div v-if="!isEdit" class="vx-row mt-4">
                <div class="vx-col w-full">
                    <vs-select v-if="user.accounts.length > 0" v-model="transaction.account_id"
                               class="max-w-xs w-full select-large" label="Счет">
                        <vs-select-item :key="index" :value="item.id" :text="item.iban"
                                        v-for="(item,index) in user.accounts" class="w-full"/>
                    </vs-select>
                    <div v-else>У данного пользователя нет активных счетов.
                        <vs-button color="primary" type="flat" v-on:click="addAccount">Добавить</vs-button>
                    </div>
                </div>
            </div>
            <div v-if="transaction.is_incoming == '0'" class="vx-row mt-4">
                <div class="vx-col w-full">
                    <vs-input class="w-full max-w-sm"
                              v-validate="{regex: /^([A-я \-0-9])+$/}"
                              label="Получатель"
                              v-model="transaction.beneficiary"
                              name="transaction_beneficiary"/>
                    <span class="text-danger text-sm" v-show="errors.has('transaction_beneficiary')">{{ errors.first('transaction_beneficiary') }}</span>
                </div>
            </div>
            <div class="vx-row mt-4">
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
                    <vs-input class="w-full max-w-sm"
                              v-validate="{regex: /^([A-я0-9])+$/}"
                              label="SWIFT/BIC"
                              v-model="transaction.swift"
                              name="transaction_swift"/>
                    <span class="text-danger text-sm" v-show="errors.has('transaction_swift')">{{ errors.first('transaction_swift') }}</span>
                </div>
            </div>
            <div v-if="transaction.is_incoming == '1'" class="vx-row mt-4">
                <div class="vx-col w-full">
                    <vs-input class="w-full max-w-sm"
                              v-validate="{regex: /^([A-я \-0-9])+$/}"
                              label="Отправитель"
                              v-model="transaction.payer" name="transaction_payer"/>
                    <span class="text-danger text-sm" v-show="errors.has('transaction_payer')">{{ errors.first('transaction_payer') }}</span>
                </div>
            </div>
            <div class="vx-row mt-4">
                <div class="vx-col w-full">
                    <vs-input class="w-full max-w-sm"
                              v-validate="{required:true, regex: /^([.0-9])+$/}"
                              label="Сумма"
                              v-model="transaction.amount"
                              name="transaction_amount"/>
                    <span class="text-danger text-sm" v-show="errors.has('transaction_amount')">{{ errors.first('transaction_amount') }}</span>
                </div>
            </div>
            <div class="vx-row mt-4">
                <div class="vx-col w-full">
                    <vs-input class="w-full max-w-sm"
                              v-validate="{required:true, regex: /^([A-я \/.\-0-9])+$/}"
                              label="Пояснение"
                              v-model="transaction.reference"
                              name="transaction_reference"/>
                    <span class="text-danger text-sm" v-show="errors.has('transaction_reference')">{{ errors.first('transaction_reference') }}</span>
                </div>
            </div>
            <div class="vx-row mt-4">
                <div class="vx-col w-full">
                    <div class="vx-col">
                        <vs-select v-model="transaction.status" class="max-w-xs w-full select-large"
                                   label="Статус платежа">
                            <vs-select-item :key="index" :value="item.value" :text="item.text"
                                            v-for="(item,index) in statusOptions" class="w-full"/>
                        </vs-select>
                    </div>
                </div>
            </div>
            <div class="vx-row mt-4">
                <div class="vx-col w-full">
                    <div class="flex">
                        <vs-switch v-model="transaction.is_accepted"/>
                        <span class="vs-input--label ml-2">Платеж подтвержден</span>
                    </div>
                </div>
            </div>
            <div class="vx-row mt-4">
                <div class="vx-col w-full">
                    <vs-button :disabled="disabledSubmitButton"
                               v-on:click="submitForm">
                        {{ isEdit ? 'Сохранить' : 'Создать' }}
                    </vs-button>
                </div>
            </div>
        </vx-card>
    </div>
</template>

<script>
    import Axios from 'axios'
    import {Validator} from 'vee-validate'

    const dict = {
        custom: {
            last_name: {
                max: 'Максимальная длина не должна привышать 30 символов включая пробел',
                regex: 'Поле может содержать только буквы, пробел " ", "-"',
                required: 'Поле обязательно для ввода',
            },
            transaction_beneficiary: {
                regex: 'Поле может содержать только буквы, пробел " ", "-"',
                required: 'Поле обязательно для ввода',
            },
            transaction_iban: {
                regex: 'Поле может содержать только буквы и цифры',
                required: 'Поле обязательно для ввода',
            },
            transaction_swift: {
                regex: 'Поле может содержать только буквы и цифры',
                required: 'Поле обязательно для ввода',
            },
            transaction_payer: {
                regex: 'Поле может содержать только буквы, пробел " ", "-"',
                required: 'Поле обязательно для ввода',
            },
            transaction_amount: {
                regex: 'Поле может содержать только числа',
                required: 'Поле обязательно для ввода',
            },
            transaction_reference: {
                regex: 'Поле может содержать только буквы, пробел " ", "-", ".", "/"',
                required: 'Поле обязательно для ввода',
            },
        }
    }

    Validator.localize('en', dict);
    Validator.localize('ru', dict);

    const USER_TYPE_PRIVATE = 1;
    const USER_TYPE_BUSINESS = 2;

    export default {
        name: 'TransactionCreateEdit',
        data() {
            return {
                isLoading: false,
                isEdit: true,
                validForm: false,
                user: {
                    id: '',
                    name: '',
                    last_name: '',
                    email: '',
                    password: '',
                    pin: '',
                    user_type: USER_TYPE_PRIVATE,
                    accounts: [],
                    details: {
                        company_name: '',
                        company_reg_number: '',
                        company_representative: '',
                        birth_date: '',
                        document_id: '',
                        phone_number: '',
                        address: '',
                        country: '',
                        zip_code: '',
                        marketing: false,
                        notifications: false,
                        language: 'ru'
                    },
                },
                transaction: {
                    user_id: '',
                    account_id: '',
                    amount: '',
                    commission: '',
                    currency: '',
                    is_accepted: 0,
                    type: 1,
                    status: 2,
                    reference: '',
                    payer: '',
                    beneficiary: '',
                    iban: '',
                    swift: '',
                    is_incoming: 0,
                },
                paymentTypeOptions: [
                    {text: "Внутренний", value: 1},
                    {text: "Стандартный", value: 2},
                    {text: "Экспресс", value: 3},
                ],
                statusOptions: [
                    {text: "В обработке", value: 1},
                    {text: "Выполнен", value: 2},
                    {text: "Отменен", value: 3}
                ]
            }
        },
        computed: {
            disabledSubmitButton() {
                return !this.isEdit && this.user.accounts.length < 1
            },
            isIncoming() {
                return this.transaction.is_incoming == '1' ? true : false
            }
        },
        methods: {
            toggleLoader() {
                this.isLoading ?
                    this.$vs.loading.close('#transaction-create-edit-card > .con-vs-loading') :
                    this.$vs.loading({container: '#transaction-create-edit-card'})

                this.isLoading = !this.isLoading
            },
            saveTransaction() {
                this.toggleLoader()
                this.$store.dispatch('updateTransaction', this.transaction)
                    .then((r) => {
                        this.transaction = r.data.data.transaction
                        this.toggleLoader()
                        this.$vs.notify({
                            title: 'Сохранено',
                            text: 'Данные транзакции успешно сохранены',
                            color: 'success',
                            position: 'top-right'
                        })
                        this.$store.dispatch('getTransactions')
                        this.$store.dispatch('getAccounts')
                    })
                    .catch(() => {
                        this.toggleLoader()
                        this.$vs.notify({
                            title: 'Ошибка',
                            text: 'Произошла ошибка! Данные транзакции не были сохранены',
                            color: 'danger',
                            position: 'top-right'
                        })
                    })
            },
            createTransaction() {
                this.toggleLoader()
                this.$store.dispatch('createTransaction', this.transaction)
                    .then((transaction) => {
                        this.toggleLoader()
                        this.isEdit = true
                        this.$vs.notify({
                            title: 'Сохранено',
                            text: 'Транзакции успешно добавлена',
                            color: 'success',
                            position: 'top-right'
                        })
                        this.$store.dispatch('getTransactions')
                        this.$router.push({name: 'user-edit', params: {id: this.transaction.user_id}})
                    })
                    .catch(() => {
                        this.toggleLoader()
                        this.$vs.notify({
                            title: 'Ошибка',
                            text: 'Произошла ошибка! Данные транзакции не были сохранены',
                            color: 'danger',
                            position: 'top-right'
                        })
                    })
            },
            fetchTransaction() {
                Axios.get('/api/admin/transaction/' + this.$route.params.id)
                    .then(r => {
                        this.transaction = r.data.data.transaction
                    })
                    .catch(e => console.log(e))
            },
            fetchUser() {
                Axios.get('/api/admin/user/' + this.$route.params.userId)
                    .then(r => {
                        this.user = r.data.data.user
                    })
                    .catch(e => console.log(e))
            },
            transactionCreateUpdate() {
                if (this.$route.name == 'transaction-create') {
                    this.createTransaction()
                } else if (this.$route.name == 'transaction-edit') {
                    this.saveTransaction()
                }
            },
            submitForm() {
                this.$validator
                    .validateAll()
                    .then(result => result ? (this.validForm = true, this.transactionCreateUpdate()) : this.validForm = false)
            },
            addAccount() {
                this.$router.push({name: 'account-create', params: {userId: this.user.id}})
            }
        },
        created() {
            if (this.$route.name == 'transaction-create') {
                this.isEdit = false
                this.fetchUser()
            } else if (this.$route.name == 'transaction-edit') {
                this.isEdit = true
                this.fetchTransaction()
            }
            this.transaction.user_id = this.$route.params.userId
        }
    }
</script>

<style scoped>

</style>
