<template>
    <vx-card title="Создать счет">
        <div class="vx-row mt-4">
            <div class="vx-col w-full">
                <vs-input label="Название счета" v-model="title" class="w-full max-w-sm" />
            </div>
        </div>
        <div class="vx-row mt-4">
            <div class="vx-col w-full">
                <vs-select v-model="currency" class="w-full select-large max-w-sm" label="Валюта">
                    <vs-select-item :key="index" :value="item.code" :text="item.sign + ' ' +item.name"
                                    v-for="(item,index) in currencies" class="w-full"/>
                </vs-select>
            </div>
        </div>
        <div class="vx-row mt-4">
            <div class="vx-col w-full">
                <vs-input label="PIN" v-model="pin" class="w-full max-w-sm" />
            </div>
        </div>
        <div class="vx-row mt-4">
            <div class="vx-col w-full">
                <vs-button v-on:click="formSubmitted">
                    Создать
                </vs-button>
            </div>
        </div>
    </vx-card>
</template>

<script>
    export default {
        name: 'AddAccount',
        components: {
        },
        data() {
            return {
                title: '',
                pin: '',
                currencies: [
                    {code: 'EUR', name: 'Евро', sign: '€'},
                    {code: 'USD', name: 'Доллар', sign: '$'},
                    {code: 'GBP', name: 'Фунт', sign: '£'},
                ],
                currency: {code: 'EUR', name: 'Евро', sign: '€'},
            }
        },
        methods: {
            formSubmitted() {
                // alert("Form submitted!");
            }
        },
    }
</script>

<style scoped>

</style>
