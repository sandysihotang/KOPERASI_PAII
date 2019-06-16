<template>
    <div class="container">
        <b-breadcrumb :items="items"></b-breadcrumb>
        <div class="container alert" style="background-color: #f8f9fa;">
            <div class="col-md-12 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row float-right mb-2">
                            <b-button v-if="!datas.length && !createExcelShow" disabled class="btn btn-primary mr-1">
                                <font-awesome-icon icon="download"/>
                                Simpan Data
                            </b-button>
                            <b-button v-else-if="datas.length" class="btn btn-primary mr-1" @click="createExcel">
                                <font-awesome-icon icon="download"/>
                                Simpan Data
                            </b-button>
                            <b-button v-else-if="createExcelShow" class="btn btn-primary mr-1" disabled>
                                <b-spinner small type="grow"></b-spinner>
                                Saving...
                            </b-button>
                            <b-button v-b-modal.modal-1 class="btn btn-success">
                                <font-awesome-icon icon="plus-circle"/>
                                Tambah
                            </b-button>
                            <b-button v-b-modal.modal-2 variant="primary" class="btn">
                                <font-awesome-icon icon="plus-circle"/>
                                Tambah Kategori
                            </b-button>
                        </div>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">Nama</th>
                                <th scope="col" class="text-center">Kode Barang</th>
                                <th scope="col" class="text-center">Harga /buah</th>
                                <th scope="col" class="text-center">Qty</th>
                                <th scope="col" class="text-center">Total Harga</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="data in datas" :key="data.id">
                                <td class="text-center">{{ data.nama }}</td>
                                <td class="text-center">{{ data.code_produk }}</td>
                                <td class="text-center">{{ data.harga | currency}}</td>
                                <td class="text-center">{{ data.jumlah }}</td>
                                <td class="text-center">{{ data.harga*data.jumlah | currency}}</td>
                                <td class="text-center">
                                    <i style="cursor: pointer;" class="btn btn-warning btn-sm">
                                        <font-awesome-icon icon="edit" @click="edit(data.id)"/>
                                    </i>
                                    <i style="cursor: pointer;" class="btn btn-danger btn-sm">
                                        <font-awesome-icon icon="calendar-times" @click="deleteBarang(data.id)"/>
                                    </i>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-center"><b>Total Seluruhnya</b></td>
                                <td colspan="2" class="text-center"><b>{{ totalBarang | currency }}</b></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <b-modal id="modal-1" ref="modal1" title="Tambah Produk" hide-footer>
                <form_produk v-on:refresh="refreshData" ref="form_input"></form_produk>
            </b-modal>
            <b-modal id="modal-2" ref="modal2" title="Tambah Kategori" hide-footer>
                <form @submit.prevent="tambah_kategory()">
                    <div class="form-group">
                        <label>Kategori</label>
                        <input type="text" v-model="kategori" class="form-control">
                    </div>
                    <div class="form-group float-right">
                        <b-button variant="primary" @click="tambah_kategory()">Tambah</b-button>
                    </div>
                </form>
            </b-modal>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                kategori: null,
                createExcelShow: false,
                items: [
                    {
                        text: 'Dashboard',
                        to: {path: '/dashboard'}
                    },
                    {
                        text: 'Inventori',
                        to: {path: '/produk'}
                    },
                    {
                        text: 'Pemasukan Barang',
                        active: true
                    }
                ],
                dataKey: {
                    harga: null,
                    jumlah: null
                },
                datas: [],
                totalHarga: [],
                totalBarang: 0
            }
        },
        methods: {
            tambah_kategory() {
                if (this.kategori == null) {
                    this.$swal({
                        position: 'center',
                        type: 'error',
                        width: 300,
                        title: 'Isi Kategori',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    return
                }
                axios.post('api/createKategori', {kategori: this.kategori}, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        this.kategori = null
                        this.$nextTick(() => {
                            this.$refs.modal2.hide()
                            this.$refs.form_input.getKategori()
                            this.$swal({
                                position: 'center',
                                type: 'success',
                                width: 300,
                                title: 'Berhasil menambah kategori',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        })
                    })
            },
            deleteBarang(id) {
                axios.delete('api/deleteNewProduk/' + id, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        this.refreshData()
                        const Toast = this.$swal.mixin({
                            toast: true,
                            position: 'top-end',
                            width: 300,
                            showConfirmButton: false,
                            timer: 3000
                        });
                        Toast.fire({
                            type: 'success',
                            title: 'Barang Berhasil Dihapus'
                        })
                    })
            },
            edit(id) {
                axios.get('api/getProduk/' + id, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(async e => {
                        this.dataKey.harga = e.data.harga
                        this.dataKey.jumlah = e.data.jumlah
                        const {value: formValues} = await this.$swal.fire({
                            title: 'Edit Jumlah Dan Harga',
                            html:
                                '<label for="swal-input1" style="font-size:25px;">Harga: </label><br><input id="swal-input1" type="number" class="form-group swal2-input" placeholder="Harga" value="' + this.dataKey.harga + '"><br>' +
                                '<label for="swal-input2" style="font-size:25px;">Jumlah:</label><br><input id="swal-input2" type="number" class="form-group swal2-input" placeholder="Jumlah" value="' + this.dataKey.jumlah + '">',
                            focusConfirm: false,
                            preConfirm: () => {
                                return [{
                                    harga: document.getElementById('swal-input1').value,
                                    jumlah: document.getElementById('swal-input2').value
                                }]
                            }
                        })
                        if (formValues.length > 0) {
                            this.createExcelShow = true
                            axios.put('api/editNewProduk/' + id, formValues, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                                .then(e => {
                                    this.createExcelShow = false
                                    this.refreshData()
                                    const Toast = this.$swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Barang Berhasil Diubah'
                                    })
                                })
                        }
                    })
            },
            fetchData() {
                axios.get('api/getPemasukan', {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(({data}) => (this.datas = data))
            },
            getTotal() {
                axios.get('api/getTotal', {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        if (e.data[0]) {
                            var sum = 0
                            for (var i = 0; i < e.data[0].produk.length; i++) {
                                sum += (e.data[0].produk[i].harga * e.data[0].produk[i].jumlah)
                            }
                            this.totalBarang = sum
                        } else {
                            this.totalBarang = 0
                        }
                    })
            },
            refreshData() {
                this.getTotal()
                this.fetchData()
                this.$nextTick(() => {
                    this.$refs.modal1.hide()
                })
            },
            async createExcel() {

                const {value: formValues} = await this.$swal.fire({
                    title: 'Masukkan Data Vendor',
                    html:
                        '<label for="swal-input1" style="font-size:25px;">Nama Suplier: </label><br><input id="swal-input1" class="swal2-input" placeholder="Nama Vendor""><br>' +
                        '<label for="swal-input2" style="font-size:25px;">No Telepon Suplier:</label><br><input id="swal-input2" class="swal2-input" placeholder="No Telepone">',
                    focusConfirm: false,
                    preConfirm: () => {
                        return {
                            nama: document.getElementById('swal-input1').value,
                            telp: document.getElementById('swal-input2').value
                        }
                    }
                })
                if (formValues.nama.length > 0 && formValues.telp.length > 0) {
                    axios.post('api/createExcel', {formValues}, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                        .then(e => {
                            const Toast = this.$swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            Toast.fire({
                                type: 'success',
                                title: 'Berhasil Menyimapan Barang'
                            })
                            this.refreshData()
                        })
                } else {
                    this.$swal.fire({
                            type: 'error',
                            width: 300,
                            showConfirmButton: false,
                            title: "Masukkan Nama Vendor atau No Telp!!",
                            timer: 2000
                        }
                    )
                }
            }
        },
        created() {
            this.refreshData()
        }
    }
</script>

<style>
</style>
