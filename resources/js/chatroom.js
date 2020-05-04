import Vue from 'vue';

new Vue({
    el: '#app',

    data: {
        me: {
            id: null,

            name: '',
        },

        socket: null,

        users_count: 0,

        message: '',

        messages: [],

        box: null,
    },

    mounted() {
        this.box = this.$el.querySelector('#box');
    },

    computed: {
        participant() {
            if (this.users_count === 1) {
                return '(1 participant)';
            }

            if (this.users_count > 1) {
                return '('+this.users_count+' participants)';
            }

            return '(no participants)';
        },
    },

    methods: {
        connect(event) {
            if (event.key.toLowerCase() !== 'enter') {
                return;
            }

            const name = this.me.name.trim();

            if (name.length === 0) {
                return;
            }

            if (name.length > 12) {
                return alert('The name may not be greater than 12 characters.');
            }

            this.socket = new WebSocket('ws://'+location.host+'/chatroom?name='+name);

            this.socket.addEventListener('message', event => {
                const message = JSON.parse(event.data);

                switch (message[0]) {
                    case 'connected':
                        this.me = message[1];
                        break;
                    case 'message':
                        this.pushMessage(message[1]['user'], message[1]['content']);
                        break;
                    case 'joined':
                        this.pushNotification(message[1]['user']['name']+' joined');
                        this.users_count = message[1]['users_count'];
                        break;
                    case 'left':
                        this.pushNotification(message[1]['user']['name']+' left');
                        this.users_count = message[1]['users_count'];
                        break;
                }
            });

            this.socket.addEventListener('close', event => {
                alert('Connection lost!');
            });
        },

        send(event) {
            if (event.key.toLowerCase() !== 'enter') {
                return;
            }

            if (this.socket.readyState !== this.socket.OPEN) {
                return alert('Server not connected.');
            }

            const message = this.message.trim();

            if (message.length === 0) {
                return
            }

            if (message.length > 1000) {
                return alert('The message content may not be greater than 1000 characters.');
            }

            this.socket.send(message);

            this.message = '';
        },

        pushMessage(user, content) {
            this.push({
                type: 'message',
                user: user,
                content: content,
            });
        },

        pushNotification(content) {
            this.push({
                type: 'notification',
                content: content,
            });
        },

        push(message) {
            if (this.messages.length === 100) {
                this.messages.splice(0, 1);
            }

            this.messages.push(message);

            setTimeout(() => {
                this.box.scroll({
                    top: this.box.scrollHeight,
                    left: 0,
                    behavior: 'smooth',
                })
            }, 100);
        },
    },
});
