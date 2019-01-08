<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-body p-0">
                        <ul class="list-unstyled" style="height:300px;overflow-y:scroll;">
                            <li class="p-2" v-for="(message, index) in messages" :key="index">
                                <strong>{{ message.user.name }}</strong>
                                {{ message.message }}
                            </li>
                        </ul>
                    </div>
                    <input type="text" name="message" class="form-control" placeholder="Enter message" v-model="sendMessage" @keyup.enter="postMessage">
                </div>
                <span class="text-muted">user is typing...</span>
            </div>
            <div class="col-md-4">
                <div class="card card-default">
                    <div class="card-header">
                        Active User
                    </div>
                    <div class="card-body">
                        <ul>
                            <li class="py-2" v-for="(user, index) in users" :key="index">
                                {{ user.name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        data() {
            return {
                messages: [],
                sendMessage: '',
                users: []
            }
        },
        created() {
            this.fetchMessages()
            Echo.join('chat')
            .here(user => {
                this.users = user
            })
            .joining(user => {
                this.users.push(user)
            })
            .leaving(user => {
                this.users = this.users.filter(u => u.id != user.id)
            })
            .listen('MessageEvent', (event) => {
                this.messages.push(event.msg)
            })
        },
        methods: {
            fetchMessages() {
                axios.get('messages').then(response => {
                    this.messages = response.data
                })
            },
            postMessage() {
                this.messages.push({
                    user: this.user,
                    message: this.sendMessage
                })
                axios.post('messages', {message: this.sendMessage})
                this.sendMessage = ''
            }
        }
    }
</script>
