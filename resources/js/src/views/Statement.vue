<template>
    <div id="statement-page" class="max-w-2xl mx-auto">

        <div class="flex flex-wrap items-center justify-between">
          <div class="flex items-center">
            <vs-button class="my-base mr-3"
                       v-on:click="printInvoice"
                       type="border"
                       icon-pack="feather"
                       icon="icon icon-download">
                {{ $t('invoice.download') }}
            </vs-button>
          </div>
        </div>

        <vx-card id="invoice-container" ref="content" v-if="transaction">

            <div class="vx-row leading-loose p-base">
                <div class="vx-col w-full mt-base">
                    <img class="statement-logo" src="../../../assets/images/logo/zbk_logo.png" alt="zbkbank.com">
                </div>
                <div class="vx-col w-full mt-4 flex">
                    <div class="w-1/3">
                        Владелец счета:
                    </div>
                    <div class="w-2/3">
                        <span v-if="user.user_type === 1">{{ user.name }} {{ user.last_name }}</span>
                        <span v-else>{{ user.details.company_name }} </span>
                    </div>
                </div>
                <div class="vx-col w-full mt-4 flex">
                    <div class="w-1/3">
                        Дата печати:
                    </div>
                    <div class="w-2/3">
                        {{ new Date().toLocaleDateString('ru-RU') }}
                    </div>
                </div>
                <div class="vx-col w-full mt-4 flex">
                    <div class="w-1/3">Выполнен</div>
                </div>
            </div>

            <!-- INVOICE CONTENT -->
            <div class="p-base">
                <!-- INVOICE TASKS TABLE -->
                <vs-table hoverFlat :data="transaction">
                    <!-- HEADER -->
                    <template slot="thead">
                        <vs-th>Дата</vs-th>
                        <vs-th>Описание</vs-th>
                        <vs-th>Сумма</vs-th>
                    </template>

                    <!-- DATA -->
                    <template slot-scope="{data}">
                        <vs-tr>
                            <vs-td>Дата операции <br>{{ data.created_at }}</vs-td>
                            <vs-td>
                                <p class="my-2"><strong>Со счета:</strong> {{ data.user_account.iban }}</p>
                                <p class="my-2"><strong>На счет:</strong> {{ data.iban }}</p>
                                <p v-if="data.is_incoming == 0" class="my-2"><strong>Получатель:</strong> {{ data.beneficiary }}</p>
                                <p v-if="data.is_incoming == 1" class="my-2"><strong>Отправитель:</strong> {{ `${data.payer} ${data.iban}` }}</p>
                                <p class="my-2"><strong>Назначение</strong> {{ data.reference }}</p>
                                <p class="my-2"><strong>Коммиссия</strong> {{ `${data.commission.toFixed(2)} ${data.currency}` }}</p>
                            </vs-td>
                            <vs-td>
                                {{ data.is_incoming == 0 ? '-' + data.amount.toFixed(2) : '' }} {{ data.is_incoming == 1 ? data.currency : '' }}
                                {{ data.is_incoming == 1 ? data.amount.toFixed(2) : '' }} {{ data.is_incoming == 0 ? data.currency : '' }}
                            </vs-td>
                        </vs-tr>
                    </template>
                </vs-table>

            </div>
            <div class="mt-10">
                <img class="statement-stamp p-base ml-auto" src="../../../assets/images/logo/zbk_stamp.png" alt="zbk_stamp">
            </div>
        </vx-card>
    </div>
</template>

<script>

    import _ from 'lodash'
    import html2pdf from 'html2pdf.js'

export default{
    data() {
        return {

        }
    },
    computed  : {
        user() {
            return this.$store.state.user
        },
        transaction() {
            return this.$route.params.transaction
        }
    },
    methods   : {
        printInvoice() {
            html2pdf().from(document.getElementById('invoice-container')).toPdf().save('statement.pdf')

        }
    },
    components: {},
    mounted() {
        this.$emit("setAppClasses", "invoice-page")
    },
    created() {
        if (_.isEmpty(this.$route.params.transaction)) {
            this.$router.push({name: 'transactions'})
        }
    }
}
</script>

<style lang="scss">
    .statement-logo,
    .statement-stamp {
        max-width: 200px;
    }
    @media print {
        .invoice-page {
            * {
                visibility: hidden;
            }

            #content-area {
                margin: 0 !important;
            }

            #invoice-container,
            #invoice-container * {
                visibility: visible;
            }

            #invoice-container {
                position: absolute;
                left: 0;
                top: 0;
                box-shadow: none;
            }
        }
    }
</style>
