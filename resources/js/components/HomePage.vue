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
                tradingPairs: [
                ],              
            }
        },
        methods: {
         handlePercentage(num){
             num = num  *100;
             return num>0? '+'+num.toFixed(2)+'%':num.toFixed(2)+'%';
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
            console.log(res);
            const resr= await res.json();
            const pairs = await resr.slice(0,5);
            // const pairs = ["BTCUSD","LTCUSD","LTCBTC","ETHUSD","ETHBCD"];
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