<template>
    <div>
        <vx-card v-if="!isPaymentSent" title="Создать платеж" class="md:w-2/3 md:mx-auto">
            <form-wizard color="rgba(var(--vs-primary), 1)"
                         :title="null"
                         :subtitle="null"
                         finishButtonText="Отправить"
                         nextButtonText="Далее"
                         backButtonText="Назад"
                         @on-complete="formSubmitted">
                <tab-content class="mb-5">

                    <!-- tab 1 content -->
                    <div class="vx-row md:w-1/2 mx-auto flex-col no-gutter">
                        <h3>Тип платежа</h3>
                        <div class="vx-col mt-5">
                            <vs-select v-model="paymentType" class="w-full select-large" label="Тип платежа">
                                <vs-select-item :key="index" :value="item.value" :text="item.text"
                                                v-for="(item,index) in paymentTypeOptions" class="w-full"/>
                            </vs-select>
                        </div>
                        <div class="vx-col mt-5">
                            <vs-select v-model="account" class="w-full select-large" label="Со счёта">
                                <vs-select-item :key="index" :value="item.id" :text="item.iban"
                                                v-for="(item,index) in accounts" class="w-full"/>
                            </vs-select>
                        </div>
                    </div>
                </tab-content>

                <!-- tab 2 content -->
                <tab-content class="mb-5" :before-change="validateStep1">
                    <form data-vv-scope="transaction-step-1">
                        <div class="vx-row md:w-1/2 mx-auto flex-col no-gutter">
                            <h3>Детали</h3>
                            <div class="vx-col mt-5">
                                <vs-input v-validate="'required'" label="Получатель" name="beneficiary"
                                          v-model="beneficiary" class="w-full"/>
                                <span class="text-danger">{{ errors.first('transaction-step-1.beneficiary') }}</span>
                            </div>
                            <div class="vx-col mt-5">
                                <vs-input v-validate="'required|alpha_num|max:34'"
                                          label="IBAN"
                                          name="iban"
                                          v-model="iban"
                                          class="w-full"/>
                                <span class="text-danger">{{ errors.first('transaction-step-1.iban') }}</span>
                            </div>
                            <div class="vx-col mt-5">
                                <vs-input v-validate="'required|alpha_num|max:11'" label="SWIFT/BIC" name="swift"
                                          v-model="swift" class="w-full"/>
                                <span class="text-danger">{{ errors.first('transaction-step-1.swift') }}</span>
                            </div>
                            <div class="vx-col mt-5">
                                <vs-input v-validate="{required:true, regex: /^([.0-9])+$/}"
                                          label="Сумма"
                                          name="amount"
                                          v-model="amount"
                                          class="w-full"/>
                                <span class="text-danger">{{ errors.first('transaction-step-1.amount') }}</span>
                            </div>
                            <div class="vx-col mt-5 mb-5">
                                <vs-input v-validate="{required: true, regex: /^([A-я \/.\-0-9])+$/}" label="Пояснение" name="reference"
                                          v-model="reference" class="w-full"/>
                                <span class="text-danger">{{ errors.first('transaction-step-1.reference') }}</span>
                            </div>
                        </div>
                    </form>
                </tab-content>

                <!-- tab 3 content -->
                <tab-content class="mb-5" :before-change="validateStep2">
                    <form data-vv-scope="transaction-step-2">
                        <div class="vx-row md:w-1/2 mx-auto flex-col no-gutter">
                            <h3>Подтверждение</h3>
                            <div class="vx-col mt-5">
                                <vs-input label="PIN" v-model="pin" name="pin" class="w-full"/>
                                <span class="text-danger" v-if="!pinCorrect">Введите правильный pin</span>
                            </div>
                        </div>
                    </form>
                </tab-content>
            </form-wizard>
        </vx-card>
        <vx-card v-else-if="isPaymentSent" class="md:w-2/3 md:mx-auto">
            <div class="text-center">
                <vs-icon icon="check_circle_outline" size="64px" color="success"></vs-icon>
                <h4 class="text-center">Платеж отправлен в обработку</h4>
                <ul class="inline-block mt-6 text-left pl-7">
                    <li class="py-2">{{ beneficiary }}</li>
                    <li class="py-2">{{ iban }}</li>
                    <li class="py-2">{{ swift }}</li>
                    <li class="py-2">{{ amount }}</li>
                    <li class="py-2">{{ reference }}</li>
                </ul>
            </div>
        </vx-card>
    </div>
</template>


<script>
    import {FormWizard, TabContent} from 'vue-form-wizard'
    import 'vue-form-wizard/dist/vue-form-wizard.min.css'

    // For custom error message
    import {Validator} from 'vee-validate';

    const dict = {
        custom: {
            beneficiary: {
                required: 'Поле обязательно для ввода.',
            },
            iban       : {
                required : 'Поле обязательно для ввода.',
                alpha_num: 'Поле может содержать только буквы или цифры',
                max      : 'Длина IBAN не должна привышать 34 символов',
            },
            swift      : {
                required : 'Поле обязательно для ввода.',
                alpha_num: 'Поле может содержать только буквы или цифры',
                max      : 'Длина SWIFT кода не должна привышать 11 символов',
            },
            amount     : {
                required: 'Поле обязательно для ввода.',
                regex : "Поле может содержать только цифры"
            },
            reference  : {
                required : 'Поле обязательно для ввода.',
                regex  : 'Поле может содержать только буквы, пробел " ", "-", ".", "/"',
            },
            pin: {
                required : 'Поле обязательно для ввода.',
            }
        }
    };

    // register custom messages
    Validator.localize('en', dict);
    Validator.localize('ru', dict);

    export default {
        name      : "AddTransaction",
        components: {
            FormWizard,
            TabContent
        },
        computed  : {
            // account() {
            //     return this.$store.state.accounts.length > 0 ? _.head(this.$store.state.accounts).id : ''
            // },
            accounts() {
                return this.$store.state.accounts
            },
            // userPin: '1234',
        },
        data() {
            return {
                paymentType       : 1,
                beneficiary       : '',
                iban              : '',
                swift             : '',
                pin               : '',
                reference         : '',
                amount            : '',
                pinCorrect: true,
                account: 1,
                // account: _.head(this.$store.state.accounts).id,
                paymentTypeOptions: [
                    {text: "Внутренний", value: 1},
                    {text: "Стандартный", value: 2},
                    {text: "Экспресс", value: 3},
                ],
                // accounts: this.$store.state.accounts,
                isPaymentSent     : false,
            }
        },
        methods   : {
            formSubmitted() {
                this.isPaymentSent = true
                let trx = {
                    paymentType: this.paymentType,
                    beneficiary: this.beneficiary,
                    iban       : this.iban,
                    swift      : this.swift,
                    pin        : this.pin,
                    reference  : this.reference,
                    amount     : this.amount,
                    account    : this.account,
                }
                this.$store.dispatch('sendTransaction', trx)
                this.$store.dispatch('getTransactions')
                this.$store.dispatch('getAccounts')
            },
            validateStep1() {
                return new Promise((resolve, reject) => {
                    this.$validator.validateAll('transaction-step-1').then(result => {
                        if (result) {
                            resolve(true)
                        } else {
                            reject("correct all values");
                        }
                    })
                })
            },
            validateStep2() {
                return new Promise((resolve, reject) => {
                    if (this.pin == this.$store.state.user.pin) {
                        this.pinCorrect = true
                        resolve(true)
                    } else {
                        this.pinCorrect = false
                        reject("correct all values");
                    }
                })
            },
        },

    }
</script>
