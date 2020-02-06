<template>
    <div id="settings">
        <keep-alive>
            <vx-card title="Настройки"
                     id="settings-edit-card"
                     class="mb-base vs-con-loading__container">

                <h5 class="mb-4">Комиссии</h5>

                <div class="vx-col md:w-1/2 w-full" v-if="commissions.length > 0">
                    <template v-for="(commission, index) in commissions">

                        <vs-divider v-if="index === 0">
                            <h6>Счета в евро €</h6>
                        </vs-divider>

                        <div class="flex self-center mb-5">
                            <div class="w-2/3">
                                <vs-input class="w-full"
                                          :label="commissionLabel(commission)"
                                          v-model="commission.amount"
                                          :name="commission.currency + '_' + commission.type"/>
                            </div>
                            <div class="w-1/6 pl-4 flex self-center mt-3">
                                <vs-switch v-model="commission.is_active" />
                            </div>
                        </div>

                        <vs-divider v-if="index === 4">
                            <h6>Счета в долларах $</h6>
                        </vs-divider>

                        <vs-divider v-if="index === 9">
                            <h6>Счета в фунтах £</h6>
                        </vs-divider>

                    </template>

                    <div class="vx-col w-full flex mt-8" id="save-settings">
                        <vs-button icon-pack="feather" icon="icon-check-square" class="mr-4" v-on:click="saveNewCommissions">Сохранить</vs-button>
                    </div>

                </div>

            </vx-card>
        </keep-alive>
    </div>
</template>

<script>
    import Axios from "axios"

    const COMMISSION_TYPE_MONTHLY = 1;
    const COMMISSION_TYPE_TRANSACTION = 2;
    const COMMISSION_TYPE_ACC_OPEN = 3;
    const COMMISSION_TYPE_EXPRESS = 4;
    const COMMISSION_TYPE_INCOMING = 5;

    export default {
        name: 'Settings',
        data() {
            return {
                isLoading: false,
                commissions: []
            }
        },
        methods: {
            toggleLoader() {
                this.isLoading ?
                    this.$vs.loading.close('#settings-edit-card > .con-vs-loading') :
                    this.$vs.loading({container: '#settings-edit-card'})

                this.isLoading = !this.isLoading
            },
            commissionLabel(commission) {
                switch (commission.type) {
                    case COMMISSION_TYPE_MONTHLY:
                        return 'Ежемесечное обслуживание'
                        break
                    case COMMISSION_TYPE_TRANSACTION:
                        return 'За перечисление'
                        break
                    case COMMISSION_TYPE_EXPRESS:
                        return 'За экспресс платеж'
                        break
                    case COMMISSION_TYPE_ACC_OPEN:
                        return 'За открытие'
                        break
                    case COMMISSION_TYPE_INCOMING:
                        return 'За входящий платёж'
                        break
                    default:
                        break
                }
            },
            saveNewCommissions() {
                this.toggleLoader()
                this.$store.dispatch('saveCommissions', this.commissions)
                    .then(() => {
                        this.toggleLoader()
                        this.$vs.notify({
                            title   : 'Сохранено',
                            text    : 'Настройки успешно сохранены',
                            color   : 'success',
                            position: 'top-right'
                        })
                    })
                .catch(e => {
                    console.log(e)
                    this.toggleLoader()
                    this.$vs.notify({
                        title   : 'Ошибка',
                        text    : 'Произошла ошибка! Настройки не были сохранены',
                        color   : 'danger',
                        position: 'top-right'
                    })
                })
            },
        },
        mounted() {
            this.toggleLoader()
            Axios.get('/api/admin/commissions')
                .then(r => {
                    this.commissions = r.data.data.commissions
                    this.toggleLoader()
                })
                .catch(e => console.log(e))
        }
    }
</script>
