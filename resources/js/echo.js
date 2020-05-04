import Vue from 'vue';

new Vue({
    el: '#app',

    data: {
        socket: null,

        input: null,

        inputs: [],

        box: null,
    },

    mounted() {
        this.box = this.$el.querySelector('#box');
        this.connect();
    },

    methods: {
        connect() {
            this.socket = new WebSocket('ws://'+location.host+'/echo');

            this.socket.addEventListener('open', () => {
                this.initInput();
            });

            this.socket.addEventListener('message', event => {
                this.push(event.data);
            });

            this.socket.addEventListener('close', event => {
                this.push('Connection lost!');
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

        push(content) {
            if (this.inputs.length === 100) {
                this.inputs.splice(0, 1);
            }

            this.inputs.push(content);

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
