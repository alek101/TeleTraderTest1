<template>
    <div>
    <div class="table1">
        <div class="tableLine1 headerTable">
            <div class="entry">Name</div>
            <div class="entry">Last</div>
            <div class="entry">Change</div>
            <div class="entry">Change Percent</div>
            <div class="entry">High</div>
            <div class="entry">Low</div>
        </div>
        <div class="tableLine1" v-for="pair in tradingPairs" :key="pair.name">
            <div class="entry symbolLink"><router-link v-bind:to="'/details/'+pair.name">{{pair.name}}</router-link></div>
            <div class="entry">{{pair.last}}</div>
            <div class="entry">{{pair.change}}</div>
            <div class="entry">{{pair.changePercentage}}</div>
            <div class="entry">{{pair.high}}</div>
            <div class="entry">{{pair.low}}</div>
        </div>
    </div>
    </div>
</template>

<script>
export default {
    name: 'Favorites',
    data(){
        return {
            tradingPairs: [
                   
                ],
            isLoged: false,
            favorites: {},      
        }
    },
    
    
     methods: {
         handlePercentage(num){
             num = num  *100;
             return num>0? '+'+num.toFixed(2)+'%':num.toFixed(2)+'%';
         }
        },
        async beforeMount() {
            const res = await fetch('/api/status');
            const data = await res.json();
            this.isLoged=data.isLoged;
            this.favorites=data.favorites;
            const pairs = Object.keys(this.favorites);
             for(let i=0; i<pairs.length; i++){
                
                let np = {
                            name: pairs[i],
                            last: null,
                            change: null,
                            changePercentage: null,
                            high: null,
                            low: null
                            };
                this.tradingPairs.push(np);
               let ws = new WebSocket('wss://api-pub.bitfinex.com/ws/2');

                ws.onopen = function(){
                    ws.send(JSON.stringify({
                        "event":"subscribe",
                        "channel":"ticker",
                        "ticket":pairs[i]
                    }));
        
                }

                ws.onmessage = function(msg){
                        let response = JSON.parse(msg.data);
                        if(response[1] && response[1] != "hb")
                        {
                           console.log(pairs[i], response); 
                            this.tradingPairs[i].lastPrice = (response[1][6]).toFixed(2);
                            this.tradingPairs[i].change = (response[1][4]).toFixed(2);
                            this.tradingPairs[i].changePercentage = this.handlePercentage(response[1][5]);
                            this.tradingPairs[i].high = (response[1][8]).toFixed(2);
                            this.tradingPairs[i].low = (response[1][9]).toFixed(2);
                        }
                        
                    }
            }
        } ,

}
</script>

<style scoped>

</style>