<template>
<div>
    <h1>Home page</h1>
    <div class="table1">
        <div class="tableLine1">
            <div class="entry">Name</div>
            <div class="entry">Last</div>
            <div class="entry">Change</div>
            <div class="entry">Change Percent</div>
            <div class="entry">High</div>
            <div class="entry">Low</div>
        </div>
        <div class="tableLine1" v-for="pair in tradingPairs" :key="pair.name">
            <router-link v-bind:to="'/details/'+pair.name">{{pair.name}}</router-link>
            <div class="entry">{{pair.last.toFixed(2)}}</div>
            <div class="entry">{{pair.change.toFixed(2)}}</div>
            <div class="entry">{{pair.changePercentage}}</div>
            <div class="entry">{{pair.high.toFixed(2)}}</div>
            <div class="entry">{{pair.low.toFixed(2)}}</div>
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
                    {
                        name: 'BTCUSD',
                        last: 32877,
                        change: 1488,
                        high:33639,
                        low: 30968
                    },
                    {
                        name: 'ETHUSD',
                        last: 1825.6,
                        change: 54.1,
                        high:1904,
                        low: 1753
                    },
                    {
                        name: 'LTCUSD',
                        last: 126.32,
                        change: 4.08,
                        high:130.52,
                        low: 121
                    },
                    {
                        name: 'LTCBTC',
                        last: 0.0038322,
                        change: -0.0000698,
                        high: 0.0039407,
                        low: 0.0038195
                    },
                    {
                        name: 'ETHBTC',
                        last: 0.055492,
                        change: -0.0000698,
                        high: 0.0038195,
                        low: 0.055218
                    }
                ],              
            }
        },
        methods: {
         handlePercentage(change,last){
             const num = (change+last)/last*100-100;
             return num>0? '+'+num.toFixed(2)+'%':num.toFixed(2)+'%';
         }
        },
        async beforeMount() {
            this.tradingPairs.forEach(pair=>pair.changePercentage=this.handlePercentage(pair.change, pair.last));
            // const pairs = await fetch('https://api.bitfinex.com/v1/symbols',{
            //     mode: 'no-cors',
            // });
            // console.log('res',pairs);
            // const pairsData = await pairs.json();
            // console.log('pd',pairsData);
            // for (let i=0; i<5; i++){
            //     const newTradingPair = {pair: pairsData[i]};
            //     console.log(newTradingPair)
            //     this.tradingPairs.push(newTradingPair);
            // }
        } ,

}
</script>

<style>
    .tableLine1 {
        display: grid;
        grid-template-columns: repeat(6, 200px);
        
        border: 1px solid black;
    }
    .entry {
        justify-content: center;
    }
</style>