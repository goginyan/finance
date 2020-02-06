<template>
    <div id="data-list-list-view" class="data-list-container">
        <div class="vx-row mt-4">
            <div class="vx-col w-full">
                <vs-button v-if="isAcceptedId.length"
                           type="border"
                           v-on:click="bulkAccept"
                           icon-pack="feather">
                    Подтвердить
                </vs-button>
            </div>
        </div>
        <vs-table ref="table" pagination :max-items="itemsPerPage" :data="transactions">

            <template slot="thead">
                <vs-th></vs-th>
                <vs-th></vs-th>
                <vs-th>Дата</vs-th>
                <vs-th>Получатель/плательщик</vs-th>
                <vs-th>Детали</vs-th>
                <vs-th>Счет</vs-th>
                <vs-th>Статус</vs-th>
                <vs-th>Тип платежа</vs-th>
                <vs-th>Сумма</vs-th>
            </template>

            <template slot-scope="{data}">
                <tbody>
                <vs-tr  :key="indextr" v-for="(tr, indextr) in data">
                    <vs-td class="whitespace-no-wrap">
                        <div >
                            <vs-checkbox :vs-value="tr.id"
                                         v-model="isAcceptedId" >
                            </vs-checkbox>
                        </div>
                    </vs-td>
                    <vs-td class="whitespace-no-wrap">
                        <feather-icon icon="EditIcon"
                                      class="cursor-pointer"
                                      svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                      @click.stop="edit(tr.id)"/>
                    </vs-td>

                    <vs-td >
                        <p v-bind:class="{'font-bold':tr.is_accepted}">{{ tr.created_at }}</p>
                    </vs-td>

                    <vs-td>
                        <p  v-bind:class="{'font-bold':!tr.is_accepted}">{{ tr.beneficiary }}</p>
                    </vs-td>

                    <vs-td>
                        <p  v-bind:class="{'font-bold':!tr.is_accepted}"> {{ tr.reference }}</p>
                    </vs-td>

                    <vs-td>
                        <p  v-bind:class="{'font-bold':!tr.is_accepted}">{{ tr.user_account?tr.user_account.iban:null }}</p>
                    </vs-td>

                    <vs-td>
                        <span  v-bind:class="{'font-bold':!tr.is_accepted}">{{ getPaymentStatus(tr.status) }}</span>
                    </vs-td>

                    <vs-td>
                        <p  v-bind:class="{'font-bold':!tr.is_accepted}">{{ getPaymentType(tr.type) }}</p>
                    </vs-td>

                    <vs-td>
                        <p  v-bind:class="{'font-bold':!tr.is_accepted}" class="product-price">{{ tr.amount.toFixed(2) }}</p>
                    </vs-td>

                </vs-tr>
                </tbody>
            </template>
        </vs-table>
    </div>
</template>

<script>
    import Axios from 'axios'

    export default {
        name: 'Transactions',
        data() {
            return {
                isMounted: false,
                itemsPerPage: 10,
                // Data Sidebar
                addNewDataSidebar: false,
                isAcceptedId: [],
                sidebarData: {},
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
                if(this.$route.name === 'transactions-user')
                   return this.$store.state.transactionsUser;
                    else return this.$store.state.transactions
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
            edit(id) {
                this.$router.push({name: 'transaction-edit', params: {id: id}})
            },
            bulkAccept() {
                Axios.patch('/api/admin/transactions/bulk-accept', {
                    ids: this.isAcceptedId
                })
                    .then((request) => {
                        this.isAcceptedId = [];
                        this.$store.dispatch('getTransactions')
                        this.$store.dispatch('updateTransactionUser', this.$route.params.userId);
                    })
            }
        },
        mounted() {
            this.isMounted = true;
        },
        watch:{
            '$route.name':function () {
                if(this.$route.name === 'transactions-user')
                    this.$store.dispatch('updateTransactionUser', this.$route.params.userId);
                else this.$store.dispatch('getTransactions');
            }
        },
        created() {
            if(this.$route.name === 'transactions-user')
                this.$store.dispatch('updateTransactionUser', this.$route.params.userId);
            else this.$store.dispatch('getTransactions');
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

            .vs-checkbox--check{
                    border-radius: 0!important;
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
