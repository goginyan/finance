<template>
<!--    <div class="vx-col w-full lg:w-2/3 mb-base mx-auto">-->
<!--        <vx-card title="Chat">-->
<!--            <template slot="no-body">-->
<!--                <div class="chat-card-log">-->
<!--                    <VuePerfectScrollbar ref="chatLogPS" class="scroll-area pt-6 px-6" :settings="settings">-->
<!--                        <ul ref="chatLog">-->
<!--                            <li class="flex items-start" :class="{'flex-row-reverse': msg.isSent, 'mt-4': index}" v-for="(msg, index) in chatLog" :key="index">-->
<!--                                <div class="msg relative bg-white shadow-md py-3 px-4 mb-2 rounded-lg max-w-md" :class="{'chat-sent-msg bg-primary-gradient text-white': msg.isSent, 'border border-solid d-theme-border-grey-light': !msg.isSent}">-->
<!--                                    <span>{{ msg.msg }}</span>-->
<!--                                </div>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </VuePerfectScrollbar>-->
<!--                </div>-->
<!--                <div class="flex bg-white p-6 chat-input-container">-->
<!--                    <vs-input class="mr-3 w-full" v-model="chatMsgInput" @keyup.enter="chatMsgInput = ''" placeholder="Type Your Message" ></vs-input>-->
<!--                    <vs-button icon-pack="feather" icon="icon-send" @click="chatMsgInput = ''"></vs-button>-->
<!--                </div>-->
<!--            </template>-->
<!--        </vx-card>-->
<!--    </div>-->
</template>

<script>
    import VuePerfectScrollbar from 'vue-perfect-scrollbar'

    export default {
        name: "Messages",
        components: { VuePerfectScrollbar },
        data() {
            return {
                chatMsgInput: '',
                chatLog: [
                    { msg: 'Cake sesame snaps cupcake gingerbread', isSent: false },
                    { msg: 'Apple pie pie jujubes chupa chups muffin', isSent: true },
                    { msg: 'Chocolate cake', isSent: false },
                    { msg: 'Donut sweet pie oat cake dragée fruitcake', isSent: true },
                    { msg: 'Liquorice chocolate bar jelly beans icing', isSent: false },
                    { msg: 'Pudding candy', isSent: true },
                ],
                settings: { // perfectscrollbar settings
                    maxScrollbarLength: 60,
                    wheelSpeed: .60,
                },
            }
        }
    }
</script>

<style lang="scss">
</style>
