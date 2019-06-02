<template>
    <b-container>
        <b-row>
            <b-col md="9"></b-col>
            <b-col md="3">
                <b-button @click="modalShow = !modalShow" variant="primary" class="c">Tambah Kategori</b-button>
                <b-modal
                    title="Tambah Kategori"
                    v-model="modalShow">
                    <b-form-group id="input-group" label="Kategori" label-for="input-kategory">
                        <b-form-input
                            id="input-kategory"
                            required></b-form-input>
                    </b-form-group>
                </b-modal>
            </b-col>
        </b-row>
        <b-row class="mt-2">
            <b-col md="6">
                <a href="">
                    <b-card
                        style="max-height: 23%"
                        class="mb-2 text-center">
                        <b-card-text><i class="fa fa fa-shopping-bag fa-3x"></i></b-card-text>
                        <b-card-body>Produk</b-card-body>
                    </b-card>
                </a>
            </b-col>
            <b-col md="6">
                <a href="">
                    <a href="">
                        <b-card
                            style="max-height: 23%"
                            class="mb-2 text-center">
                            <b-card-text><i class="fa fa fa-tag fa-3x"></i></b-card-text>
                            <b-card-body>Kategori</b-card-body>
                        </b-card>
                    </a>
                </a>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
    import BContainer from "bootstrap-vue/src/components/layout/container";
    import BRow from "bootstrap-vue/src/components/layout/row";
    import BCard from "bootstrap-vue/src/components/card/card";
    import BCardText from "bootstrap-vue/src/components/card/card-text";
    import BCardBody from "bootstrap-vue/src/components/card/card-body";

    export default {
        components: {BCardBody, BCardText, BCard, BRow, BContainer},
        data() {
            return {
                modalShow: false
            }
        }
    }
</script>

<style scoped>
    .c {
        max-width: 80% !important;
        border-radius: 23px;
        max-height: 10% !important;
    }
</style>
