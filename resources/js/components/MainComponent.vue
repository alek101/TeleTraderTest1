<template>
    <div class="container">
        <div class="header">
            <div>
                <router-link to="/">Home</router-link>
                <router-link to="/favorites" v-if="isLoged">Favorites</router-link>  
            </div>
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
            }
        },
        methods: {
            async logIn(){
                await fetch('/api/login',{
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        login: true,
                    })
                });
                this.isLoged = true;
            }
        },
        async beforeMount() {
            const res = await fetch('/api/status');
            const data = await res.json();
            this.isLoged=data.isLoged;
        }   
    }
</script>


<style>
    .header {
        display: flex;
        justify-content: space-between;
        color: rgb(63, 216, 178);
        font-weight: bold;
        padding: 10px;
        margin-bottom: 10px;
    }
    .login {
        width: 148px;
        height: 38px;
        background-color: rgb(63, 216, 178);
        color: white;
        border: 1px solid rgb(63, 216, 178);
    }
    .login:hover {
        color: rgb(63, 216, 178);
        background-color: white;
        border: 1px solid rgb(63, 216, 178);
        cursor: pointer;
    }
</style>