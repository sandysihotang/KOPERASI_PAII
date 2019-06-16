<template>
    <div class="container">
        <b-breadcrumb :items="itemBreadCrumbs"></b-breadcrumb>
        <b-container align="center">
            <b-row class="align-content-center vertical-center">
                <b-col>
                    <div class="wrap">
                        <button class="button" @click="inventory">
                            <h1>
                                <font-awesome-icon icon="dolly-flatbed"/>
                            </h1>
                            <br>Inventory
                        </button>
                    </div>
                </b-col>
                <b-col>
                    <div class="wrap">
                        <button class="button" @click="pemasukanProduk">
                            <h1>
                                <font-awesome-icon icon="hand-holding-usd"/>
                            </h1>
                            <br>Pemasukan Barang
                        </button>
                    </div>
                </b-col>
                <b-col>
                    <div class="wrap">
                        <button class="button" @click="penjualanProduk">
                            <h1>
                                <font-awesome-icon icon="shopping-cart"/>
                            </h1>
                            <br>Penjualan Produk
                        </button>
                    </div>
                </b-col>
                <b-col class="float-center">
                    <div class="wrap">
                        <button class="button" @click="penitipanBarang">
                            <h1>
                                <font-awesome-icon icon="piggy-bank"/>
                            </h1>
                            <br>Barang Titipan
                        </button>
                    </div>
                </b-col>
            </b-row>
            <b-row class="align-content-center vertical-center mt-3">
                <b-col class="float-center">
                    <div class="wrap">
                        <button class="button" @click="pembelianBarang">
                            <h1>
                                <font-awesome-icon icon="chart-bar"/>
                            </h1>
                            <br>Pembelian
                        </button>
                    </div>
                </b-col>
                <b-col>
                    <div class="wrap">
                        <button class="button" @click="goToInventory">
                            <h1>
                                <font-awesome-icon icon="dolly-flatbed"/>
                            </h1>
                            <br>Inventory
                        </button>
                    </div>
                </b-col>
                <b-col>
                    <div class="wrap">
                        <button class="button" @click="goToTransaksi">
                            <h1>
                                <font-awesome-icon icon="money-check"/>
                            </h1>
                            <br>Transaksi
                        </button>
                    </div>
                </b-col>
                <b-col>
                    <div class="wrap">
                        <button class="button" @click="goToTitipan">
                            <h1>
                                <font-awesome-icon icon="dice"/>
                            </h1>
                            <br>Kelola Barang Titipan
                        </button>
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
            pemasukanProduk() {
                this.$router.push('/laporan/pemasukan')
            },
            penjualanProduk() {
                this.$router.push('/laporan/penjualan')
            },
            penitipanBarang() {
                this.$router.push('/laporan/penitipan')
            },
            pembelianBarang() {
                this.$router.push('/pembelianterbanyak')
            },
            goToInventory() {
                this.$router.push('/produk')
            },
            goToTransaksi() {
                this.$router.push('/transaksi')
            },
            goToTitipan() {
                this.$router.push('/Kelolatitipan')
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
        width: 200px;
        height: 200px;
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
