<template>
    <div class="container">
        <b-breadcrumb :items="itemBreadCrumbs"></b-breadcrumb>
        <b-container align="center">
            <b-row class="align-content-center vertical-center">
                <b-col>
                    <div class="wrap">
                        <button class="button" @click="inventory"><h1><font-awesome-icon icon="dolly-flatbed"/></h1>
                            <br>Inventory</button>
                    </div>
                </b-col>
                <b-col>
                    <div class="wrap">
                        <button class="button" @click="pemasukanProduk"><h1><font-awesome-icon icon="hand-holding-usd"/></h1>
                            <br>Pemasukan Barang</button>
                    </div>
                </b-col>
                <b-col>
                    <div class="wrap">
                        <button class="button" @click="penjualanProduk"><h1><font-awesome-icon icon="shopping-cart"/></h1>
                            <br>Penjualan Produk</button>
                    </div>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                itemBreadCrumbs: [
                    {
                        text: 'Dashboard',
                        active: true
                    }
                ]
            }
        },
        methods: {
            inventory() {
                this.$router.push('/inventory')
            },
            pemasukanProduk(){
                this.$router.push('/laporan/pemasukan')
            },
            penjualanProduk(){
                this.$router.push('/laporan/penjualan')
            }
        },
        created() {

        }
    }
</script>

<style scoped>
    .wrap {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .button {
        width: 300px;
        height: 300px;
        font-family: 'Roboto', sans-serif;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 2.5px;
        font-weight: 500;
        color: #000;
        background-color: #fff;
        border: none;
        border-radius: 45px;
        box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease 0s;
        cursor: pointer;
        outline: none;
    }

    .button:hover {
        background-color: #2EE59D;
        box-shadow: 0px 15px 20px rgba(46, 229, 157, 0.4);
        color: #fff;
        transform: translateY(-7px);
    }
</style>
