<template>
<div>
    <div>
    <h1>Details</h1>
    <div class="table2">
        <div class="tableLine2">
            <div class="entry">Symbol</div>
            <div class="entry">Last Price</div>
            <div class="entry">High</div>
            <div class="entry">Low</div>
            
        </div>
        <div class="tableLine2">
            <div class="entry">{{pair}}</div>
            <div class="entry">{{pairInfo.lastPrice}}</div>
            <div class="entry">{{pairInfo.high}}</div>
            <div class="entry">{{pairInfo.low}}</div>
        </div>
    </div>
    <div v-if="isLoged">
        <button @click="toggleFavorites" v-if="isFavorite" class='removeButton'>Remove from favorites</button>
        <button @click="toggleFavorites" v-else class='addButton'>Add to favorite</button>  
    </div>
    
    </div>
</div>
    
</template>

<script>
export default {
    name: 'Details',
    data(){
        return {
            pairInfo: {
                symbol: '',
                lastPrice: 0,
                high: 0,
                low: 0,
            },
            isLoged: false,
            favorites: {},
            isFavorite: false,
        }
    },
    props: [
        'pair'
    ],
    methods: {
        async toggleFavorites(){
            await fetch('/api/fav/'+this.pair);
            this.isFavorite=!this.isFavorite;
        }
    },
    async beforeMount(){
        const res = await fetch('/api/status');
            const data = await res.json();
            this.isLoged=data.isLoged;
            this.favorites=data.favorites;
            if(this.favorites[this.pair]){
                this.isFavorite=true;
            } 
    },
    
}
</script>

<style >
    .tableLine2 {
        display: grid;
        grid-template-columns: 600px 200px 200px 200px;
        
        border: 1px solid black;
    }

    .removeButton {
        background-color: red;
        color: white;
    }

    .addButton {
        background-color: green;
        color: white;
    }
</style>