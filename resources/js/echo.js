import Vue from 'vue';

new Vue({
    el: '#app',

    data: {
        socket: null,

        input: null,

        inputs: [],
    },

    mounted() {
        this.connect();
    },

    methods: {
        connect() {
            this.socket = new WebSocket('ws://'+location.host+'/echo');

            this.socket.addEventListener('open', () => {
                this.initInput();
            });

            this.socket.addEventListener('message', event => {
                this.inputs.push(event.data);
            });

            this.socket.addEventListener('close', event => {
                this.inputs.push('Connection lost!');
            });
        },

        initInput() {
            this.input = this.$el.querySelector('#input');

            this.input.addEventListener('keypress', event => {
                if (event.key.toLowerCase() === 'enter') {
                    this.wantEcho();
                }
            });

            this.input.focus();

            document.addEventListener('click', () => {
                this.input.focus();
            });
        },

        wantEcho() {
            if (this.socket.readyState !== this.socket.OPEN) {
                return alert('Connection lost! Refresh this page to reconnect the Echo Server.');
            }

            const message = this.input.value.trim();

            if (message.length === 0) {
                return;
            }

            this.socket.send(message);

            this.input.value = '';
        },
    },
});
