<template>
<div>
    <div>
    <div class="table2">
        <div class="tableLine2 headerTable">
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
        <button @click="toggleFavorites" v-else class='addButton'>Add to favorites</button>  
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
                lastPrice: null,
                high: null,
                low: null,
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
            await fetch('/api/fav',{
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        fav: this.pair,
                    })
                });
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
        const r2 = await fetch('/api/getDetails/'+this.pair);
        const r2r = await r2.json();
        if(r2r.code){
            alert(r2r.error_description);
        }
        else {
            this.pairInfo.lastPrice = Number(r2r.last_price).toFixed(2);
            this.pairInfo.high = Number(r2r.high).toFixed(2);
            this.pairInfo.low = Number(r2r.low).toFixed(2); 
        }
    },
}
</script>

<style >
    .table2 {
        border: 1px solid black;
        border-radius: 3px;
        box-shadow: 3px 3px 9px rgba(0, 0, 0, 0.452);
        padding: 5px;
    }
    .tableLine2 {
        display: grid;
        grid-template-columns: 600px 200px 200px 200px;
        border-bottom: 1px solid gray;  
    }
    .removeButton {
        background-color: red;
        color: white;
    }
    .addButton {
        background-color: green;
        color: white;   
    }
    .addButton,.removeButton {
        height: 30px;
        margin-top: 10px;
    }
    .addButton:hover,.removeButton:hover {
        cursor: pointer;
    }
</style>