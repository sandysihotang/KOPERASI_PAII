<template>
    <div v-if="labels!==null">
        <chartjs-bar :datalabel="mylabel" :bind="true" :height="height" v-bind:labels="labels" v-bind:datasets="datasets"
                     v-bind:option="option"></chartjs-bar>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                mylabel:'TestDataLabel',
                height: 500,
                labels: [],
                datasets: [{
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWitdh: 1,
                    data: []
                }],
                option: {
                    title: {
                        display: true,
                        position: 'bottom',
                        text: '5 Teratas Produk Yang Paling Sering Dibeli(Buah)',
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            }
        },
        methods: {
            fetchData() {
                this.labels = []
                this.datasets[0].data = []
                axios.get('api/getForMore', {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        for (var i in e.data) {
                            this.labels.push(e.data[i].nama)
                            this.datasets[0].data.push(Number(e.data[i].jumlah))
                        }
                    })
            }
        }
        ,
        created() {
            this.fetchData()
        }
    }
</script>

<style>
</style>
