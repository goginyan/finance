<template>
    <div id="page-user-view">
        <div id="user-data">

            <vx-card title="Профиль" class="mb-base">

                <div class="vx-row">

                    <!-- Information - Col 1 -->
                    <div class="vx-col flex-1" id="account-info-col-1">
                        <table>
                            <tr>
                                <td class="font-semibold">ID пользователя</td>
                                <td>{{ user.public_id }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Имя</td>
                                <td>{{ user.name }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Фамилия</td>
                                <td>{{ user.last_name }}</td>
                            </tr>
                        </table>
                    </div>
                    <!-- /Information - Col 1 -->

                    <!-- Information - Col 2 -->
                    <div class="vx-col flex-1" id="account-info-col-2">
                        <table>
                            <tr>
                                <td class="font-semibold">Email</td>
                                <td>{{ user.email }}</td>
                            </tr>
                        </table>
                    </div>
                    <!-- /Information - Col 2 -->
                    <div class="vx-col w-full flex" id="account-manage-buttons">
                        <vs-button icon-pack="feather" icon="icon-edit" v-on:click="editPassword()" class="mr-4">Сменить пароль</vs-button>
                    </div>

                </div>

            </vx-card>

            <div class="vx-row">
                <div class="vx-col lg:w-1/2 w-full">
                    <vx-card title="Информация" class="mb-base mh-300">
                        <table>
                            <tr>
                                <td class="font-semibold">Дата рождения</td>
                                <td>{{ user.details.birth_date }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Номер ID документа</td>
                                <td>{{ user.details.document_id }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Номер телефона</td>
                                <td>{{ user.details.phone_number }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Адрес</td>
                                <td>{{ user.details.address }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Страна</td>
                                <td>{{ user.details.country }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Почтовый индекс</td>
                                <td>{{ user.details.zip_code }}</td>
                            </tr>
                        </table>
                    </vx-card>
                </div>
                <div class="vx-col lg:w-1/2 w-full">
                    <vx-card title="Настройки" class="mb-base mh-300">
                        <table class="lh-2">
                            <tr>
                                <td class="font-semibold">Получать информацию о <br> актульных предложениях</td>
                                <td><vs-switch v-model="user.details.marketing" /></td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Получать уведомления</td>
                                <td><vs-switch v-model="user.details.notifications" /></td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Язык общения с оператором</td>
                                <td>
                                    <vs-select v-model="user.details.language">
                                        <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="item,index in options1" />
                                    </vs-select>
                                </td>
                            </tr>
                        </table>
                        <div class="vx-col w-full flex" id="save-settings">
                            <vs-button icon-pack="feather" icon="icon-check-square" class="mr-4" v-on:click="saveDetails()">Сохранить</vs-button>
                        </div>
                    </vx-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ProfileView',
        data() {
            return {
                options1: [
                    {text: 'Русский', value: 'ru'},
                    {text: 'Latvian', value: 'lv'},
                    {text: 'English', value: 'en'},
                ],
            }
        },
        computed: {
            user() {
                return this.$store.state.user
            }
        },
        methods: {
            editPassword() {
                this.$router.push('/profile/password')
            },
            saveDetails() {
                this.$store.dispatch('updateDetails', this.user)
                    .then(() => {
                        this.$vs.notify({
                            title: 'Сохранено',
                            text: 'Настройки пользователя сохранены',
                            color: 'success',
                            position: 'top-right'
                        })
                    })
                    .catch(() => {
                        this.$vs.notify({
                            title: 'Ошибка',
                            text: 'Не удалось сохранить настройки',
                            color: 'danger',
                            position: 'top-right'
                        })
                    })
            }
        }

    }
</script>

<style lang="scss">
    #avatar-col {
        width: 10rem;
    }

    .vx-card.mh-300 {
        min-height: 300px;
    }

    table.lh-2 {
        tr {
            line-height: 2;
        }
    }

    #user-data table {
        width: 100%;
    }

    #page-user-view {
        table {
            td {
                vertical-align: top;
                min-width: 140px;
                padding-bottom: .8rem;
                word-break: break-all;
            }

            &:not(.permissions-table) {
                td {
                    @media screen and (max-width:370px) {
                        display: block;
                    }
                }
            }
        }
    }

    @media screen and (min-width:1201px) and (max-width:1211px),
    only screen and (min-width:636px) and (max-width:991px) {
        #account-info-col-1 {
            width: calc(100% - 12rem) !important;
        }
    }

</style>
