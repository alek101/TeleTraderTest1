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
    name: 'HomePage',
     data() {
        return {
            tradingPairs: [],  
            pairs: []            
        }
    },
    methods: {
        handlePercentage(num){
            num = num  *100;
            return num>0? '+'+num.toFixed(2)+'%':num.toFixed(2)+'%';
        },
    getTopFive(array) {
    const array2 = array.slice(0,5);
    const result = [];
    for(let mem of array2){
        result.push(mem.toUpperCase());
    }
    return result;
    }
    },
    async beforeMount() {
        const res = await fetch('/api/getSymbols',{
            method: 'GET',
            headers: {
                'Access-Control-Allow-Origin': '*',
                'Content-Type': 'application/json',
            },
            mode: 'cors'
        })
            const resr= await res.json();
            

            if(resr.code) {
            alert(resr.error_description);
        }
        else
        {
            this.pairs = this.getTopFive(resr);
                const that = this;
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
        }     
}
</script>

<style>
    .table1 {
        border: 1px solid black;
        border-radius: 3px;
        box-shadow: 3px 3px 9px rgba(0, 0, 0, 0.452);
        padding: 25px;
    }
    .tableLine1 {
        display: grid;
        grid-template-columns: repeat(6, 200px);
        border-bottom: 1px solid gray;    
    }
    .entry {
        justify-content: center;
    }
    .headerTable {
        font-weight: bold;
    }
    .symbolLink {
        color: rgb(63, 216, 178);
        font-weight: bold;
    }
</style>