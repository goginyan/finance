<template>
    <div id="data-list-list-view" class="data-list-container">

        <vs-table ref="users-table" pagination :max-items="itemsPerPage" :data="users">

            <div slot="header" class="flex flex-wrap-reverse items-center flex-grow justify-between">
                <vs-button type="border" icon-pack="feather" icon="icon-plus" v-on:click="createUser">Создать</vs-button>
            </div>

            <template slot="thead">
                <vs-th></vs-th>
                <vs-th>ID</vs-th>
                <vs-th>Имя</vs-th>
                <vs-th>E-mail</vs-th>
                <vs-th>Дата регистрации</vs-th>
            </template>

            <template slot-scope="{data}">
                <tbody>
                <vs-tr :key="indextr" v-for="(tr, indextr) in data" >

                    <vs-td class="whitespace-no-wrap">
                        <feather-icon icon="EditIcon"
                                      class="cursor-pointer"
                                      svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                      @click.stop="editUser(tr.id)" />
                    </vs-td>

                    <vs-td>
                        <p>{{ tr.id }}</p>
                    </vs-td>

                    <vs-td>
                        <p>{{ getUserFullName(tr) }}</p>
                    </vs-td>

                    <vs-td>
                        <p>{{ tr.email }}</p>
                    </vs-td>

                    <vs-td>
                        <p>{{ tr.created_at }}</p>
                    </vs-td>

                </vs-tr>
                </tbody>
            </template>

        </vs-table>
    </div>
</template>

<script>
    export default {
        name: 'UserIndex',
        data() {
            return {
                itemsPerPage: 15,
            }
        },
        computed: {
            users() {
                return this.$store.state.allUsers
            },
        },
        methods: {
            getUserFullName(user){
                if (user.user_type === 1) {
                    return user.name + ' ' + user.last_name
                }
                return user.details.company_name
            },
            editUser(id) {
                this.$router.push({name: 'user-edit', params: {id: id}})
            },
            createUser() {
                this.$router.push({name: 'user-create'})
            }
        },
    }
</script>
