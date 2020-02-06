<template>
    <div>
<!--        <div class="flex flex-wrap mb-10">-->
<!--            <div class="w-full lg:w-2/3">-->
<!--                <vx-card class="overview-balance">-->
<!--                    <div>-->
<!--                        <vs-tabs>-->
<!--                            <vs-tab class="mx-6" label="$ USD">-->
<!--                                <div class="pt-4">-->
<!--                                    <h1>$ 14342,12</h1>-->
<!--                                    <div class="line-area-chart pt-8">-->
<!--                                        <vue-apex-charts ref="apexChart" type="line" height=200 width='100%' :options="subscribersGainedChartData.chartOptions" :series="subscribersGainedChartData.series" />-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </vs-tab>-->
<!--                            <vs-tab label="€ EUR">-->
<!--                                <div class="pt-4">-->
<!--                                    <h1>€ 14342,12</h1>-->
<!--                                    <div class="line-area-chart pt-8">-->
<!--                                        <vue-apex-charts ref="apexChart" type="line" height=200 width='100%' :options="subscribersGainedChartData2.chartOptions" :series="subscribersGainedChartData2.series" />-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </vs-tab>-->
<!--&lt;!&ndash;                            <vs-tab label="GBP">&ndash;&gt;-->
<!--&lt;!&ndash;                                <div>&ndash;&gt;-->
<!--&lt;!&ndash;                                    GBP&ndash;&gt;-->
<!--&lt;!&ndash;                                </div>&ndash;&gt;-->
<!--&lt;!&ndash;                            </vs-tab>&ndash;&gt;-->
<!--                        </vs-tabs>-->
<!--                    </div>-->
<!--                </vx-card>-->
<!--            </div>-->
<!--        </div>-->
        <div class="flex flex-wrap mb-10">
            <div class="w-full">
                <vx-card title="Недавние платежи">
                    <vs-table v-if="transactions" ref="table" :data="recentTransactions" class="table-dark-inverted">

                        <template slot="thead">
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
                            <vs-tr :key="indextr" v-for="(tr, indextr) in data" >

                                <vs-td>
                                    <p>{{ tr.created_at }}</p>
                                </vs-td>

                                <vs-td>
                                    <p>{{ tr.beneficiary }}</p>
                                </vs-td>

                                <vs-td>
                                    <p>{{ tr.reference }}</p>
                                </vs-td>

                                <vs-td>
                                    <p>{{ tr.user_account.iban }}</p>
                                </vs-td>

                                <vs-td>
                                    <span>{{ getPaymentStatus(tr.status) }}</span>
                                </vs-td>

                                <vs-td>
                                    <p>{{ getPaymentType(tr.type) }}</p>
                                </vs-td>

                                <vs-td>
                                    <p class="product-price">{{ tr.amount.toFixed(2) }}</p>
                                </vs-td>

                            </vs-tr>
                            </tbody>
                        </template>
                    </vs-table>
                </vx-card>
            </div>
        </div>
    </div>
</template>

<script>
    import BalanceCard from "./BalanceCard";
    import VueApexCharts from 'vue-apexcharts'
    import chartConfigs from "@js/common/components/statistics-cards/chartConfigs.js"

    export default {
        name: "Overview",
        components: { BalanceCard, VueApexCharts },
        data() {
            return {
                subscribersGainedChartData: {
                    series      : [{
                        name: '$',
                        data: [2405.20 , 5600.50, 8730.15, 10050.40, 11500.80, 12800.90, 14342.12]
                    }],
                    chartOptions: {
                        grid      : {
                            show   : false,
                            padding: {
                                left : 0,
                                right: 0
                            }
                        },
                        chart     : {
                            toolbar  : {
                                show: false,
                            },
                            sparkline: {
                                enabled: true
                            },
                            shadow: {
                                enabled: true,
                                color: '#000',
                                top: 18,
                                left: 7,
                                blur: 10,
                                opacity: 1
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke    : {
                            curve: 'smooth',
                            width: 2.5
                        },
                        fill      : {
                            type    : 'gradient',
                            gradient: {
                                shadeIntensity: 0.9,
                                opacityFrom   : 0.7,
                                opacityTo     : 0.5,
                                stops         : [0, 80, 100]
                            }
                        },
                        xaxis     : {
                            type      : 'numeric',
                            lines     : {
                                show: true,
                            },
                            axisBorder: {
                                show: false,
                            },
                            labels    : {show: false}
                        },
                        yaxis     : [{
                            y      : 0,
                            offsetX: 0,
                            offsetY: 0,
                            padding: {left: 0, right: 0},
                        }],
                        tooltip   : {
                            x: {show: false}
                        },
                    },
                },
                subscribersGainedChartData2: {
                    series      : [{
                        name: '€',
                        data: [2405.20 , 5600.50, 8730.15, 10050.40, 11500.80, 12800.90, 14342.12]
                    }],
                    chartOptions: {
                        grid      : {
                            show   : false,
                            padding: {
                                left : 0,
                                right: 0
                            }
                        },
                        chart     : {
                            toolbar  : {
                                show: false,
                            },
                            sparkline: {
                                enabled: true
                            },
                            shadow: {
                                enabled: true,
                                color: '#000',
                                top: 18,
                                left: 7,
                                blur: 10,
                                opacity: 1
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke    : {
                            curve: 'smooth',
                            width: 2.5
                        },
                        fill      : {
                            type    : 'gradient',
                            gradient: {
                                shadeIntensity: 0.9,
                                opacityFrom   : 0.7,
                                opacityTo     : 0.5,
                                stops         : [0, 80, 100]
                            }
                        },
                        xaxis     : {
                            type      : 'numeric',
                            lines     : {
                                show: true,
                            },
                            axisBorder: {
                                show: false,
                            },
                            labels    : {show: false}
                        },
                        yaxis     : [{
                            y      : 0,
                            offsetX: 0,
                            offsetY: 0,
                            padding: {left: 0, right: 0},
                        }],
                        tooltip   : {
                            x: {show: false}
                        },
                    },
                },



            }
        },
        watch: {
        },
        computed: {
            transactions() {
                return this.$store.state.transactions
            },
            recentTransactions() {
                return this.transactions.slice(0,9) || []
            }
        },
        methods: {
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
        },
        created() {

            // Assign chart options
            // this.chartOptions = JSON.parse(JSON.stringify(chartConfigs.lineChartOptions))
            //
            // this.chartOptions.fill.gradient.gradientToColors = [this.gradientToColor(this.colorTo || this.color)]
            // this.chartOptions.colors = [this.getHex(this.color)]
        },
        mounted() {
            // this.$refs.apexChart.updateOptions({ theme: { monochrome: { color: this.getHex(this.color) } } });
        }
    }
</script>

<style lang="scss">
    .overview-balance {
        .vs-tabs--ul.vs-tabs--ul {
            box-shadow: none;
        }
    }
</style>
