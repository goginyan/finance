<template>
    <div id="data-list-list-view" class="data-list-container">
        <div class="flex flex-wrap my-2">
            <div class="lg:w-1/3 w-full lg:pr-3 lg:mb-0 mb-4" :key="indextr" v-for="(tr, indextr) in accounts">
                <vx-card>
                    <div>
                        <h3 class="py-2">{{ currencySing(tr) }} {{ tr.balance }}</h3>
                        <div class="py-2">{{ tr.iban }}</div>
                        <div class="py-2">{{ tr.currency }}</div>
                    </div>
                </vx-card>
            </div>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash'
    import Axios from 'axios'

    export default {
        name: 'BankAccounts',
        data() {
            return {
                itemsPerPage: 10,
                isMounted: false,
                userAccounts: [],
            }
        },
        computed: {
            currentPage() {
                if (this.isMounted) {
                    return this.$refs.table.currentx
                }
                return 0
            },
            accounts() {
                if (this.$route.name == 'accounts') {
                    return this.$store.state.accounts
                } else if (this.$route.name == 'user-accounts') {
                    return this.userAccounts
                }
            },
        },
        methods: {
            currencySing(account) {
                return account.currency === 'EUR' ? '€' : account.currency === 'USD' ? '$' : '£'
            },
        },
        created() {
            // this.$store.dispatch('setAccounts')
            if (this.$route.name == 'user-accounts') {
                Axios.get('/admin/user/'+ this.$route.params.userId +'/accounts')
                .then((r) => {
                    this.userAccounts = r.data.data.accounts
                })
                .catch(e => console.log(e))
            }
        },
        mounted() {
            this.isMounted = true;
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

                        & + i {
                            left: 1rem;
                        }

                        &:focus + i {
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

                        &:first-child {
                            border-top-left-radius: .5rem;
                            border-bottom-left-radius: .5rem;
                        }

                        &:last-child {
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
