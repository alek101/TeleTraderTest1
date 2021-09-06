<template>
    <div class="container">
        <div class="header"><button @click="logIn" v-if="!isLoged" class="login">Login</button></div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                isLoged: false,
                favorites: {},
                page: 'home-page',
                tradingPairs: {},
            }
        },
        methods: {
            async logIn(){
                await fetch('/api/login');
                this.isLoged = true;
            }
        },
        async mounted() {
            // console.log('Component mounted.')
            const res = await fetch('/api/status');
            const data = await res.json();
            this.isLoged=data.isLoged;
            this.favorites=data.favorites;
        }
    }
</script>
