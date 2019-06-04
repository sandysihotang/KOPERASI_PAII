<template>
    <div>
        <div id="invoice" class="container">
            <b-row>
                <div v-for="bar in barcode">
                    <b-col md="6">
                        <label>{{ bar.nama_barang }}</label>
                        <barcode v-bind:value="bar.code_barang">
                            Show this if the rendering fails.
                        </barcode>
                    </b-col>
                </div>
            </b-row>
        </div>
    </div>
</template>

<script>
    import barcode from 'vue-barcode'

    export default {
        components: {
            'barcode': barcode
        },
        data() {
            return {
                id: this.$route.params.id,
                barcode: []
            }
        },
        methods: {
            print() {
                const modal = document.getElementById('invoice')
                const cloned = modal.cloneNode(true)
                let section = document.getElementById('print')

                if (!section) {
                    section = document.createElement('div')
                    section.id = 'print'
                    document.body.appendChild(section)
                }

                section.innerHTML = '';
                section.appendChild(cloned);
                window.print();
            }
        },
        created() {
            axios.get('api/getBarcode/' + this.id, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                .then(e => {
                    this.barcode = e.data
                    setTimeout(()=>this.print(),1000)
                    setTimeout(()=>this.$router.push('/Kelolatitipan'),2000)
                })
        }
    }
</script>
<style scoped>
</style>
