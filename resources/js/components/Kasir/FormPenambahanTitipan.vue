<template>
    <div class="container">
        <b-container>
            <b-form @submit.prevent="addProduct">
                <b-row>
                    <b-form-group label="Nama Produk" class="col-md-12">
                        <b-form-input
                            v-model="formBaru.nama"
                            placeholder="Masukkan Nama Produk"
                            required
                        ></b-form-input>
                    </b-form-group>
                </b-row>
                <b-row>
                    <b-form-group label="Harga" class="col-md-12">
                        <CurrencyInput
                            class="form-control"
                            v-model="formBaru.harga"
                            :currency="currency_input.currency"
                            :locale="currency_input.locale"
                            :validate-on-input="true"
                            placeholder="Harga Barang"
                        />
                    </b-form-group>
                </b-row>
                <b-row>
                    <b-form-group label="Jumlah Stock" class="col-md-12">
                        <b-form-input
                            v-model="formBaru.jumlah"
                            type="number"
                            min="1"
                            placeholder="Jumlah Stock"
                            required
                        ></b-form-input>
                    </b-form-group>
                </b-row>
                <div class="float-right">
                    <b-button variant="success" @click="addProdukTitipan"><i class="fa fa-plus"></i> Tambahkan
                    </b-button>
                    <b-button variant="Danger" class="ml-2" @click="resetData">Reset</b-button>
                </div>
            </b-form>
        </b-container>

    </div>
</template>

<script>
    import {CurrencyInput} from 'vue-currency-input'

    export default {
        components: {CurrencyInput},
        data() {
            return {
                currency_input: {
                    locale: 'id',
                    currency: 'IDR',
                },
                kategories: [{text: 'Select one', value: null}],
                show: true,
                creteq: false,
                nama_barang: null,
                formBaru: {
                    nama: null,
                    harga: null,
                    jumlah: null
                }
            }
        },
        methods: {
            resetData() {
                this.formBaru.nama = null
                this.formBaru.harga = null
                this.formBaru.jumlah = null
            },
            addProdukTitipan() {
                if (this.formBaru.nama === null || this.formBaru.harga === null || this.formBaru.jumlah === null) {
                    this.$swal({
                        position: 'center',
                        type: 'error',
                        width:300,
                        title: 'Isi Semua Form',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    return
                }
                axios.post('api/tambahBarang/' + this.$route.params.id, this.formBaru, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        this.$swal({
                            position: 'center',
                            width:300,
                            type: 'success',
                            title: 'Berhasil Menambah',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.$emit('refresh')
                        this.resetData()
                    })
            }
        },
    }
</script>

<style scoped>

</style>
