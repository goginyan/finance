<template>
    <div id="data-list-list-view" class="data-list-container">

        <vs-button type="border" icon-pack="feather" icon="icon-plus" v-on:click="newTrx">Новый платеж</vs-button>

        <vs-table v-if="transactions.length > 0"
                  ref="table"
                  pagination
                  :max-items="itemsPerPage"
                  :data="transactions">

            <template slot="thead">
                <vs-th></vs-th>
                <vs-th>Дата</vs-th>
                <vs-th>Получатель/плательщик</vs-th>
                <vs-th>Пояснение</vs-th>
                <vs-th>Счет отправителя</vs-th>
                <vs-th>Статус</vs-th>
                <vs-th>Тип платежа</vs-th>
                <vs-th>Коммиссия</vs-th>
                <vs-th>Сумма</vs-th>
            </template>

            <template slot-scope="{data}">
                <tbody>
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">

                    <vs-td>
                        <p>
                            <vs-button size="large"
                                       v-on:click="showInvoice(indextr)"
                                       color="primary"
                                       icon-pack="feather"
                                       type="flat"
                                       icon="icon-file-text"
                                       class="cursos-pointer"/>
                        </p>
                    </vs-td>

                    <vs-td>
                        <p>{{ tr.created_at }}</p>
                    </vs-td>

                    <vs-td>
                        <p v-if="tr.is_incoming == 0">{{ `${tr.beneficiary} ${tr.iban}` }}</p>
                        <p v-if="tr.is_incoming == 1">{{ `${tr.payer} ${tr.iban}` }}</p>
                    </vs-td>

                    <vs-td>
                        <p>{{ tr.reference }}</p>
                    </vs-td>

                    <vs-td>
                        <p v-if="tr.is_incoming == 0">{{ tr.user_account.iban }}</p>
                        <p v-if="tr.is_incoming == 1">{{ tr.iban }}</p>
                    </vs-td>

                    <vs-td>
                        <span>{{ getPaymentStatus(tr.status) }}</span>
                    </vs-td>

                    <vs-td>
                        <p>{{ getPaymentType(tr.type) }}</p>
                    </vs-td>

                    <vs-td>
                        <p> {{ `${tr.commission.toFixed(2)}` }} </p>
                    </vs-td>

                    <vs-td>
                        <p class="product-price">
                            {{ tr.is_incoming == 0 ? `-${tr.amount.toFixed(2)}` : tr.amount.toFixed(2) }}
                        </p>
                    </vs-td>

                </vs-tr>
                </tbody>
            </template>
        </vs-table>
    </div>
</template>

<script>
    export default {
        name: "Transactions",
        data() {
            return {
                isMounted: false,
                itemsPerPage: 10,
            }
        },
        computed: {
            currentPage() {
                if (this.isMounted) {
                    return this.$refs.table.currentx
                }
                return 0
            },
            transactions() {
                return this.$store.state.transactions
            },
        },
        methods: {
            newTrx() {
                this.$router.push('/transactions/new')
            },
            getPaymentStatusColor(status) {
                if (status === 1) return 'primary'
                if (status === 2) return 'success'
                if (status === 3) return 'danger'
            },
            getPaymentStatus(status) {
                if (status === 1) return 'В обработке'
                if (status === 2) return 'Выполнен'
                if (status === 3) return 'Отменён'
            },
            getPaymentType(type) {
                if (type === 1) return 'Внутренний'
                if (type === 2) return 'Стандартный'
                if (type === 3) return 'Экспресс'
            },
            showInvoice(indextr) {
                this.$router.push({name: 'statement', params:{transaction: this.transactions[indextr]}})
            }
        },
        mounted() {
            this.isMounted = true;
        },
        created() {
            // this.$store.dispatch('setTransactions')
        }
    }
</script>

<style lang="scss">
    #data-list-list-view {

        .vs-con-table {

            .product-name {
                max-width: 23rem;
            }

            .vs-table--header {
                display: flex;
                flex-wrap: wrap-reverse;
                /*margin-left: 1.5rem;*/
                /*margin-right: 1.5rem;*/

                > span {
                    display: flex;
                    flex-grow: 1;
                }

                .vs-table--search {
                    padding-top: 0;

                    .vs-table--search-input {
                        padding: 0.9rem 2.5rem;
                        font-size: 1rem;

                        &
                        + i {
                            left: 1rem;
                        }

                        &
                        :focus + i {
                            left: 1rem;
                        }

                    }
                }
            }

            .vs-table {
                border-collapse: separate;
                border-spacing: 0 1.3rem;
                padding: 0;

                tr {
                    box-shadow: 0 4px 20px 0 rgba(0, 0, 0, .05);

                    td {
                        padding: 20px;

                        &
                        :first-child {
                            border-top-left-radius: .5rem;
                            border-bottom-left-radius: .5rem;
                        }

                        &
                        :last-child {
                            border-top-right-radius: .5rem;
                            border-bottom-right-radius: .5rem;
                        }

                    }

                    td.td-check {
                        padding: 20px !important;
                    }

                }
            }

            .vs-table--thead {

                th {
                    padding-top: 0;
                    padding-bottom: 0;

                    .vs-table-text {
                        text-transform: uppercase;
                        font-weight: 600;
                    }

                }

                th.td-check {
                    padding: 0 15px !important;
                }

                tr {
                    background: none;
                    box-shadow: none;
                }

            }

            .vs-table--pagination {
                justify-content: center;
            }

        }
    }
</style>
