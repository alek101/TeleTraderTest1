<template>
    <div class="container">
        <div class="header">
            <router-link to="/">Home</router-link>
            <router-link to="/favorites" v-if="isLoged">Favorites</router-link>
            <button @click="logIn" v-if="!isLoged" class="login">Login</button></div>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name: 'MainComponent',
        data() {
            return {
                isLoged: false,
                favorites: {},
                tradingPairs: [],              
            }
        },
        methods: {
            async logIn(){
                await fetch('/api/login');
                this.isLoged = true;
            }
        },
        async beforeMount() {
            // console.log('Component mounted.')
            const res = await fetch('/api/status');
            const data = await res.json();
            this.isLoged=data.isLoged;
            this.favorites=data.favorites;
        }   
    }
</script>
