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
            tradingPairs: [],
            isLoged: false,
            favorites: {}, 
            pairs: [],     
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
        const resr = await res.json();
        if(resr.code){
            alert(r2r.error_description);
        }
        else {
            this.isLoged=resr.isLoged;
        this.favorites=resr.favorites;
        this.pairs = Object.keys(this.favorites);
        const that=this;
            for(let i=0; i<that.pairs.length; i++){
            
            let np = {
                    name: that.pairs[i],
                    last: null,
                    change: null,
                    changePercentage: null,
                    high: null,
                    low: null
                };
            that.tradingPairs.push(np);
            let ws = new WebSocket('wss://api-pub.bitfinex.com/ws/2');

            ws.onopen = function(){
                ws.send(JSON.stringify({
                        event: 'subscribe', 
                    channel: 'ticker', 
                    symbol: 't'+that.pairs[i], 
                }));
            }

            ws.onmessage = function(msg){
                    let response = JSON.parse(msg.data);
                    console.log(that.pairs[i], response); 
                    if(response[1] && response[1] != "hb")
                    {
                        that.tradingPairs[i].last = (response[1][6]).toFixed(2);
                        that.tradingPairs[i].change = (response[1][4]).toFixed(2);
                        that.tradingPairs[i].changePercentage = that.handlePercentage(response[1][5]);
                        that.tradingPairs[i].high = (response[1][8]).toFixed(2);
                        that.tradingPairs[i].low = (response[1][9]).toFixed(2);
                    }
                }
            } 
        } 
    } ,

}
</script>

<style scoped>

</style>